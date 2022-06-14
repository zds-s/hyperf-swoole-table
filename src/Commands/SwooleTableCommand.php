<?php


namespace DeathSatan\Hyperf\Swoole\Table\Commands;


class SwooleTableCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('gen:swoole_table');
        $this->setDescription('Create a new SwooleTable class');
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/table.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Table';
    }
}