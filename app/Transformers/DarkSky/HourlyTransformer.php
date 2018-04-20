<?php

namespace App\Transformers\DarkSky;

use League\Fractal\TransformerAbstract;

class HourlyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param array $payload
     * @return array
     */
    public function transform($payload = [])
    {
        return [
            'cloud_cover'   => $payload['cloudCover'],
            'humidity'      => $payload['humidity'],
            'icon'          => $payload['icon'],
            'precipitation' => [
                'intensity'   => $payload['precipIntensity'],
                'probability' => $payload['precipProbability'],
                'type'        => isset($payload['precipType']) ? $payload['precipType'] : null,
            ],
            'summary'       => $payload['summary'],
            'time'          => $payload['time'],
            'wind'          => [
                'speed'   => $payload['windSpeed'],
                'gust'    => $payload['windGust'],
                'bearing' => $payload['windBearing'],
            ],
        ];
    }
}
