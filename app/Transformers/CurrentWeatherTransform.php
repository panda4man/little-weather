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
            'time'              => $payload['time'],
            'summary'           => $payload['summary'],
            'icon'              => $payload['icon'],
            'temperature'       => $payload['temperature'],
            'temperature_feel' => $payload['apparentTemperature'],
        ];

        return $data;
    }
}
