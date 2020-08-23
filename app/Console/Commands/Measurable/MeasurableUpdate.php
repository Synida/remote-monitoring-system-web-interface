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
        // TODO: validations

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


}
