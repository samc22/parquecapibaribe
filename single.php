<?php

	the_post();
	$url = get_permalink();
	if ( empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):


		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'menu-principal' ] ) ):

			$post_categories = wp_get_post_categories(get_the_id());
			$nav_menu = wp_get_nav_menu_object( $locations[ 'menu-principal' ] );
			$menu_items = wp_get_nav_menu_items($nav_menu->term_id);

			foreach ($menu_items as $item):

				if ($item->object === 'category'):
					foreach ($post_categories as $category):
						if ($item->object_id == $category):

							$category_url = get_term_link($category);

							header("Location: ".get_bloginfo('url') . "/#" . bdq_hash_slug($category_url) . '/' . bdq_hash_slug($url));
							die();

						endif;

					endforeach;
				endif;

			endforeach;

			header("Location: ".get_bloginfo('url'));
			die();

		endif;
	endif;

	$slug = bdq_hash_slug($url);

	$featured_id = get_post_thumbnail_id();
	$slider_featured = wp_get_attachment_image_src($featured_id, "slider");
	$slider_featured = $slider_featured[0];
	$mobile_featured = wp_get_attachment_image_src($featured_id, "action-list");
	$mobile_featured = $mobile_featured[0];

	$attachments = get_posts(
		array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_parent' => $post->ID,
			'exclude'     => get_post_thumbnail_id()
		)
	);

			$class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
			$thumbimg = wp_get_attachment_link( $attachment->ID, 'thumbnail-size', true );
			//echo '<li class="' . $class . ' data-design-thumbnail">' . $thumbimg . '</li>';

?>
<article id="<?php echo $slug;?>">

	<div class="bdq-slide bdq-slide-featured" style="background-image: url(<?php echo $slider_featured ;?>);">
		<img class="bg-img" src="<?php echo $mobile_featured;?>">
	</div>
	<div class="bdq-slide" style="background-image: url(<?php echo $slider_featured ;?>);">
		<div class="bdq-slide-content-overlay"></div>
		<div class="bdq-slide-content">
			<?php the_content();?>
		</div>
	</div>
	<?php
		if ( $attachments ):
			foreach ( $attachments as $attachment ):

				$image_id = $attachment->ID;
				$slider_image = wp_get_attachment_image_src($image_id, "slider");
				$slider_image = $slider_image[0];
				$mobile_image = wp_get_attachment_image_src($image_id, "action-list");
				$mobile_image = $mobile_image[0];
	?>

				<div class="bdq-slide bdq-slide-image" style="background-image: url(<?php echo $slider_image ;?>);">
					<img class="bg-img" src="<?php echo $mobile_image;?>">
				</div>

	<?php
			endforeach;
		endif;
	?>

</article>