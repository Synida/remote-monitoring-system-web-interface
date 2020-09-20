<?php

namespace App\Console\Commands;

use App\Measurable;
use App\MeasuredItem;
use Closure;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use parallel\Channel;
use parallel\Runtime;

/**
 * Class ModuleManager
 * @package App\Console\Commands
 *
 * @property Closure $query
 * @property Runtime[] $measurableThreads
 * @property Channel $resultChannel
 */
class ThreadManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thread-manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes the monitoring process';

    /**
     * Contains the sensor and monitored system data query closure
     *
     * @var Closure
     */
    protected $query;

    /**
     * Contains the active threads of query closures.
     *
     * @var Runtime[]
     */
    protected $measurableThreads;

    /**
     * Contains the gathered sensor and system info results.
     *
     * @var Channel
     */
    protected $resultChannel;

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
     * @return void
     * @throws Channel\Error\Existence
     * @throws Runtime\Error\Closed
     * @throws Runtime\Error\IllegalFunction
     * @throws Runtime\Error\IllegalInstruction
     * @throws Runtime\Error\IllegalParameter
     * @throws Runtime\Error\IllegalReturn
     * @throws Runtime\Error\IllegalVariable
     */
    public function handle()
    {
        // Initializes the manager
        $this->init();

        while (1) {
            foreach ($this->measurableThreads as $measurableTable => $future) {
                $measured = $this->resultChannel->recv();
                if ($measured === null) {
                    continue;
                }

                // TODO: if measured is the same as last time, dont update the db?
                $measuredItem = MeasuredItem::setDBTable($measurableTable);
                $measuredItem->value = $measured['current'];
                $measuredItem->save();
            }
        }
    }

    /**
     * Initializes the manager
     *
     * @return void
     * @throws Runtime\Error\Closed
     * @throws Runtime\Error\IllegalFunction
     * @throws Runtime\Error\IllegalInstruction
     * @throws Runtime\Error\IllegalParameter
     * @throws Runtime\Error\IllegalReturn
     * @throws Runtime\Error\IllegalVariable
     * @author Synida Pry
     */
    public function init(): void
    {
        ini_set('memory_limit', config('app.memory_limit'));
        set_time_limit(0);
        declare(ticks = 1);

        register_shutdown_function([$this, 'onShutdown']);
        pcntl_signal(SIGTERM, [$this, 'onSignal']);
        pcntl_signal(SIGHUP, [$this, 'onSignal']);
        pcntl_signal(SIGINT, [$this, 'onSignal']);

        // Initializes the service variables
        $this->initVariables();
    }

    /**
     * Kills the process.
     *
     * @param int $signal
     * @return void
     * @author Synida Pry
     */
    public function onSignal($signal): void
    {
        $this->info("Received PCNTL signal {$signal}");
        // TODO: close the channels
        die();
    }

    public function onShutdown(): void
    {
        $this->info('Shutdown called, exiting.');
        // TODO: close the channels
    }

    /**
     * Initializes the DB query closure.
     *
     * @return void
     * @throws Runtime\Error\Closed
     * @throws Runtime\Error\IllegalFunction
     * @throws Runtime\Error\IllegalInstruction
     * @throws Runtime\Error\IllegalParameter
     * @throws Runtime\Error\IllegalReturn
     * @throws Runtime\Error\IllegalVariable
     * @author Synida Pry
     */
    public function initVariables(): void
    {
        $measurables = Measurable::where('active', '==', true)
            ->get();

        $this->query = static function (Channel $channel, $className, $frequency) {
            $namespace = 'App\\Http\\Measurable\\';
            $class = "{$namespace}{$className}";
            $object = new $class();

            while (1) {
                if (!(time() % $frequency)) {
                    // Returns with a measurable information
                    $channel->send($object->execute());
                }
            }
        };

        $this->resultChannel = new Channel();

        /** @var Measurable $measurable */
        foreach ($measurables as $measurable) {
            try {
                Schema::create($measurable->table, function (Blueprint $table) {
                    $table->id()->autoIncrement();
                    $table->double('value')
                        ->comment('Measured value');
                    $table->timestamps();
                });
            } catch (Exception $e) {
                $this->info($e->getMessage());
            }

            $this->measurableThreads[$measurable->table] = (new Runtime())
                ->run($this->query, [$this->resultChannel, $measurable->class, $measurable->frequency]);
        }

//        $this->queueUpdateFrequency = Yii::$app->params['taskManager']['queueUpdateFrequency'];
//        $this->dbQueryThread = (new Runtime(Yii::$app->params['taskManager']['bootstrapFile']));
//
//        $this->dbQueryClosure = static function ($taskQueue = [], $mode = null) {
//            new Autoloader($mode);
//
//            $resultsArray = (new ThreadedExecutionManagerSearch())->search(['taskQueue' => $taskQueue])->getModels();
//            $objects = [];
//            foreach ($resultsArray as $taskArray) {
//                $baseTask = new BaseTask();
//                $baseTask->setAttributes($taskArray, false);
//                $objects[] = $baseTask;
//            }
//
//            return $objects;
//        };
//
//        $this->taskProcessingClosure = static function ($task, $mode) {
//            new Autoloader($mode);
//
//            /** @var BaseTask $task */
//            if (isset($task->class_name)) {
//                // TODO: update all of the execute tasks
//                // TODO: dont forget to set the recurring tasks up after they are done
//                // TODO: put the failed task back to the scheduled execution list with an updated scheduled_at
//                $task->execute();
//                // TODO: logging
//            } else {
//                // TODO: instant execution for non tasks
//            }
//        };
    }
}
