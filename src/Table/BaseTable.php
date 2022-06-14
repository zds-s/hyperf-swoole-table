<?php


namespace DeathSatan\Hyperf\Swoole\Table\Table;


use Hyperf\Contract\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Swoole\Table;

class BaseTable
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    protected $dispatch_fields = [
        'string'=>Table::TYPE_STRING,
        'float'=>Table::TYPE_FLOAT,
        'int'=>Table::TYPE_INT
    ];

    protected $config = [
        'table'=>[
            'id'=>'int'
        ]
    ];


    protected $table;

    public function __construct(array $config =[])
    {
        $this->parseConfig($config);
        $this->createTable();
        if (method_exists($this,'init'))
        {
            $this->init();
        }
    }

    protected $key;

    protected $attributes = [];

    /**
     * @param string $key
     * @return BaseTable
     */
    public function setKey(string $key):self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * @return Table
     */
    public function getTable():Table
    {
        return $this->table;
    }

    protected function createTable()
    {
        $tables = $this->config['table'];
        $swoole_table = new Table((int)$this->config['size']);
        foreach ($tables as $item => $value)
        {
            if (is_array($value))
            {
                $swoole_table->column($item,$this->dispatch_fields[$value[0]],$value[1]);
                continue;
            }else{
                $swoole_table->column($item,$this->dispatch_fields[$value]);
            }
        }
        if (!$swoole_table->create())
        {
            throw new \Exception('swoole_table 创建失败');
        }
        $this->table = $swoole_table;
    }

    //格式化配置
    protected function parseConfig($config)
    {
        if (!isset($config['size'])){
            $config['size'] = config('swoole_table.size',1024);
        }
        $this->config = array_merge($this->config,$config);
    }

    /**
     * 新增一行以key为主的数据
     * 如果key已存在则覆盖该数据
     * @param string $key key
     * @param array $data 数组必须严格对照当前类所声明的字段
     * @return string|null 成功返回key,失败返回null
     */
    public function insert(string $key,array $data):?string
    {
        return $this->getTable()->set($key,$data)?$key:null;
    }

    /**
     * 自增
     * @param string $key 数据的 key【如果 $key 对应的行不存在，默认列的值为 0】
     * @param string $column 指定列名【仅支持浮点型和整型字段】
     * @param int|float $incr_by 增量 【如果列为 int，$incrby 必须为 int 型，如果列为 float 型，$incrby 必须为 float 类型】
     * @return int|float
     */
    public function increment(string $key,string $column,$incr_by=1)
    {
        return $this->getTable()->incr($key,$column,$incr_by);
    }

    /**
     * 自减
     * @param string $key 数据的 key【如果 $key 对应的行不存在，默认列的值为 0】
     * @param string $column 指定列名【仅支持浮点型和整型字段】
     * @param int|float $decr_by 增量 【如果列为 int，$decr_by 必须为 int 型，如果列为 float 型，$decr_by 必须为 float 类型】
     * @return int|float
     */
    public function decrement(string $key,string $column,$decr_by=1)
    {
        return $this->getTable()->incr($key,$column,$decr_by);
    }

    /**
     * 根据key查找一条数据
     * @param string $key 数据的 key【必须为字符串类型】
     * @param array|string[] $fields 要显示的字段列表,如果为单个字段则会直接返回单个字段的值
     * @return false|string|array|mixed
     */
    public function find(string $key,array $fields=['*'])
    {
        $this->attributes = $this->getTable()->get($key);
        if (in_array('*',$fields)){
            return $this->attributes;
        }else{
            return Arr::only($this->attributes,$fields);
        }
    }

    /**
     * 返回列的值
     * @param string $field 列
     * @return array|string|mixed
     */
    public function value(string $field)
    {
        return Arr::only($this->attributes,$field);
    }

    /**
     * 检查key是否存在
     * @param string $key
     * @return bool
     */
    public function exist(string $key):bool
    {
        return $this->getTable()->exists($key);
    }

    /**
     * 统计当前table中一共多少条数据
     * @return int
     */
    public function count():int
    {
        return $this->getTable()->count();
    }

    /**
     * 如果没有传入key,则删除当前列的数据.如果传入key则根据key删除对应的列
     * @param string|null $key
     * @return bool
     */
    public function delete(?string $key=null):bool
    {
        return $key===null?
            $this->getTable()->del($this->getKey()):
            $this->getTable()->del($key);
    }
}