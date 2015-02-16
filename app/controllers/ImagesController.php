<?php


/**
* Usage:
* $files = ImagesController::list_files();
* print_r($files);
*/
// class ImagesController extends App
class ImagesController
{
	
	function __construct($img="", $width = 200, $height = 200)
	{
		if( !empty($img) ){
			$this->new_image($img, $width, $height);
		}
	}


	public function file_name($file_name){
		return get_file_name($file_name);
	}

	public function file_extension($file_name){
		return get_file_extension($file_name);
	}

	public function thumbnail($img, $box_w, $box_h) {
		return thumbnail_box($img, $box_w, $box_h);
	}


	public static function new_image($img="", $width = 200, $height = 150){
		if( !empty($img) ){
			$data = [];
			$img_ext = get_file_extension($img);
			$img_name = get_file_name($img);
			$img_string = $img;


			if( strtolower($img_ext) == "png" ){
				$img = imagecreatefrompng($img);
			}else{
				$img = imagecreatefromjpeg($img);
			}

			$estampa = imagecreatefrompng('./logo.png');

			$margen_dcho = 10;
			$margen_inf = 10;
			$sx = imagesx($estampa);
			$sy = imagesy($estampa);


			$thumb = thumbnail_box($img, 200, 150);
			imagedestroy($img);
			
			// Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
			// ancho de la foto para calcular la posición de la estampa. 
			imagecopy($thumb, $estampa, 
				imagesx($thumb) - $sx - $margen_dcho, imagesy($thumb) - $sy - $margen_inf, 0, 0, 
				imagesx($estampa), imagesy($estampa));

			$folder = get_file_folder($img_string);
			$newname = $folder . $img_name . "_thumb." . $img_ext;

			if(is_null($thumb)) {
			    $data = ["error" => "image not created"];
				return $data;
			}

			if( strtolower($img_ext) == "png" ){
				$data = ["success" => "Image created", "type" => "png"];
				imagepng($png, $newname);
				return $data;
			}else{
				$data = ["success" => "Image created", "type" => "jpg"];
				imagejpeg($thumb, $newname);
				return $data;
			}

		}
	}



	public static function list_files($max=30, $folder="."){
		$current = 1;
		$files = [];
		$folder_name = $folder == "." ? "./" : $folder;

		if ($handle = opendir($folder)) {

		    while (false !== ($entry = readdir($handle))) {

		        if ($entry != "." && $entry != "..") {

		        	$extension = strtolower( get_file_extension($entry) );
		        	if( $extension == "jpg" || $extension == "jpeg" || $extension == "png" ){
			            if(count($files) <= $max && strpos($entry, "_thumb") == 0 ){
			            	$name = get_file_name($entry);

			            	$file = $folder . $name . '_thumb.' . $extension;
			            	if( !file_exists( $file ) ){
				            	array_push($files, $folder_name . $entry);
			            	}
			            }
		        	}
		        }
		    }

		    closedir($handle);
		}

		return $files;

	}



	public static function file_folder($file){
		return get_file_folder($file);
	}







}










	function get_file_extension($file_name) {
		return substr(strrchr($file_name,'.'),1);
	}

	function get_file_name($file_name) {
		return basename($file_name, '.' . get_file_extension($file_name));
	}

	function get_file_folder($file){
		return substr($file, 0, strrpos($file, '/') + 1);
	}



	function thumbnail_box($img, $box_w, $box_h) {

	    //create the image, of the required size
	    $new = imagecreatetruecolor($box_w, $box_h);
	    if($new === false) {
	        //creation failed -- probably not enough memory
	        return null;
	    }


	    //Fill the image with a light grey color
	    //(this will be visible in the padding around the image,
	    //if the aspect ratios of the image and the thumbnail do not match)
	    //Replace this with any color you want, or comment it out for black.
	    //I used grey for testing =)
	    $fill = imagecolorallocate($new, 0, 0, 0);
	    imagefill($new, 0, 0, $fill);

	    //compute resize ratio
	    $hratio = $box_h / imagesy($img);
	    $wratio = $box_w / imagesx($img);
	    $ratio = min($hratio, $wratio);

	    //if the source is smaller than the thumbnail size, 
	    //don't resize -- add a margin instead
	    //(that is, dont magnify images)
	    if($ratio > 1.0)
	        $ratio = 1.0;

	    //compute sizes
	    $sy = floor(imagesy($img) * $ratio);
	    $sx = floor(imagesx($img) * $ratio);

	    //compute margins
	    //Using these margins centers the image in the thumbnail.
	    //If you always want the image to the top left, 
	    //set both of these to 0
	    $m_y = floor(($box_h - $sy) / 2);
	    $m_x = floor(($box_w - $sx) / 2);

	    //Copy the image data, and resample
	    //
	    //If you want a fast and ugly thumbnail,
	    //replace imagecopyresampled with imagecopyresized
	    if(!imagecopyresampled($new, $img,
	        $m_x, $m_y, //dest x, y (margins)
	        0, 0, //src x, y (0,0 means top left)
	        $sx, $sy,//dest w, h (resample to this size (computed above)
	        imagesx($img), imagesy($img)) //src w, h (the full size of the original)
	    ) {
	        //copy failed
	        imagedestroy($new);
	        return null;
	    }
	    //copy successful
	    return $new;
	}