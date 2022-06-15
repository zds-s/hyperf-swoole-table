<?php
return [
    'size'=>1024,//最大内存
    'tables'=>[
        'scanner_path'=>BASE_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Table',//扫描的绝对目录
        'namespace'=>'App\\Table\\',//namespace空间
    ]
];