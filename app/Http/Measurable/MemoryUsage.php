<?php
/**
 * Created by Synida Pry.
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
     * @return string
     * @author Synida Pry
     */
    public function execute()
    {
        $freeMemory = explode("\n", (string)trim(shell_exec('free')))[1];
        $memory = array_merge(array_filter(explode(" ", $freeMemory)));

        return round($memory[2] / 1073741824, 2);
    }
}
