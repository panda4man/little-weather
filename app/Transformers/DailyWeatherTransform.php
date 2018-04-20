<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DailyWeatherTransform extends TransformerAbstract
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
            'sun'           => [
                'rise' => $payload['sunriseTime'],
                'set'  => $payload['sunsetTime'],
            ],
            'temperature'   => [
                'high'      => $payload['temperatureHigh'],
                'low'       => $payload['temperatureLow'],
                'feel_low'  => $payload['apparentTemperatureLow'],
                'feel_high' => $payload['apparentTemperatureHigh'],
            ],
            'time'          => $payload['time'],
            'wind'          => [
                'speed'   => $payload['windSpeed'],
                'gust'    => $payload['windGust'],
                'bearing' => $payload['windBearing'],
            ],
        ];
    }
}
