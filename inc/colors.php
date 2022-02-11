<?php
/**
* Register Custom color palette for Gutenberg editor
*
* Should be the colors from css/colors.css.
*
* @package expertempo
*/
add_action( 'after_setup_theme', 'exp_gutenberg_color_palette',100);
function exp_gutenberg_color_palette() {
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  =>'Vert foncé #1A615E ',
			'slug'  => 'vert1',
			'color'	=> '#1A615E',
		),
		array(
			'name'  =>'Vert #2A9D8F',
			'slug'  => 'vert2',
			'color'	=> '#2A9D8F',
		),
		array(
			'name'  =>'Vert clair #e9f5f4',
			'slug'  => 'vert3',
			'color'	=> '#e9f5f4',
		),
		array(
			'name'  =>'Gris',
			'slug'  => 'gris',
			'color'	=> '#444',
		),
		array(
			'name'  =>'Blanc',
			'slug'  => 'blanc',
			'color'	=> '#ffffff',
		),
		array(
			'name'  =>'Noir',
			'slug'  => 'noir',
			'color'	=> '#000',
		),
	));
}
