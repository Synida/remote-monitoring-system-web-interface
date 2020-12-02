<?php
/**
 * Created by Synida Pry.
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
     * @return string
     * @author Synida Pry
     */
    public function execute()
    {
        return sys_getloadavg()[0];
    }
}
