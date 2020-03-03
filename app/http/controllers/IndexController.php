<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2020/2/22
 * Time: 12:40 下午
 */

namespace app\http\controllers;


class IndexController
{
    public function index(){

        return '<h2>Swo Frame</h2>';
    }
    
    public static function test(){
        
        return 'index--test';
    }

}