<?php


/**
* 
*/
class App
{
	
	public $isRoute;

	function __construct()
	{
		global $isRoute;

		if(!$isRoute)
		{
			return Route::missing(function(){
					App::template('error');
				});
		}	
	}



	public static function view($view,$vars=[])
	{
		global $partials, $title, $slogan, $path;

		$config_vars = ["partials" => $partials];
		array_push($config_vars, $vars);

		if( is_array($vars) && !empty($vars) )
		{
			extract($vars, EXTR_PREFIX_SAME, "wddx");
		}


		$view = $partials . $view . ".html";
		if( file_exists($view) )
		{
			return App::render($view, $vars);
		}
		else
		{
			return "<!-- view not found: " . $view . " -->";
		}

	}


	public static function render($file, $vars=[])
	{
		global $partials, $title, $slogan, $path;

		if( is_array($vars) && !empty($vars) ){
			extract($vars, EXTR_PREFIX_SAME, "wddx");
		}

		$ajax = Route::ajax();
		$url = Route::url();
		$uri = Route::uri();

		$content = file_get_contents($file, FILE_USE_INCLUDE_PATH);
		$content = str_replace('@{{', '<?php ', str_replace('}}@', ' ?>', 
				( preg_replace ( '/@{{(|\s)([^\']+)(|\s)}}@/', '$0', $content ) ) ));
		$content = str_replace('{{', '<?php echo ', str_replace('}}', '; ?>', 
				( preg_replace ( '/{{(|\s)([^\']+)(|\s)}}/', '$0', $content ) ) ));

		
		return eval('?>' . $content . '<?php ');
		// return $content;
	}

	public static function template($template, $vars=[])
	{
		global $partials, $title, $slogan, $path;

		echo App::view('header', $vars) . App::view($template . '.template', $vars) . App::view('footer', $vars);
	}




}

