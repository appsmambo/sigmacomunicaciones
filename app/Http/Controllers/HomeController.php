<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Configuracion;
use App\GpsData;
use App\Exports\GpsDataExport;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex()
    {
        $gpsData = GpsData::select('created_at', 'device_id', 'name')
            ->orderBy('created_at', 'DESC')
            ->take(10)
            ->get();
        return view('home')->with('gpsData', $gpsData->toArray());
    }

    public function getConfiguracion()
    {
        $config = Configuracion::find(1);
        return view('configuracion')->with('configuracion', $config);
    }

    public function postGrabarConfiguracion(Request $request)
    {
        $id = $request->input('id', 1);
        $login_interface = $request->input('login_interface');
        $usr = $request->input('usr');
        $pwd = $request->input('pwd');
        $url = $request->input('url');
        $configuracion = Configuracion::find($id);
        $configuracion->login_interface = $login_interface;
        $configuracion->usr = $usr;
        $configuracion->pwd = $pwd;
        $configuracion->url = $url;
        $configuracion->save();
        return redirect()->route('index');
    }

    public function getConsulta(Request $request)
    {
        $deviceID = '';
        // retirar - y substraer parte del año
        // 20 08 07 12 00 36
        // y  M  d  H  i  s
        $desde = substr(str_replace('-', '', $request->input('desde')), 2) . '000000';
        $hasta = substr(str_replace('-', '', $request->input('hasta')), 2) . '235959';

        $gpsData = GpsData::select('device_id', 'name', 'lt', 'lg', 'lbs_type', 'altitude', 'speed', 'direction', 'time', 'sos_flag');

        if ($request->has('deviceID') && !empty($request->input('deviceID'))) {
            $deviceID = $request->input('deviceID');
            $gpsData->where('device_id', 'like', '%' . $deviceID . '%');
        }

        if ($request->has(['desde', 'hasta']) && !empty($request->input('desde')) && !empty($request->input('hasta'))) {
            $gpsData->whereBetween('time', [$desde, $hasta]);
        } else if ($request->hasAny(['desde', 'hasta']) && (!empty($request->input('desde')) || !empty($request->input('hasta')))) {
            if ($request->has('desde') && !empty($request->input('desde'))) {
                $gpsData->where('time', '>=', $desde);
            }
            if ($request->has('hasta') && !empty($request->input('hasta'))) {
                $gpsData->where('time', '<=', $hasta);
            }
        }

        $gpsData = $gpsData
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('consulta')
            ->with('desde', $request->input('desde', ''))
            ->with('hasta', $request->input('hasta', ''))
            ->with('deviceID', $deviceID)
            ->with('gpsData', $gpsData);
    }

    public function getExportar(Request $request)
    {
        $deviceID = '';
        $desde = '';
        $hasta = '';
        if ($request->has('deviceID') && !empty($request->input('deviceID'))) {
            $deviceID = $request->input('deviceID');
        }
        if ($request->has('desde') && !empty($request->input('desde'))) {
            $desde = substr(str_replace('-', '', $request->input('desde')), 2) . '000000';
        }
        if ($request->has('hasta') && !empty($request->input('hasta'))) {
            $hasta = substr(str_replace('-', '', $request->input('hasta')), 2) . '235959';
        }
        return Excel::download(new GpsDataExport($deviceID, $desde, $hasta), 'GpsData.xlsx');
    }

}
