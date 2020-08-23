<?php

namespace App\Console\Commands;

use App\Measurable;
use App\MeasuredItem;
use Illuminate\Console\Command;

/**
 * Class Maintenance
 * @package App\Console\Commands
 */
class Maintenance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance {before}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the database from the old data';

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
        $before = $this->argument('before');

        if (!is_numeric($before)) {
            $this->error('The "before" parameter must be numeric!');
            return 1;
        }

        $measurables = Measurable::all();

        foreach ($measurables as $measurable) {
            MeasuredItem::setDBTable($measurable->table)
                ->where('created_at', '<', $before)
                ->delete();
        }

        return 0;
    }
}
