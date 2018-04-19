<?php

namespace App\Http\Controllers\Api;

use App\Transformers\CurrentWeatherTransform;
use App\Transformers\DailyWeatherTransform;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrent()
    {
        $lat = request()->get('lat');
        $lon = request()->get('lon');

        if(empty($lat) || empty($lon)) {
            $lat = '37.8651';
            $lon = '119.5383';
        }

        try {
            $res = json_decode(json_encode(\DarkSky::location($lat, $lon)->currently()), true);
            $res = fractal()->item($res, new CurrentWeatherTransform())->toArray();
        } catch (BadResponseException $e) {
            \Log::error($e->getMessage());
            $res = null;
        }

        if(is_null($res)) {
            return response()->json(['error' => 'An error occurred with the DarkSky API'], 400);
        }

        return response()->json($res);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToday()
    {
        $lat = request()->get('lat');
        $lon = request()->get('lon');

        if(empty($lat) || empty($lon)) {
            $lat = '37.8651';
            $lon = '119.5383';
        }

        try {
            $res = json_decode(json_encode(\DarkSky::location($lat, $lon)->daily()), true)[0];
            $res = fractal()->item($res, new DailyWeatherTransform())->toArray();
        } catch (BadResponseException $e) {
            \Log::error($e->getMessage());
            $res = null;
        }

        if(is_null($res)) {
            return response()->json(['error' => 'An error occurred with the DarkSky API'], 400);
        }

        return response()->json($res);
    }
}
