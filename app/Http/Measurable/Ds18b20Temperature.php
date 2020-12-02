<?php


namespace App\Http\Measurable;

/**
 * Class Ds18b20Temperature
 * @package App\Http\Measurable
 */
class Ds18b20Temperature
{
    /**
     * Returns with the CPU temperature
     * Usage requires lm_sensors package
     *
     * @return string
     * @author Synida Pry
     */
    public function execute()
    {
        $baseDir = '/sys/bus/w1/devices/';
        $deviceFolder = glob($baseDir . '28*')[0];
        $deviceFile = $deviceFolder . '/w1_slave';

        $data = file($deviceFile, FILE_IGNORE_NEW_LINES);

        $temperature = null;
        if (preg_match('/YES$/', $data[0])) {
            if (preg_match('/t=(\d+)$/', $data[1], $matches, PREG_OFFSET_CAPTURE)) {
                $temperature = $matches[1][0] / 1000;
            }
        }

        return $temperature;
    }
}
