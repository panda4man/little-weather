<?php

namespace App\Http\Controllers\Api;

use App\Transformers\DarkSky\BaseTransformer;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DarkSkyController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function weather()
    {
        $lat = request()->get('lat');
        $lon = request()->get('lon');

        if(empty($lat) || empty($lon)) {
            $lat = '37.8651';
            $lon = '119.5383';
        }

        try {
            $res = json_decode(json_encode(\DarkSky::location($lat, $lon)->excludes(['minutely', 'flags'])->get()), true);
            $res = fractal()->item($res, new BaseTransformer())->toArray();
        } catch (BadResponseException $e) {
            \Log::error($e->getMessage());
            $res = null;
        }

        if(is_null($res)) {
            return response()->json(['error' => 'An error occurred with the DarkSky API'], 400);
        }

        dd($res);

        return response()->json($res);
    }
}
