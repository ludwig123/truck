<?php
// 加载基础文件
use think\Console;
use think\Container;

require __DIR__ . '/thinkphp/base.php';

// 应用初始化
Container::get('app')->path(__DIR__ . '/application/')->initialize();
