<?php
/**
 * Created by Synida Pry.
 * Copyright © 2020. TakeNote. All rights reserved.
 */

namespace App\Http\Measurable;

use App\Http\Component\MeasurableInterface;

/**
 * Class MemoryUsage
 * @package App\Http\Measurable
 */
class MemoryUsage implements MeasurableInterface
{
    /**
     * Returns with the memory usage percentage
     *
     * @return mixed
     * @author Synida Pry
     */
    public function execute()
    {
        $freeMemory = explode("\n", (string)trim(shell_exec('free')))[1];
        $memory = array_merge(array_filter(explode(" ", $freeMemory)));

        return [
            'current' => round($memory[2] / $memory[1] * 100, 2),
            'min' => 0,
            'max' => round($memory[1] / 1073741824, 2) . ' GB'
        ];
    }
}
