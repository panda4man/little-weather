<?php

namespace App\Transformers\DarkSky;

use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param null $response
     * @return array
     */
    public function transform($response = null)
    {
        $res = [];

        if(is_null($response)) {
            return $res;
        }

        $res['currently'] = [];
        $res['hourly'] = [];
        $res['daily'] = [];
        $res['timezone'] = $response['timezone'];

        if(isset($response['currently'])) {
            $res['currently'] = fractal()->item($response['currently'], new CurrentTransformer)->toArray();
        }

        if(isset($response['daily'])) {
            $res['daily'] = fractal()->collection($response['daily']['data'], new DailyTransformer)->toArray();
        }

        if(isset($response['hourly'])) {
            $res['hourly'] = fractal()->collection($response['hourly']['data'], new HourlyTransformer)->toArray();
        }

        return $res;
    }
}
