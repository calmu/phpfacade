<?php

namespace Calfacade\Traits;

trait Facade
{
	use Instance;
	protected static $obj = null;// 此处的obj是拿来存放真正的功能类的单例

	public static function __callStatic($method, array $arguments)
	{
		# 单例自己
		if ( ! is_object(static::$instance)) static::instance();
		# 单例真正的功能类
		if (is_null(self::$obj)) {
			if (method_exists(static::$instance, 'getName')) {
				#1,定义了具体的功能类。包含命名空间
				$name = static::$instance->getName();
			} else {
				#2,插件包方式，就在同等的命名空间下，并且以门面的类名为目录下
				/*$name = get_class(static::$instance);
				$name .= '\\' . basename($name);*///basename(str_replace('\\', '/', $name));
				// 用反射类进行定位真实类
				$class = new \ReflectionClass(static::$instance);
				$class_name = $class->getShortName();
				$name = $class->getNamespaceName() . "\\{$class_name}\\{$class_name}";
			}
			class_exists($name) || $name = "\\{$name}";
			if ( ! class_exists($name)) {
				echo "class {$name} not exists";
				exit;
			}
			static::_realMe($name);
		}
		return call_user_func_array([static::$obj, $method], $arguments);
	}
	protected static function _realMe(string $name)
	{
		static::$obj = $name::instance();
	}
	//protected static function getName(){} // 如果找不到类请实现此函数
}