<?php

/**
* 
*/
class PagesController extends App
{
	
	function __construct()
	{
		// echo 'pages';
	}

	function home(){
		return App::template('index');
	}

	function list_images(){
		return App::view('list');
	}

	function single($id=""){
		return App::view('single');
	}


}