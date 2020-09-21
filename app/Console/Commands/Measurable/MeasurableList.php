<?php

namespace App\Console\Commands\Measurable;

use App\Measurable;
use Illuminate\Console\Command;

/**
 * Class MeasurableList
 * @package App\Console\Commands\Measurable
 */
class MeasurableList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurable:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists measurable items from the database';

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
        $measurables = Measurable::all();

        $this->info('Measurable items in the database:');

        if (!count($measurables)) {
            $this->info('DB is empty!');
        }

        $this->info(DD($measurables->toArray()));

        return 0;
    }
}
