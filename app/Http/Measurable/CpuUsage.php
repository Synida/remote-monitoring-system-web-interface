<?php
/**
 * Created by Synida Pry.
 * Copyright © 2020. TakeNote. All rights reserved.
 */

namespace App\Http\Measurable;

use App\Http\Component\MeasurableInterface;

/**
 * Class CpuUsage
 * @package App\Http\Measurable
 */
class CpuUsage implements MeasurableInterface
{
    /**
     * Returns with the CPU usage percentage
     *
     * @return array
     * @author Synida Pry
     */
    public function execute()
    {
        return [
            'current' => sys_getloadavg()[0],
            'min' => 0,
            'max' => 100,
        ];
    }
}
