<?php 

    the_post();

    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        /* SE NÃO FOR AJAX REDIRECIONA */

        $redirect = get_bloginfo('url') . '/#' . bdq_hash_slug(get_permalink());
        header("Location: $redirect");
        die();
    }

?>
<section id="<?php echo bdq_hash_slug(get_permalink());?>">
	<h1><?php the_title();?></h1>

	<?php the_content();?>
</section>