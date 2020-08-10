@extends('layouts.app')

@section('content')
<div class="container">
    <h4>
        Consulta GPS Data
    </h4>
    <form action="{{ route('consulta') }}" method="GET">
        @csrf
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-2 col-xl-3 form-group">
                <label for="deviceID">Device ID</label>
                <input type="text" class="form-control" id="deviceID" name="deviceID" maxlength="20" value="{{ $deviceID }}">
            </div>
            <div class="col-6 col-sm-4 col-lg-3 form-group">
                <label for="desde">Desde</label>
                <input type="datetime-local" class="form-control" id="desde" name="desde" value="{{ $desde }}">
            </div>
            <div class="col-6 col-sm-4 col-lg-3 form-group">
                <label for="hasta">Hasta</label>
                <input type="datetime-local" class="form-control" id="hasta" name="hasta" value="{{ $hasta }}" max="{{ now()->toDateString() }}T23:59">
            </div>
            <div class="col-12 col-lg-4  col-xl-3 form-group">
                <label class="d-none d-lg-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
                &nbsp;
                <a href="{{ route('exportar') }}?deviceID={{ $deviceID }}&desde={{ $desde }}&hasta={{ $hasta }}" class="btn btn-success">
                    Exportar
                </a>
                &nbsp;
                <button type="button" class="btn btn-secondary" onclick="customReset();">
                    Limpiar
                </button>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Device ID</th>
                    <th>Device Name</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>LBS Type</th>
                    <th>Altitude</th>
                    <th>Speed</th>
                    <th>Direction</th>
                    <th>Time</th>
                    <th>SOS flag</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gpsData as $row)
                @php
                    $fecha = str_split($row->time, 2);
                    $fecha = $fecha[2] . '/' . $fecha[1] . '/20' . $fecha[0] . ' ' . $fecha[3] . ':' . $fecha[4] . ':' . $fecha[5];
                @endphp
                <tr>
                    <td>{{ $row->device_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->lt }}</td>
                    <td>{{ $row->lg }}</td>
                    <td>{{ $row->lbs_type }}</td>
                    <td>{{ $row->altitude }}</td>
                    <td>{{ $row->speed }}</td>
                    <td>{{ $row->direction }}</td>
                    <td>{{ $fecha }}</td>
                    <td>{{ $row->sos_flag }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        No se encontraron registros.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="row my-3">
        <div class="col-12 col-md-2">
            @if ($gpsData->total() > 0)
            <h6 class="mt-2 mb-0">{{$gpsData->total()}} fila(s)</h6>
            @endif
        </div>
        <div class="col-12 col-md-10">
            {{ $gpsData->withQueryString()->links() }}
        </div>
    </div>

</div>
<script>
    function customReset() {
        document.getElementById("deviceID").value = "";
        document.getElementById("desde").value = "";
        document.getElementById("hasta").value = "";
    }
    function exportTableToExcel(tableID, filename = 'DataGps') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>
@endsection
