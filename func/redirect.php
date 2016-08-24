<?php

/*

add_action( 'template_redirect', 'bdq_hash_redirect' );

function bdq_hash_redirect() {

	$current_page = curPageURL();

	if (!is_admin() && !strpos($current_page, "#") && bdq_remove_url_slash($current_page) != bdq_remove_url_slash(get_bloginfo('url'))):

		$section_id = bdq_hash_slug($current_page);

		$html_output = "
			<section id='$section_id'>$current_page<br>$section_id</section>
		";

		die($html_output);

	endif;

}

*/