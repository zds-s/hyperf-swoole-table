<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace DeathSatan\Hyperf\Swoole\Table;

use DeathSatan\Hyperf\Swoole\Table\Commands\SwooleTableCommand;
use DeathSatan\Hyperf\Swoole\Table\Listeners\AppBootingListeners;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [

            ],
            'commands' => [
                SwooleTableCommand::class
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'listeners'=>[
                AppBootingListeners::class
            ],
            'publish'=>[
                [
                    'id'=>'swoole_table',
                    'description'=>'swoole_table config file',
                    'source'=>__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'publish'.DIRECTORY_SEPARATOR.'config.php',
                    'destination'=>BASE_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'autoload'.DIRECTORY_SEPARATOR.'swoole_table.php'
                ]
            ]
        ];
    }
}
