<?php
/**
 * Discgolf Mimizan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/advanced-topics/child-themes/
 *
 * @package discgolf
 * @since discgolf 1.0
 */


/**
 * Enqueue the parent and child theme stylesheets.
 */
add_action( 'wp_enqueue_scripts', 'exp_enqueue_styles' );
function exp_enqueue_styles() {
	$parent_style = 'type-style';
	$parent_theme = wp_get_theme('type-plus');
	wp_enqueue_style( $parent_style, 
		get_template_directory_uri() . '/style.css',
		array(),
		$parent_theme->get('Version')
	);
	wp_enqueue_style( 'discgolf-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version')
	);
	wp_enqueue_script( 'discgolf-scripts',
		get_stylesheet_directory_uri() . '/discgolf.js',
		array( 'jquery' )
	);

}

// Add support for editor styles.
add_theme_support( 'editor-styles' );

// Enqueue editor styles.
add_editor_style( 'editor-styles.css' );

/**
* Register color palette for Gutenberg editor.
*/
require get_stylesheet_directory() . '/inc/colors.php';

/**
* Créer des shortcodes pour afficher les horaires d'ouverture du parcours.
*/
include_once get_stylesheet_directory() . '/inc/horaires.php';

/**
* Créer des shortcodes pour afficher les inscriptions aux tournois du dimanche.
*/
include_once get_stylesheet_directory() . '/inc/inscriptions-tournoi.php';


/**
* ******** ACF custom blocks
*/
/*
function exp_block_categories( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug' => 'exp',
				'title' => 'Discgolf',
				'icon'  => 'dashicons-hourglass',
			),
		),
		$categories
	);
}
add_filter( 'block_categories', 'exp_block_categories', 10, 2 );*/

//require_once( 'inc/blocks/acf-block-articles-accueil.php' );


/**
* ******** Add ACF option page
*/


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Réglages du site Discgolf Mimizan',
		'menu_title'	=> 'Réglages Disc Golf Mimizan',
		'menu_slug' 	=> 'discgolf-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => 'dashicons-sticky',
		'position' 		=>2
	));
	
}

/**
* ******** Gutenberg patterns
https://developer.wordpress.org/block-editor/developers/block-api/block-patterns/
https://codebeautify.org/json-escape-unescape
*/

register_block_pattern_category(
	'discgolf',
	array( 'label' => 'Discgolf' )
);

//require_once( 'inc/patterns/prestation.php' );

/**
* ******** Filtre titre pages archives
*/
add_filter( 'get_the_archive_title', function ($title) {    
	if ( is_category() ) {    
			$title = single_cat_title( '', false );    
		} elseif ( is_tag() ) {    
			$title = single_tag_title( '', false );    
		} elseif ( is_author() ) {    
			$title = '<span class="vcard">' . get_the_author() . '</span>' ;    
		} elseif ( is_tax() ) { //for custom post types
			$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title( '', false );
		}
	return $title;    
});
add_filter( 'get_the_archive_description', function ($description) {    
	if ( is_category() ) {    
		$description=wpautop($description);
		} 
	return $description;    
});


add_action( 'after_setup_theme', function() {
	add_theme_support( 'responsive-embeds' );
} );

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart',30);