<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WhatsAppBotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('whatsapp-bot');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(env('WS_BOT_USER') . ':' . env('WS_BOT_PASSWORD')),
            ],
        ]);

        $body = '{
            "metodo": "SmsEnvio",
            "id_cbm": "1107",
            "id_transaccion": "123456789",
            "telefono": "57' . $request->telefono . '",
            "id_mensaje": "30223",
            "dt_variable": "1",
            "datos": {
                "valor": [
                    "' . $request->nombre . '",
                    "' . $request->oferta . '"
                ]
            }
        }';

        $response = $client->post('https://online.anyway.com.ec/sendwpush.php', [
            'body' => $body,
        ]);

        $body = $response->getBody();

        return $body;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
