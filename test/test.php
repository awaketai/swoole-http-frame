<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2020/2/18
 * Time: 10:46 下午
 */

require_once __DIR__ . '/../vendor/autoload.php';

(new \swo\Index())->index();
$index = new \app\controllers\Index();
// 如果先实例化，在内存中已经存在类对象，因此会直接去找对应的方法，而不是根据调用方式再去查找相应方法
$index->test();

// http请求 -> public/index.php -> 请求的url解析，根据解析的url查找控制器
// 1.直接根据命名空间
// 2.根据route

// 执行操作 -> 根据不同的结果相应内容
