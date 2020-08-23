<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MeasuredItem
 * @package App
 *
 * @property string $table
 * @property double $value
 */
class MeasuredItem extends Model
{
    /**
     * This attribute disable using the created_at and updated_at fields in the database
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The database table used by the model.
     * This field is dynamically changing.
     *
     * @var string
     */
    protected $table = 'measured_item';

    /**
     * The table's primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // one single measured value
        'value',
    ];

    /**
     * Sets the measured item table to prepare for query.
     *
     * @param string $table
     * @return MeasuredItem
     * @author Synida Pry
     */
    public static function setDBTable($table): MeasuredItem
    {
        $measuredItem = new MeasuredItem();
        $measuredItem->table = $table;

        return $measuredItem;
    }
}
