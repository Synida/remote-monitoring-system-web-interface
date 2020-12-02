<?php

namespace App\Http\Measurable;

/**
 * Class CpuTemperature
 * @package App\Http\Measurable
 */
class CpuTemperature
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
        $line = shell_exec('sensors | grep CPU');
        $secondPart = explode('+', $line);
        return explode('°', $secondPart)[0];
    }
}
