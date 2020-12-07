<?php

namespace App\Http\Controllers;

use App\Measurable;
use App\MeasuredItem;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        /** @var Measurable $exists */
        $exists = Measurable::where('id', '==', $request->id)
            ->where('active', '==', true)
            ->first();

        if ($exists === null) {
            throw new InvalidArgumentException('There is no such active measured object!');
        }

        $from = $request->from ?? null;

        if ($from === null) {
            return MeasuredItem::setDBTable($exists->id)
                ->orderBy('created_at')
                ->first();
        }

        if (!is_numeric($from) || $from < 0) {
            throw new InvalidArgumentException('The range limit must be a positive integer');
        }

        return MeasuredItem::setDBTable($exists->id)
            ->where('created_at', '>', $from)
            ->get();
    }

    /**
     * Returns with the active measurable objects.
     *
     * @param Request $request
     * @return array
     * @author Synida Pry
     */
    public function getActive(Request $request)
    {
        return Measurable::where('active', true)
            ->get(['id', 'name', 'frequency', 'unit']);
    }
}
