<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Measurable
 * @package App
 *
 * @property int $id
 * @property array $fillable
 * @property string $name
 * @property string $class
 * @property string $table
 * @property boolean $active
 * @property string $unit
 * @property integer $frequency
 */
class Measurable extends Model
{
    /**
     * This attribute disable using the created_at and updated_at fields in the database
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'measurable';

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
        // status of the measurable
        'active',
        // Name of the measurable
        'name',
        // Class name of the measurable item
        'class',
        // database table name of the measurable
        'table',
        // unit of the measurable, like °C, Hz, ect.
        'unit',
        // max value of a specific measurable
        'max',
        // min value of a specific measurable
        'min',
        // update frequency in ms, that defines how often the measurable object need to be checked
        'frequency'
    ];

    /**
     * Returns with the fillable of this class
     *
     * @return array
     * @author Synida Pry
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }
}
