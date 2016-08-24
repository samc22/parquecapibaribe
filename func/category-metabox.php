<?php

/**
 * Add a custom meta box to the new/edit category pages.
 * The meta data is saved to the array term_meta[], which can handle further
 * fields in the future.
 *
 * Based on: https://pippinsplugins.com/adding-custom-meta-fields-to-taxonomies/
 */
/**
 * Add meta box to the new category page.
 */
function bdq_taxonomy_add_new_meta_field() {
	// This will add the custom meta field to the add new term page.
	ob_start(); ?>
	<div class="form-field">
		<label for="term_meta[subtitulo]"><?php _e( 'Subtítulo', 'parquecapibaribe' ); ?></label>
		<input type="text" name="term_meta[subtitulo]" id="term_meta[subtitulo]" value="">
		<p class="description"><?php _e( 'Subtítulo é o texto que aparece abaixo do nome da categoria no site.', 'parquecapibaribe' ); ?></p>
		<?php wp_nonce_field ( 'update_term_meta', 'term_meta_nonce' ) ?>
	</div>
	<?php ob_end_flush();
}
add_action( 'category_add_form_fields', 'bdq_taxonomy_add_new_meta_field', 10, 2 );
/**
 *Add meta box to the term category page.
 */
function bdq_taxonomy_edit_meta_field($term) {
	// Put the term ID into a variable.
	$t_id = $term->term_id;
 
	// Retrieve the existing value(s) for this meta field. This returns an array.
	$term_meta = get_option( "taxonomy_$t_id" );
	ob_start(); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[subtitulo]"><?php _e( 'Subtítulo', 'parquecapibaribe' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[subtitulo]" id="term_meta[subtitulo]" value="<?php echo esc_attr( $term_meta['subtitulo'] ) ? esc_attr( $term_meta['subtitulo'] ) : ''; ?>">
			<p class="description"><?php _e( 'Subtítulo é o texto que aparece abaixo do nome da categoria no site.', 'parquecapibaribe' ); ?></p>
			<?php wp_nonce_field ( 'update_term_meta', 'term_meta_nonce' ) ?>
		</td>
	</tr>
	<?php ob_end_flush();
}
add_action( 'category_edit_form_fields', 'bdq_taxonomy_edit_meta_field', 10, 2 );
/**
 * Save meta data callback function.
 */
function bdq_save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = sanitize_text_field ( $_POST['term_meta'][$key] );
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_category', 'bdq_save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_category', 'bdq_save_taxonomy_custom_meta', 10, 2 );