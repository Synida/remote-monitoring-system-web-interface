<?php

namespace App\Console\Commands\Measurable;

use App\Measurable;
use Exception;
use Illuminate\Console\Command;

/**
 * Class MeasurableDelete
 * @package App\Console\Commands\Measurable
 */
class MeasurableDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurable:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a measurable element from the database';

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
     * @throws Exception
     */
    public function handle()
    {
        $id = $this->argument('id');

        if (!is_numeric($id)) {
            $this->error('The ID must be numeric');
            return 1;
        }

        $measurable = Measurable::where('id', $id)->first();

        if (!$measurable instanceof Measurable) {
            $this->error("Measurable({$id}) not found in the database.");
            return 1;
        }

        $measurable->delete();

        $this->info('Measurable deleted successfully!');

        return 0;
    }
}
