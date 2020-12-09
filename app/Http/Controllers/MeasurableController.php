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
<<<<<<< HEAD
        $exists = Measurable::all()
            ->where('id', '==', $request->id)
=======
        /** @var Measurable $exists */
        $exists = Measurable::where('id', '==', $request->id)
>>>>>>> master
            ->where('active', '==', true)
            ->first();

        if ($exists === null) {
            throw new InvalidArgumentException('There is no such active measured object!');
        }

<<<<<<< HEAD
        if (isset($request->from)) {
            $from = $request->from;
            if (!is_numeric($from) || $from < 0) {
                throw new InvalidArgumentException('The range limit must be a positive integer');
            }
=======
        $from = $request->from ?? null;

        if ($from === null) {
            return MeasuredItem::setDBTable($exists->id)
                ->orderBy('created_at')
                ->first();
        }
>>>>>>> master

            return MeasuredItem::setDBTable($exists->table)
                ->orderBy('created_at', 'desc')
                ->where('created_at', '>', $from)
                ->first(['value']);
        }

<<<<<<< HEAD
        return MeasuredItem::setDBTable($exists->table)
            ->orderBy('created_at', 'desc')
            ->first(['value']);
=======
        return MeasuredItem::setDBTable($exists->id)
            ->where('created_at', '>', $from)
            ->get();
>>>>>>> master
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
