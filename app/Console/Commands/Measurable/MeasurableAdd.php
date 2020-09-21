<?php

namespace App\Console\Commands\Measurable;

use App\Measurable;
use Illuminate\Console\Command;

/**
 * Class MeasurableRegister
 * @package App\Console\Commands\Measurable
 */
class MeasurableAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurable:add {active} {name} {class} {table} {unit} {frequency}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new measurable to the database';

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
        $active = $this->argument('active');
        $name = $this->argument('name');
        $table = $this->argument('table');
        $unit = $this->argument('unit');
        $class = $this->argument('class');
        $updateFrequency = $this->argument('frequency');

        // TODO: validations; create a validation class accessable from the MeasurableUpdate class too

        Measurable::create(
            [
                'active' => $active,
                'name' => $name,
                'table' => $table,
                'class' => $class,
                'unit' => $unit,
                'frequency' => $updateFrequency
            ]
        );

        $this->info('OK!');

        return 0;
    }
}
