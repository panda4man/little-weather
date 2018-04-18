<?php

namespace App\Http\Controllers\Api;

use App\Transformers\CurrentWeatherTransform;
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
}
