<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Google\Cloud\AutoMl\V1\ExamplePayload;
use Google\Cloud\AutoMl\V1\PredictionServiceClient;
use Google\Cloud\AutoMl\V1\TextSnippet;

class PlanosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$planos = \App\Planos::orderBy('created_at', 'desc')->get();
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
		return view('planos/index')->with([
			'planos' => $planos, 
			'pagadurias' => $pagadurias
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
        return view('planos/crear')->with(['pagadurias' => $pagadurias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		ini_set('memory_limit', '-1');
		$response = array();

		if($request->file('basicos') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'BAS';
			$plano->save();
		}

		if($request->file('aplicados') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'APL';
			$plano->save();
		}

		if($request->file('no_aplicados') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'NAP';
			$plano->save();
		}

		if($request->file('embargos') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'EMB';
			$plano->save();
		}

		if($request->file('comppago'))
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'COM';
			$plano->save();
		}

		if($request->file('mens_liquidacion'))
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'MLQ';
			$plano->save();
		}

		if($request->file('concep_liquid'))
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'CLQ';
			$plano->save();
		}

        $pagaduria = \App\Pagadurias::find($request->input("pagaduria"));
		if($pagaduria->codigo == "SEM_POPAYAN" || $pagaduria->codigo == "SED_VALLE" || $pagaduria->codigo == "SED_CAUCA" || $pagaduria->codigo == "SEM_JAMUNDI")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Secretarias::base($request, $plano);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'EMB':
					$response = \App\Http\Resources\Secretarias::embargos($request, $plano);
					break;
				case 'COM':
					$response = \App\Http\Resources\Secretarias::comprobante_pago($request, $plano);
					break;
				case 'MLQ':
					$response = \App\Http\Resources\Secretarias::mensajes_liquidacion($request, $plano);
					break;
				case 'CLQ':
					$response = \App\Http\Resources\Secretarias::conceptos_liquidacion($request);
					break;
			}
		} 
		elseif ($pagaduria->codigo == "FOPEP")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Fopep::base($request, $plano);
					break;
				case 'APL':
					$response = \App\Http\Resources\Fopep::descuentos_aplicados($request, $plano);
					break;
				case 'NAP':
					$response = \App\Http\Resources\Fopep::descuentos_no_aplicados($request, $plano);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'MLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'CLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
			}
		}
		elseif ($pagaduria->codigo == "FIDUPREVISORA")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Fiduprevisora::base($request, $plano);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'MLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'CLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
			}
		}
		elseif ($pagaduria->codigo == "COLPENSIONES")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Colpensiones::base($request);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'COM':
					$response = \App\Http\Resources\Colpensiones::base($request);
					break;
				case 'MLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'CLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
			}
		}

        return view('planos/response')->with(['response' => $response]);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_gcp()
    {
		/*$service_client = '../credentials.json';

		$key = trim(shell_exec('sudo -u ' . $service_client . ' gcloud auth application-default print-access-token 2<&1')); // replace service_name with your service account name
		// $key = trim($service_client); // replace service_name with your service account name
		$post='{"payload": {"document": {"input_config": {"gcs_source": {"input_uris": "gs://ami_nlp/prueba_ami/escaneado6_07-07-20_18-01-03.pdf"}}}}}';

		$curl = curl_init();
		// The REST API URL can be found from the predict tab example replace with your account URL https://automl.googleapis.com/v1beta1/projects/quizappflutter/locations/us-central1/models/ICNXXXXXXXXXX41:predict
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://automl.googleapis.com/v1/projects/55927814408/locations/us-central1/models/TCN6090768851320963072:predict",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30, 
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $post,
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer $key",
				"Content-Type: application/json",
			), 
		));

		$response = curl_exec($curl);
		$err = curl_error($curl); 
		
		curl_close($curl);
		
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}*/



		/*$url = 'https://automl.googleapis.com/v1/projects/55927814408/locations/us-central1/models/TCN6090768851320963072:predict';
		$datos = array(
			"payload" => array(
				"document" => array(
					"input_config" => array(
						"gcs_source" => array(
							"input_uris" => "gs://ami_nlp/prueba_ami/escaneado6_07-07-20_18-01-03.pdf"
						)
					)
				)
			)
		);
		$data = json_encode($datos);

		$ch = curl_init( $url );
		# Setup request to send json via POST.
		$payload = json_encode( array( "customer"=> $data ) );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json', 
			'Content-Length: ' . strlen($data),
			// 'Authorization: Bearer -----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC+vz57yDPm6LXN\nJIeEt+jvcm5o0bZHnkoHL0acyZKrcyzudz/6wy9GSKOgRH0u1dzlsAgMw39PO8GM\n10/04Jzu8hp++w0v1iHXLIuy7YUCdqHEHIf/un4mL7KZ9zRrcWX39BvzKbAfRkl8\nvUN6U08/5O6W/ttb8qqzAUtKD2EjwVY+jyz/n8OUqQXn9I0J0tocPTwPq5a34x+0\nQbecMnlCrD1pDJJGGjEQdD6gmEelLozUvoCxNCMQ0ccdmCEVCyoKjqnMtAJWARjf\n3TCgbpD+bF+9/M5QmmDLBCyx1gne9LzwGzRalJB+KPHwR5sIaUqu4UtazrC5Wvdx\nROAzmrdJAgMBAAECggEAAkEzH+DHRvIhPqzb3Tm/Sx/zJidfHDbxcRGh/WB0l1+O\nfGh0cnu0J1ncdUcvTkpriLDX/NlNZpuyN1P5jKTLC21ZMCm6Mi05y9f+9BJ9Vqty\n+2UgRmmR7CmhgX275OPmQf66BdnMTH0BiV6Yru16gQAtFyPUEUuBBl9Riy07XLd7\nFbFs22KVfg/Irb2MR+kXrlk9LBF0DZVRWrM29ghjhwHTtFdsa5SQAZ5eHIYE9ck/\nzNFrCuvq8JxYl9ba9C5IXS0gugScWEozJ8xYU4yWgoCjYoN9z2IY29hhAmzVQiEP\npEKmKLRDek+n0rFW9HLG54eksqXcNKLon4Zjyx9VMQKBgQDxII/gbw9AAixVKePb\nnNYq65RG+/o4YFrd4KNN2rHbPhAijWMwWL8JrYFmkzHiiEYmmbofvhuZrZnRAp/1\nYqftoc1CN5/YRnSLZDmFhOJeSH0ZE6HuxoywRAbBIpWM4BsQsFcLW241+FrmA+eA\nPzcfNlAqxr6mzS9Rsmv5d4PadwKBgQDKgyvXF5QwhbpaUWCokm0tZFldEmJ1IcOm\nuP5cIAKGbJbNeUBDaboGD1PelmOzm4RLjQKpiiGdez9+otzPfDkydCjDEqduf/KE\n5DszS0XmV+xzAfgRQrQp7+OU7rK2nngmvsn4TThb3/pI1YUDSGdCD70dLW8mnnk4\njICP36CsPwKBgQCu136cLcuwDSNaSXq4hrvg+VtWMWYZtPyOgFHJpTdsE0+dzknL\nB77WZKI836S/bzL20GdelvnqcC/ll3KnevbrX3S3fCACsevWG0F+aIG//e3/3fWA\nbyYatejz6IDqWqIlcshbKtv6dHBs8w3NN4lfr4Fn0x7xUjzqj2atyJSqNwKBgEr7\nqp3nwxz8RvXuL8X7AaXPBC+sAPyx1cnDsZrW11iCmIvYG21almBsCHfgY2Y7bQ60\nVYoE9VFkMyxmjS/eJSeDTxx7qbcGdPuzrh/d/TG+2HP1BK8PbbohcrjQcSehIYfn\nGM1xei66jeet96QxiNozDajiC8fW4beIfonHGaztAoGBAIPJ10Da7Am3q8Z3ZZF6\nceWPVmfHuBNQ6kX0EPr+v8ScoxsjOYoqKaiXRM453rbwugNCDAclfBRBdn11O4GU\ndSyJVv94/WDAjZ6ayVHtKRx7sZ6Rv19xxNyiChKXuEfmhNGFf0YCMMf453AnTX/5\n8qeyUgczOPLPOndMDklnTY+B\n-----END PRIVATE KEY-----\n'
			'Authorization: Bearer ya29.c.Ko8B0gc5KH-RfPntG5DlYQLUqju8pxN5hSaLPnm3lYk07YjNTari0JIKwNewPye-1Z_z1pWMoxlFfjOmjtaxOnpgTl2KdJ_49rLqhnOvbRjk9Nw5GMo6yc0SOuAP-f9O4rzrCxiCM38xD0gmrju0i4j8_5UudSIJQFOW6fy12blGgpVinhzLd0YLhc-JpTc1t7U'
		));
		# Return response instead of printing.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result = curl_exec($ch);
		curl_close($ch);*/



		/** Uncomment and populate these variables in your code */
		$score = 0;
		$nombre_clase = '';
		$projectId = 'warm-helix-277015';
		$location = 'us-central1';
		$modelId = 'TCN6090768851320963072';
		$content = 'gs://ami_laravel/desprendible_colpensiones_3.pdf';
		
		putenv('GOOGLE_APPLICATION_CREDENTIALS=../credentials.json');

		

		/*$client = new PredictionServiceClient();
		try {
			// get full path of model
			$formattedName = $client->modelName(
				$projectId,
				$location,
				$modelId
			);
			// create payload
			$textSnippet = (new TextSnippet())
				->setContent($content)
				->setMimeType('text/plain'); // Types: 'text/plain', 'text/html'
			$payload = (new ExamplePayload())
				->setTextSnippet($textSnippet);
			$params = ['score_threshold' => '0.5']; // value between 0.0 and 1.0
			// predict with above model and payload
			$response = $client->predict($formattedName, $payload, $params);
			$annotations = $response->getPayload();
			// display results

			// echo "<pre>$annotations[0]</pre>";
			foreach ($annotations as $key => $annotation) {
				// if ($key == 0) {
					$classification = $annotation->getClassification();

					echo '<pre>';
					echo '</pre>';

					printf('Predicted class name: %s' . PHP_EOL, $annotation->getDisplayName());
					printf('Predicted class score: %s' . PHP_EOL, $classification->getScore());

					// if ($classification->getScore() > $score) {
					// 	$nombre_clase = $annotation->getDisplayName();
					// 	$score = $classification->getScore();
					// }
				// }
			}
		} finally {
			// printf('Predicted class name: %s' . PHP_EOL, $nombre_clase);
			// printf('Predicted class score: %s' . PHP_EOL, $score);
			$client->close();
		}*/

        // return view('planos/crear-gcp');
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store_gcp(Request $request)
	{
		ini_set('memory_limit', '-1');
		$response = array();

		$archivo = $request->file('archivo');
		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();

		$disk = \Storage::disk('gcs');

		$respond = $disk->put(date('m') . '_' . date('Y') . '/' . $nombre_original, \File::get($archivo));
		
		if ($respond == '1') {
			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha cargado correctamente.',
			);
		} else {
			$response = array(
				'cod' => '300',
				'mensaje' => $respond,
			);
		}
		
        return view('planos/response')->with(['response' => $response]);
	}

}
