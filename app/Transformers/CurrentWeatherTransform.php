<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CurrentWeatherTransform extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param array $payload
     * @return array
     */
    public function transform($payload = [])
    {
        $data = [
            'cloud_cover'      => $payload['cloudCover'],
            'humidity'         => $payload['humidity'],
            'icon'             => $payload['icon'],
            'precipitation'    => [
                'intensity'   => $payload['precipIntensity'],
                'probability' => $payload['precipProbability'],
                'type'        => isset($payload['precipType']) ? $payload['precipType'] : null,
            ],
            'summary'          => $payload['summary'],
            'temperature'      => $payload['temperature'],
            'temperature_feel' => $payload['apparentTemperature'],
            'time'             => $payload['time'],
            'wind'             => [
                'speed'   => $payload['windSpeed'],
                'gust'    => $payload['windGust'],
                'bearing' => $payload['windBearing'],
            ],
        ];

        return $data;
    }
}
