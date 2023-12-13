<?php

namespace App\Services;

class WeatherService
{
    public function getTemperature()
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather?lat=43.3211301&lon=21.8959232&appid=60825efadeb08154a146559d1016ff34';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $tempK = $data['main']['temp'];
        return round($tempK - 273.15);
    }
}
