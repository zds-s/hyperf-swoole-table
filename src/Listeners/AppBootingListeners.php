<?php


namespace DeathSatan\Hyperf\Swoole\Table\Listeners;


use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Psr\Container\ContainerInterface;

class AppBootingListeners implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface  $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            BootApplication::class
        ];
    }

    public function process(object $event)
    {
        $tables = config('swoole_table.tables',[]);
        foreach ($tables as $table)
        {
            $table_object = make($table);
            $this->container->set(get_class($table_object),$table_object);
        }
    }
}