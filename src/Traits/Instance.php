<?php

namespace Calfacade\Traits;

/**
 * 通用单例trait，实现单例模式
 * @package Calfacade\Traits
 * @author Calvin Huang
 * @time 2021-04-07
 * 
 */
trait Instance
{
	protected static $instance;//实例对象

    /**
     * 获取实例
     * @param array $option 实例配置
     * @return Instance
     */
    public static function instance($option = [])
    {
        if(is_null(static::$instance)) static::$instance = new static($option);
        return static::$instance;
    }

    /**
     * 静态调用
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        if(is_null(static::$instance)) static::$instance = static::instance();
        $call = substr($name, 1);
        if(0 !== strpos($name, '_') || ! is_callable([static::$instance, $call])) {
            // 抛出错误
            echo "method {$call} not exists";
            exit;
        }
        return call_user_func_array([static::$instance, $call], $arguments);
    }
}