<?php

  $t_id = get_query_var('cat');
  $cat_link = get_term_link($t_id);
  $slug = bdq_hash_slug($cat_link);

  $redirect = get_bloginfo('url') . '/#' . $slug;

  if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    /* SE NÃO FOR AJAX REDIRECIONA */

    header("Location: $redirect");
    die();
  }
  

  $term_meta = get_option( "taxonomy_$t_id" );

?>
<section id="<?php echo $slug;?>">
  <div class="row">
    <div class="col-xs-8 col-sm-8 col-md-12">
      <h1><?php single_cat_title();?></h1>
      <?php

        if ($term_meta['subtitulo'] != ''):

          echo "<h2>" . $term_meta['subtitulo'] . "</h2>";

        endif;

      ?>
      <p><?php echo category_description();?></p>
    </div>
  </div>
  <div class="row">
    <?php if(have_posts()):?>
      <?php while(have_posts()): the_post();?>

        <div class="col-xs-8 col-sm-3 col-md-6">
          <div class="img-wrapper">
            <?php

              $data_action_link = get_permalink();
              $action_id=bdq_hash_slug(get_permalink());

            ?>
            <a data-action-slug="<?php echo bdq_hash_slug(get_permalink());?>" class="action" data-action-title="<?php the_title(); ?>" id="<?php echo $action_id;?>" data-action-link="<?php echo $data_action_link;?>">
              <h1><?php the_title();?></h1>
              <?php

                $post_id = $post->ID;
                $post_subtitle = get_post_meta($post_id, 'post-subtitle', true);

                if ($post_subtitle != ''):

                  echo "<h2>$post_subtitle</h2>";

                endif;

                the_post_thumbnail('action-list', array('class' => 'img-responsive'));
                the_excerpt();

              ?>
            </a>
          </div>
        </div>

      <?php endwhile;?>
    <?php endif;?>
  </div>
</section>