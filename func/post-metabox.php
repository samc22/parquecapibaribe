<?php

add_action( 'add_meta_boxes', 'register_post_meta_box' );

function register_post_meta_box() {
	add_meta_box( 'post-meta-box', __('Informações Adicionais', 'sbs-theme'), 'render_post_meta_box', 'post', 'normal', 'high');
}

function render_post_meta_box() {

	global $post;
	$post_id = $post->ID;

	$post_meta = get_post_meta($post_id);

	$post_subtitle = $post_meta['post-subtitle'][0];

?>
	<p>
		<label for='post-subtitle'>Subtítulo</label><br>
		<input type="text" id="post-subtitle" name="post-subtitle" value="<?php echo $post_subtitle;?>">
	</p>
<?php
}


add_action( 'save_post', 'meta_box_save' );
function meta_box_save( $post_id )  
{  
//  global $post;
    // Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  

    // salva links para slides, eventos, videos, fotos e páginas de transição
    
    if(get_post_type($post_id) == 'post'):

        
        if(!empty( $_POST['post-subtitle'] ) ):

            update_post_meta( $post_id, 'post-subtitle', $_POST['post-subtitle']);

        else:

            delete_post_meta( $post_id, 'post-subtitle');

        endif;

    endif;

}