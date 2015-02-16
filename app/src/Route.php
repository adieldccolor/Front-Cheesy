<?php

/**
* 
*/
class Route extends App
{

	public $isRoute;
	
	function __construct()
	{

	}


	public static function ajax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
				&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}


	public static function json($data=[])
	{
		$response = ["error" => "Invalid data passed."];
		if( is_array($data) && !empty($data) )
		{
			header('Content-Type: application/json');
			$response = json_encode($data);
		}
		
		return $response;
	}

	public static function compileRoute()
	{
		$optionals = Route::extractOptionalParameters();

		$uri = preg_replace('/\{(\w+?)\?\}/', '{$1}', Route::uri());

		return $uri;
	}

	/**
	 * Get the optional parameters for the route.
	 *
	 * @return array
	 */
	public static function extractOptionalParameters()
	{
		preg_match_all('/\{(\w+?)\?\}/', Route::uri(), $matches);

		return isset($matches[1]) ? array_fill_keys($matches[1], null) : [];
	}


	public static function url(){
		$url = '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		return $url;
	}




	public static function uri(){
		global $path;

		$url = Route::url();

		$uri = str_replace($path, '', $url);
		$uri = strpos($uri, '?') > -1 ? explode("?", $uri)[0] : $uri;
		$uri = $uri == "" ? "/" : $uri;

		if( !empty($route) ){
			$route = preg_replace('/\{(\w+?)\}/', '{$1}', $route);
			// echo $route;
		}

		return $uri;
	}







	public static function controller($route="", $callback="")
	{
		global $isRoute;

		$uri = Route::uri();

		$route_lastchar = substr($route, -1);
		if( $route_lastchar == "/" )
		{
			$route = substr($route, 0, -1);
		}
		

		$uri_lastchar = substr($uri, -1);
		if( $uri_lastchar == "/" )
		{
			$uri = substr($uri, 0, -1);
		}



		if($route == $uri)
		{
			$isRoute = $isRoute ? $isRoute : true;

			if( is_callable($callback) )
			{
				return call_user_func($callback);
			}
			elseif( is_string($callback) )
			{
				return new Inflate($callback);
			}
			else
			{
				echo "Bad request. Missing callback.";
				return false;
			}
		}

	}


	public static function missing($callback=""){
		global $isRoute;

		if( is_callable($callback) && !$isRoute )
		{
			return call_user_func($callback);
		}
		else
		{
			echo "Bad request. Bad callback in missing handler.";
			return false;
		}
	}

}