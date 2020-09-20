<?php

namespace App\Http\Controllers;

use App\Measurable;
use App\MeasuredItem;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

/**
 * Class MeasurableController
 * @package App\Http\Controllers
 */
class MeasurableController extends Controller
{
    /**
     * Returns with a chunk of measurable object data for display
     *
     * @param Request $request
     * @return mixed
     * @author Synida Pry
     */
    public function index(Request $request)
    {
        $exists = Measurable::andWhere('table', '==', $request->type)
            ->andWhere('active', '==', true)
            ->limit(1)
            ->count();

        if (!$exists) {
            throw new InvalidArgumentException('There is no such active measured object!');
        }

        $from = $request->from ?? 86400;

        if (!is_numeric($from) || $from < 0) {
            throw new InvalidArgumentException('The range limit must be a positive integer');
        }

        return MeasuredItem::setDBTable($request->type)
            ->andWhere('created_at', '>', $from)
            ->get();
    }
}
