<?php

require_once "autoload.php";

/**
* 
*/
class Inflate extends App
{
	
	function __construct($class="")
	{
		if( !empty($class) )
		{
			if( strpos($class, '@') != 0 )
			{
				$classexplode = explode('@', $class);
				$classname = $classexplode[0];
				$classmethod = $classexplode[1];
				$newClass = new $classname;
				if( method_exists($newClass, $classmethod) )
				{
					return $newClass->$classmethod();
				}
				else
				{
					echo 'Method not found in class: ' . $class;
					return false;
				}
			}
		}
	}
}


function view($view="", $vars=[])
{
	return App::view($view, $vars=[]);
}