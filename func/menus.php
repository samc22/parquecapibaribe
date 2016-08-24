<?php

register_nav_menus(
	array(
		'menu-principal' => 'Menu Principal',
	)
);

add_filter( 'nav_menu_link_attributes', 'show_menu_atts', 10, 3 );
function show_menu_atts( $atts, $item, $args ) {

    $raw_url = $item->url;

    $slug = bdq_hash_slug($raw_url);

    $atts['href'] = "#" . $slug;
    $atts['data-page-url'] = $item->url;
    $atts['id'] = 'menu-'.$slug;
    $atts['data-menu-item-type'] = $item->object;


	return $atts;
}
