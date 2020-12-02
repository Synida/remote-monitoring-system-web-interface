<?php

namespace App\Console\Commands;

use App\Measurable;
use App\MeasuredItem;
use Closure;
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
 * @property Channel[] $resultChannels
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
    protected $resultChannels;

    /**
     * Contains the active measurables
     *
     * @var Measurable[]
     */
    protected $measurables;

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
     * @throws Runtime\Error
     * @throws Runtime\Error\Bootstrap
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

        foreach ($this->measurables as $measurable) {
            $this->resultChannels[$measurable->table] = Channel::make($measurable->table);

            $namespace = 'App\\Http\\Measurable\\';
            $class = "{$namespace}{$measurable->class}";
            $object = new $class();

            $this->measurableThreads[$measurable->table] = (new Runtime(
                __DIR__ . "/../../../vendor/autoload.php"
            ))
                ->run($this->query, [$this->resultChannels[$measurable->table], $object, $measurable->frequency]);
        }

        while (1) {
            foreach ($this->measurableThreads as $measurableTable => $future) {
                $openChannel = Channel::open($this->resultChannels[$measurableTable]);
                $result = $openChannel->recv();
                if ($result === null) {
                    continue;
                }

                $measuredItem = MeasuredItem::setDBTable($measurableTable);
                $measuredItem->value = (string)$result;
                $measuredItem->save();
            }
        }
    }

    /**
     * Initializes the manager
     *
     * @return void
     * @author Synida Pry
     */
    public function init(): void
    {
        ini_set('memory_limit', config('app.memory_limit'));
        set_time_limit(0);
        declare(ticks=1);

        register_shutdown_function([$this, 'onShutdown']);
        pcntl_signal(SIGTERM, [$this, 'onSignal']);
        pcntl_signal(SIGHUP, [$this, 'onSignal']);
        pcntl_signal(SIGINT, [$this, 'onSignal']);

        // Initializes the service variables
        $this->initVariables();
    }

    /**
     * Initializes the DB query closure.
     *
     * @return void
     * @author Synida Pry
     */
    public function initVariables(): void
    {
        $this->measurables = Measurable::where('active', true)
            ->get();

        $this->query = static function (Channel $channel, $object, $frequency) {
            $open = time();
            while (1) {
                $time = time();
                if (!($time % $frequency) && $open !== $time) {
                    echo "*";
                    // Puts the measured value to the channel
                    $channel->send($object->execute());
                    $open = $time;
                } else {
                    // clearing the channel
                    $channel->send(null);
                }
            }
        };

        /** @var Measurable $measurable */
        foreach ($this->measurables as $measurable) {
            if (!Schema::hasTable($measurable->table)) {
                Schema::create(
                    $measurable->table,
                    function (Blueprint $table) {
                        $table->id()->autoIncrement();
                        $table->double('value')
                            ->comment('Measured value');
                        $table->timestamps();
                    }
                );
            }
        }
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
}
