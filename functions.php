<?php

	add_theme_support( 'post-thumbnails' );
	add_image_size ( 'slider', 1000, 706, true );
	add_image_size ( 'action-list', 750, 450, true );
	add_image_size ('logo', 200, 100, false);

	add_filter('image_size_names_choose', 'my_image_sizes');
	function my_image_sizes($sizes) {
		$addsizes = array(
			"logo" => __( "Logo")
		);
		$newsizes = array_merge($sizes, $addsizes);
		return $newsizes;
	}	
	
	if (!function_exists("pretty_dump")) {
	   function pretty_dump($s, $return=false) {
	       $x = "<pre>";
	       $x .= print_r($s, 1);
	       $x .= "</pre>";
	       if ($return) return $x;
	       else print $x;
	   }
	}

	/**
	 * Find the position of the Xth occurrence of a substring in a string
	 * @param $haystack
	 * @param $needle
	 * @param $number integer > 0
	 * @return int
	 */
	function strposX($haystack, $needle, $number){
	    if($number == '1'){
	        return strpos($haystack, $needle);
	    }elseif($number > '1'){
	        return strpos($haystack, $needle, strposX($haystack, $needle, $number - 1) + strlen($needle));
	    }else{
	        return error_log('Error: Value for parameter $number is out of range');
	    }
	}


	function bdq_remove_url_slash($url) {

		$url_len = strlen($raw_url);

		if (substr($url, $url_len - 1, 1) == "/"):

			$raw_slug = substr($url, 0,  $url_len - 1);

		else:

			$raw_slug = $url;

		endif;

		return $raw_slug;

	}

	function bdq_hash_slug($raw_url){
		$raw_url = str_replace(get_site_url(), '', $raw_url);
		$raw_slug = bdq_remove_url_slash($raw_url);
		$slash_pos = strposX($raw_slug, "/", 3);
		$slug = substr($raw_slug, $slash_pos + 1);
		$slug = str_replace("/", "-", $slug);

		return $slug;

	}

	function curPageURL() {

		$pageURL = 'http';

		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

		$pageURL .= "://";

		if ($_SERVER["SERVER_PORT"] != "80") {

			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

		} else {

			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

		}

		return $pageURL;
	}

	function bdq_show_only_with_thumbs( $query ) {
	    if ( $query->is_category() ) {
	        $query->set( 'meta_key', '_thumbnail_id' );
	    }
	}
	add_action( 'pre_get_posts', 'bdq_show_only_with_thumbs' );			

	include('func/scripts.php');
	include('func/menus.php');
	include('func/category-metabox.php');
	include('func/post-metabox.php');
	include('func/redirect.php');
	include('func/ws-rio-capibaribe-tweets.php');
	include('func/ws-test-tweets.php');
