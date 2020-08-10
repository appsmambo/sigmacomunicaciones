<?php

namespace App\Exports;

use App\GpsData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GpsDataExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    public function __construct(string $deviceID = '', string $desde = '', string $hasta = '')
    {
        $this->deviceID = $deviceID;
        $this->desde = $desde;
        $this->hasta = $hasta;
    }
    public function headings(): array
    {
        return [
            'Device ID',
            'Device Name',
            'Latitude',
            'Longitude',
            'LBS Type',
            'Altitude',
            'Speed',
            'Direction',
            'Time',
            'SOS flag',
        ];
    }
    public function map($gpsData): array
    {
        return [
            $gpsData->device_id,
            $gpsData->name,
            $gpsData->lt,
            $gpsData->lg,
            $gpsData->lbs_type,
            $gpsData->altitude,
            $gpsData->speed,
            $gpsData->direction,
            $gpsData->time,
            $gpsData->sos_flag,
        ];
    }
    public function query()
    {
        return GpsData::query()
            ->where('device_id', 'like', '%' . $this->deviceID . '%')
            ->where('time', '>=', $this->desde)
            ->where('time', '<=', $this->hasta)
            ->orderBy('time', 'DESC');
    }
}
