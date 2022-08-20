<?php

namespace App\Http\Controllers;

use App\Pagos;
use Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class PagosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $idOpenpay = "mbj7d0ylmxkrlg4m1tcu";
    private $keyOpenpay = "sk_382ccfcb3356474082d575c4facfefb6: ";
    private $identifiacador = 'DEVPROD';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN_SISTEMA,ADMIN_HEGO,ADMIN_AMI,COMPANY,CREAUSUARIOS');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $lista = Pagos::all()->orderBy('id', 'DESC')->paginate(20);
        } else {
            $lista = Pagos::where('usuarioid', Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        }

        $links = $lista->links();

        return view("pagos/index")->with(["links" => $links, "lista" => $lista]);
    }

    public function pagar()
    {
        $roles = \App\Roles::OrderBy('rol')->get();
        //$source_id=\App\Pagos::idOpenPay();
        return view('pagos/pagar')->with([
            'source_id' => 'demo'
        ]);
    }

    public function getPayPSE()
    {
        $pagos = new Pagos;
        $roles = \App\Roles::OrderBy('rol')->get();
        $create_dt = date("Y-m-d H:i:s");
        $idtransaccion = $_GET['id'];

        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/charges/" . $idtransaccion;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($resp, true);
        $status = $dato["status"];
        $user = Pagos::where('idtransaccion', $idtransaccion)
            ->update([
                'status' => $status,
                'respuesta' => $resp
            ]);

        return redirect()->action('PagosController@index');
    }

    public function pagarpse()
    {
        $roles = \App\Roles::OrderBy('rol')->get();
        //$source_id=\App\Pagos::idOpenPay();
        return view('pagos/pagarpse')->with([
            'source_id' => 'demo'
        ]);
    }

    public function getPayEfectivo()
    {
        $roles = \App\Roles::OrderBy('rol')->get();
        //$source_id=\App\Pagos::idOpenPay();
        return view('pagos/pagarefectivo');
    }

    public function pdfpago()
    {
        return view('pagos/pdfpago');
    }

    public function payEfectivo(Request $request)
    {
        $pagos = new Pagos;
        $idPago = Pagos::select('orders')->max('id');
        $orderId = $this->identifiacador . '-' . ($idPago + 1);

        date_default_timezone_set('America/Bogota');
        $due_date = date('Y-m-d\T24:00:00');

        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $email = $request->email;
        $telefono = $request->telefono;
        $monto = $request->monto;
        $concepto = $request->concepto;
        $city = "Manizales";
        $country_code = "CO";
        $postal_code = "170001";
        $line1 = "Conocida";
        $state = "Medallin";
        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/customers";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-type: application/json",
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // "external_id" : "' . $orderId . '",

        $data = '{
           "name": "' . $nombre . '",
           "last_name": "' . $apellido . '",
           "email": "' . $email . '",
           "phone_number" : "' . $telefono . '",
           "customer_address" : {
                "department" : "' . $state . '",
                "city" : "' . $city . '",
                "additional" : "' . $line1 . '"
            }
        }';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($resp, true);
        $CUSTOMER_ID = $dato["id"];

        $pago = Pagos::create([
            "usuarioid" => Auth::id(),
            "nombre" => $nombre,
            "apellido" => $apellido,
            "email" => $email,
            "telefono" => $telefono,
            "concepto" => $concepto,
            "tipopago" => 'EFECTIVO',
            "monto" => $monto,
            "status" => 'Pendiente',
        ]);
        $idPago = $pago->id;

        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/customers/" . $CUSTOMER_ID . "/charges";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-type: application/json",
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // "order_id" : "' . $orderId . '",
        $data = '{
            "method" : "store",
            "amount" : ' . $monto . ',
            "currency" : "COP",
            "iva" : "0",
            "description" : "' . $concepto . '",
            "due_date" : "' . $due_date . '"
        }';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($resp, true);
        //var_dump($dato);
        $idCliente = $dato["id"];

        $user = Pagos::find($idPago);
        $user->idtransaccion = $idCliente;
        $user->respuesta = $resp;
        $user->save();

        return redirect()->action('PagosController@download', $dato);
    }

    public function download(Request $request)
    {
        //$pdf = \PDF::loadView('pagos/pdfpago', $data);
        //return $pdf->download('archivo.pdf');
        return PDF::loadView('pagos/pdfpago', $request)->stream('archivo.pdf');
    }


    public function pay(Request $request)
    {
        $request->validate([
            'tarjeta' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $valido = $this->ValidCreditcard($value);
                    if ($valido != TRUE) {
                        $fail('La tarjeta debe contener 16 digitos');
                    }
                },
                'max:20',
                'min:16'
            ],
            'mes' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ((strtotime($value . $request['year'])) <= (strtotime(date('my')))) {
                        $fail('La fecha de tiene que ser mayor a la actual');
                    }
                }
            ]
        ]);
        $pagos = new Pagos;
        $resultado = $this->tokenOpenPay($request);
        $idPago = $resultado[1];
        $orderId = $this->identifiacador . '-' . ($idPago);
        $source_id = $resultado[0];

        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $holder_name = $nombre . ' ' . $apellido;
        $card_number = $request->tarjeta;
        $expiration_year = $request->year;
        $expiration_month = $request->mes;
        $cvv = $request->cvv;
        $city = "Manizales";
        $country_code = "CO";
        $postal_code = "170001";
        $line1 = "Conocida";
        $state = "Medallin";
        $device_session_id = $request->device_session_id;
        $monto = $request->monto;
        $concepto = $request->concepto;
        $telefono = $request->telefono;
        $email = $request->email;


        $pagos->usuarioid = $request->usuarioid;
        $pagos->nombre = $holder_name;
        $pagos->tarjeta = $card_number;
        $pagos->mes = $expiration_month;
        $pagos->year = $expiration_year;
        $pagos->concepto = $concepto;
        $pagos->cvv = $cvv;
        $pagos->monto = $monto;

        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/charges";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-type: application/json",
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '
        {
           "source_id" : "' . $source_id . '",
           "method" : "card",
           "amount" : "' . $monto . '",
           "currency" : "COP",
           "iva" : "0",
           "description" : "' . $concepto . '",
           "order_id" : "' . $orderId . '",
           "device_session_id" : "' . $device_session_id . '",
               "customer" : {
                    "name" : "' . $nombre . '",
                    "last_name" : "' . $apellido . '",
                    "phone_number" : "' . $telefono . '",
                    "email" : "' . $email . '"
               }
            }';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($resp, true);
        $status = $dato["status"];

        $create_dt = date("Y-m-d H:i:s");
        $user = Pagos::find($idPago);
        $user->nombre = $nombre;
        $user->idtransaccion = $source_id;
        $user->apellido = $apellido;
        $user->email = $email;
        $user->telefono = $telefono;
        $user->concepto = $concepto;
        $user->monto = $monto;
        $user->tarjeta = $card_number;
        $user->mes = $expiration_month;
        $user->year = $expiration_year;
        $user->cvv = $cvv;
        $user->status = $status;
        $user->respuesta = $resp;
        $user->updated_at = $create_dt;
        $user->save();

        return redirect()->action('PagosController@index', [
            'message' => array(
                'tipo' => 'warning',
                'titulo' => 'No se actualizó cliente.',
                'mensaje' => 'kk',
            )
        ]);
    }

    public function payPSE(Request $request)
    {

        //$pagos = new Pagos;

        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $city = "Manizales";
        $country_code = "CO";
        $postal_code = "170001";
        $line1 = "Conocida";
        $state = "Medallin";
        $device_session_id = $request->device_session_id;
        $monto = $request->monto;
        $concepto = $request->concepto;
        $telefono = $request->telefono;
        $email = $request->email;
        $create_dt = date("Y-m-d H:i:s");
        $user = Pagos::create([
            "usuarioid" => Auth::id(),
            "nombre" => $nombre,
            "apellido" => $apellido,
            "email" => $email,
            "telefono" => $telefono,
            "concepto" => $concepto,
            "tipopago" => 'PSE',
            "monto" => $monto,
            "status" => 'Pendiente',
            'created_at' => $create_dt,
        ]);
        $idPago = $user->id;
        $orderId = $this->identifiacador . '-' . ($idPago);


        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/charges";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-type: application/json",
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = '
        {
           "method" : "bank_account",
           "amount" : "' . $monto . '",
           "currency" : "COP",
           "description" : "' . $concepto . '",
           "order_id" : "' . $orderId . '",
           "iva" : "0",
           "redirect_url":"http://ami-project.test/pagos/payPSE",
           "customer" : {
                "name" : "' . $nombre . '",
                "last_name" : "' . $apellido . '",
                "email" : "' . $email . '",
                "phone_number" : "' . $telefono . '",
                "requires_account" : false,
                "customer_address" : {
                    "department" : "' . $state . '",
                    "city" : "' . $city . '",
                    "additional" : "' . $line1 . '"
                }
           }
        }';

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $datos = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($datos, true);
        //var_dump($dato);
        $url = $dato["payment_method"]["url"];
        $source_id = $dato["id"];

        $user = Pagos::find($idPago);
        $user->idtransaccion = $source_id;
        $user->save();

        return $url;
    }

    public function tokenOpenPay($request)
    {
        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $holder_name = $nombre . ' ' . $apellido;
        $card_number = $request->tarjeta;
        $expiration_year = $request->year;
        $expiration_month = $request->mes;
        $cvv = $request->cvv;
        $city = "Manizales";
        $country_code = "CO";
        $postal_code = "170001";
        $line1 = "Conocida";
        $state = "Medallin";
        $device_session_id = $request->device_session_id;
        $monto = $request->monto;
        $concepto = $request->concepto;
        $telefono = $request->telefono;
        $email = $request->email;

        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/tokens";
        $headers = array(
            "Content-type: application/json",
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        $data = '
        {
           "card_number":"' . $card_number . '",
           "holder_name":"' . $holder_name . '",
           "expiration_year":"' . $expiration_year . '",
           "expiration_month":"' . $expiration_month . '",
           "cvv2":' . $cvv . ',
           "address":{
                "city":"' . $city . '",
                "country_code":"' . $country_code . '",
                "postal_code":"' . $postal_code . '",
                "line1":"' . $line1 . '",
                "state":"' . $state . '"
           }
        }';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $datos = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($datos, true);

        if (isset($dato["error_code"])) {
            $response = $dato["id"];
            $create_dt = date("Y-m-d H:i:s");
            $user = Pagos::create([
                "usuarioid" => Auth::id(),
                "nombre" => $nombre,
                "apellido" => $apellido,
                "email" => $email,
                "telefono" => $telefono,
                "tipopago" => 'Tarjeta',
                "concepto" => $concepto,
                "monto" => $monto,
                "tarjeta" => $card_number,
                "mes" => $expiration_month,
                "year" => $expiration_year,
                "cvv" => $cvv,
                "status" => 'Error',
                'created_at' => $create_dt,
            ]);
            $idPago = $user->id;
            return redirect()->action('PagosController@index', [
                'message' => array(
                    'tipo' => 'warning',
                    'titulo' => 'No se puedo completar el pago',
                    'mensaje' => $dato["description"],
                )
            ]);
        } else {
            $response = $dato["id"];
            $create_dt = date("Y-m-d H:i:s");
            $user = Pagos::create([
                "usuarioid" => Auth::id(),
                "nombre" => $nombre,
                "apellido" => $apellido,
                "email" => $email,
                "telefono" => $telefono,
                "tipopago" => 'Tarjeta',
                "concepto" => $concepto,
                "monto" => $monto,
                "tarjeta" => $card_number,
                "mes" => $expiration_month,
                "year" => $expiration_year,
                "cvv" => $cvv,
                "status" => 'Pendiente',
                'created_at' => $create_dt,
            ]);
            $idPago = $user->id;
            return array($response, $idPago);
        }
    }

    public function consultaOpenPay()
    {
        $url = "https://sandbox-api.openpay.co/v1/" . $this->idOpenpay . "/charges";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Basic " . base64_encode($this->keyOpenpay),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $dato = json_decode($resp, true);
        $device_session_id = $dato[0]["id"];
        return $device_session_id;
    }

    /**
     * Show view edit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $cliente = Clientes::find($id);
        $registros = $cliente->registrosfinancieros->pluck('periodo')->unique();

        foreach ($cliente->registrosfinancieros as $key => $registro) {
            $ingresos_cliente[$registro->id] = ingresos_por_registro($registro->id);
            $egresos_cliente[$registro->id] = descuentos_por_registro($registro->id);
        }

        return view("clientes/editarcliente")->with([
            "cliente" => $cliente,
            "registros" => $registros
        ]);
    }

    /**
     * Edit a client on BD.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        $cargo_docente = '';
        $cargo_administrativo = '';
        $conceptos_financieros = array();

        if ($request->docente_administrativo == 'd') {
            $cargo_docente = $request->cargo;
        } elseif ($request->docente_administrativo == 'a') {
            $cargo_administrativo = $request->cargo;
        }

        //DESCUENTOS NO APLICADOS
        if (isset($request->desc_na_valor_fijo[0])) {
            for ($i = 1; $i <= sizeof($request->desc_na_valor_fijo[0]); $i++) {
                $conceptos_financieros['desc_no_aplicados'][] = array(
                    'periodo'           => $request->desc_na_periodo[0][$i],
                    'pagaduria'         => $request->desc_na_pagaduria[0][$i],
                    'cod_concepto'      => $request->desc_na_cod_concepto[0][$i],
                    'concepto'          => $request->desc_na_concepto[0][$i],
                    'inconsistencia'    => $request->desc_na_inconsistencia[0][$i],
                    'valor'             => $request->desc_na_valor_fijo[0][$i],
                    'valor_total'       => $request->desc_na_valor_total[0][$i],
                    'saldo'             => $request->desc_na_saldo[0][$i]
                );
            }
        }

        $personas_upload[] = array(
            'nombres' => (isset($request->nombres) ? $request->nombres : ''),
            'apellidos' => (isset($request->apellidos) ? $request->apellidos : ''),
            'documento' => (isset($request->documento) ? $request->documento : ''),
            'cargo_docente' => ($cargo_docente !== '' ? $cargo_docente : ''),
            'cargo_administrativo' => ($cargo_administrativo !== '' ? $cargo_administrativo : ''),
            'ciudad' => (isset($request->ciudad) ? $request->ciudad : ''),
            'centro_costos' => (isset($request->centro_costos) ? $request->centro_costos : ''),
            'grado' => (isset($request->grado) ? utf8_encode($request->grado . "") : ''),
            'tipo_contratacion' => (isset($request->tipo_contratacion) ? $request->tipo_contratacion : ''),
            'periodo' => (isset($request->periodo[0]) ? $request->periodo[0] : ''),
            'secretaria' => (isset($request->pagaduria[0]) ? $request->pagaduria[0] : ''),
            'estado_civil' => (isset($request->estado_civil) ? $request->estado_civil : ''),
            'sexo' => (isset($request->genero) ? $request->genero : ''),
            'fechanto' => (isset($request->fecha_nto) ? $request->fecha_nto : ''),
            'direccion' => (isset($request->direccion) ? $request->direccion : ''),
            'telefono' => (isset($request->telefono) ? $request->telefono : ''),
            'celular' => (isset($request->celular) ? $request->celular : ''),
            'correo' => (isset($request->correo) ? $request->correo : ''),
            'vinculacion' => (isset($request->vinculacion) ? $request->vinculacion : ''),
            'banco' => '',
            'cuenta' => '',
            'pension' => '',
            'caja_compensacion' => '',
            'cesantias' => '',
            'dias_laborados' => '',
            'nit' => '',
            'ingresos_base' => $request->ingresos,
            'conceptos_financieros' => array(
                'detallado_conceptos' => $conceptos_financieros
            )
        );

        $resp = upload_personas_without_concepts($personas_upload);

        if (strpos($resp, 'Error.') === false) {
            return redirect()->action('EstudiosController@iniciar', [
                'documento' => $request->documento,
                'message' => array(
                    'tipo' => 'success',
                    'titulo' => 'Cliente actualizado con éxito',
                    'mensaje' => 'Puedes iniciar un estudio con este cliente.',
                )
            ]);
        } else {
            return redirect()->action('EstudiosController@iniciar', [
                'documento' => $request->documento,
                'message' => array(
                    'tipo' => 'warning',
                    'titulo' => 'No se actualizó cliente.',
                    'mensaje' => $resp,
                )
            ]);
        }
    }

    protected function luhn($number)
    {
        $number = (string)$number;
        if (!ctype_digit($number)) {
            return FALSE;
        }
        $length = strlen($number);
        $checksum = 0;
        for ($i = $length - 1; $i >= 0; $i -= 2) {
            $checksum += substr($number, $i, 1);
        }
        for ($i = $length - 2; $i >= 0; $i -= 2) {
            $double = substr($number, $i, 1) * 2;
            $checksum += ($double >= 10) ? ($double - 9) : $double;
        }
        return ($checksum % 10 == 0) ? TRUE : FALSE;
    }

    protected function ValidCreditcard($number)
    {
        $card_array = array(
            'default' => array(
                'length' => '13,14,15,16,17,18,19',
                'prefix' => '',
                'luhn' => TRUE,
            ),
            'american express' => array(
                'length' => '15',
                'prefix' => '3[47]',
                'luhn' => TRUE,
            ),
            'diners club' => array(
                'length' => '14,16',
                'prefix' => '36|55|30[0-5]',
                'luhn' => TRUE,
            ),
            'discover' => array(
                'length' => '16',
                'prefix' => '6(?:5|011)',
                'luhn' => TRUE,
            ),
            'jcb' => array(
                'length' => '15,16',
                'prefix' => '3|1800|2131',
                'luhn' => TRUE,
            ),
            'maestro' => array(
                'length' => '16,18',
                'prefix' => '50(?:20|38)|6(?:304|759)',
                'luhn' => TRUE,
            ),
            'mastercard' => array(
                'length' => '16',
                'prefix' => '5[1-5]',
                'luhn' => TRUE,
            ),
            'visa' => array(
                'length' => '13,16',
                'prefix' => '4',
                'luhn' => TRUE,
            ),
        );

        // Remove all non-digit characters from the number
        if (($number = preg_replace('/\D+/', '', $number)) === '')
            return FALSE;

        // Use the default type
        $type = 'default';

        $cards = $card_array;

        // Check card type
        $type = strtolower($type);

        if (!isset($cards[$type]))
            return FALSE;

        // Check card number length
        $length = strlen($number);

        // Validate the card length by the card type
        if (!in_array($length, preg_split('/\D+/', $cards[$type]['length'])))
            return FALSE;

        // Check card number prefix
        if (!preg_match('/^' . $cards[$type]['prefix'] . '/', $number))
            return FALSE;

        // No Luhn check required
        if ($cards[$type]['luhn'] == FALSE)
            return TRUE;

        return $this->luhn($number);
    }
}
