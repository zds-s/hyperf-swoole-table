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
        $tables_path =config('swoole_table.tables.scanner_path');
        $namespace = config('swoole_table.tables.namespace');
        foreach (scandir($tables_path) as $path)
        {
            if ($path=='.'||$path=='..')
            {
                continue;
            }
            $class_name = $namespace.pathinfo($path,PATHINFO_FILENAME);
            $this->container->set($class_name,make($class_name));
        }
    }
}