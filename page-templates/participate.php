<?php

/*
Template Name: Mapa
*/

    the_post();

    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        /* SE NÃO FOR AJAX REDIRECIONA */

        $redirect = get_bloginfo('url') . '/#' . bdq_hash_slug(get_permalink());
        header("Location: $redirect");
        die();
    }

?>

<section id="<?php echo bdq_hash_slug(get_permalink());?>">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
  
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-12">
        <h1><?php the_title();?></h1>

        <?php the_content();?>
      </div>
    </div>

  <iframe src="http://parquecapibaribe.org/mapa" class="mapa" frameborder="0"></iframe>

</section>
