<?php

namespace App\Console\Commands\Measurable;

use App\Measurable;
use Illuminate\Console\Command;

/**
 * Class MeasurableUpdate
 * @package App\Console\Commands\Measurable
 */
class MeasurableUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurable:update {id} {field} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a measurable field with a different value';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $field = $this->argument('field');
        $value = $this->argument('value');

        // Validates the arguments of the command.
        if (!$this->isValidateArgs($id, $field, $value)) {
            return 1;
        }

        $measurable = Measurable::where('id', $id)->first();

        if (!$measurable instanceof Measurable) {
            $this->error("Measurable({$id}) not found in the database.");
            return 1;
        }

        if (!isset($measurable->$field)) {
            $this->error("Field does not exists.");
            return 1;
        }

        $measurable->update([$field => $value]);

        $this->info('OK!');

        return 0;
    }

    /**
     * Validates the arguments of the command.
     *
     * @param string $id
     * @param string $field
     * @param string $value
     * @return bool
     * @author Synida Pry
     */
    public function isValidateArgs($id, $field, $value)
    {
        if (!is_numeric($id)) {
            $this->error('The ID must be numeric');
            return 0;
        }

        // Returns with the fillable of this class
        $allowedFields = (new Measurable)->getFillable();
        if (!in_array($field, $allowedFields)) {
            $this->error('The field must be one of the available fields: ');
            DD($allowedFields);
            return 0;
        }

        // TODO: validate value towards the fields

        return 1;
    }
}
