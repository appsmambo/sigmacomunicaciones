<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Configuracion;
use App\GpsData;

class ApiController extends Controller
{
    public function postLogin()
    {
        $config = Configuracion::find(1); // model from Database
/*
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://gps.pocradio.com:8100/gps/client/reg",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\"id\":\"Sigma\",\"pwd\":\"Sigma2020\",\"url\":\"https://sigmacomunicaciones.net/services/api/gps_data\"}",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        ///////////////////////////////////////////////////////////
        */
        $client = new Client(['base_uri' => 'https://gps.pocradio.com:8100/']);
        $response = $client->request(
            'POST',
            '/gps/client/reg',
            [
                'form_params' => [
                    'id' => 'Sigma', // $config->usr,
                    'pwd' => 'Sigma2019', // $config->pwd,
                    'url' => $config->url
                ],
                'verify' => false
            ]
        );
        echo $response->getBody();
    }
    public function postGpsData(Request $request)
    {
        // write log
        $archivo = 'request-'.date('Ymd').'.txt';
        $req_dump = print_r($request->all(), true);
        Storage::append($archivo, $req_dump);
        // write gps_data
        $gpsData = new GpsData;
        $data = $request->all();
        $gpsData->transfer_id = '1';
        $gpsData->device_id = $data['id'];
        $gpsData->name = $data['name'];
        $gpsData->lt = $data['lt'];
        $gpsData->lg = $data['lg'];
        $gpsData->lbs_type = $data['LBS'];
        $gpsData->altitude = $data['altitude'];
        $gpsData->speed = $data['speed'];
        $gpsData->direction = $data['direction'];
        $gpsData->time = $data['time'];
        $gpsData->sos_flag = $data['SOS'];
        $gpsData->save();
        echo 'ok';
    }
}
