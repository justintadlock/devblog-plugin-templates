<?php
/**
 * Plugin Name:       Developer Blog: Plugin Templates
 * Plugin URI:        https://github.com/wptrainingteam/devblog-plugin-templates
 * Description:       Example code for registering plugin block templates with WordPress 6.7+.
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Text Domain:       devblog-plugin-templates
 */

add_action( 'init', 'devblog_register_plugin_templates' );

function devblog_register_plugin_templates() {

	// Post template
	wp_register_block_template( 'devblog-plugin-templates//single-canvas', [
		'title'       => __( 'Single: Canvas', 'devblog-plugin-templates' ),
		'description' => __( 'An open template for use with single posts. Includes the Header, Post Content, and Footer.', 'devblog-plugin-templates' ),
		'post_types'  => [ 'post' ],
		'content'     => devblog_get_template_content( 'single-canvas.php' )
	] );

	// Virtual template
	wp_register_block_template( 'devblog-plugin-templates//all-categories', [
		'title'       => __( 'All Categories', 'devblog-plugin-templates' ),
		'description' => __( 'Displays a list of all categories.', 'devblog-plugin-templates' ),
		'content'     => devblog_get_template_content( 'all-categories.php' )
	] );

	// Step 3: Cleaner Way - Example template.
	wp_register_block_template( 'devblog-plugin-templates//example', [
		'title'       => __( 'Example', 'devblog-plugin-templates' ),
		'description' => __( 'An example block template from a plugin.', 'devblog-plugin-templates' ),
		'content'     => devblog_get_template_content( 'example.php' )
	] );

	// Step 2: Messy way:
	/*
	wp_register_block_template( 'devblog-plugin-templates//example', [
		'title'       => __( 'Example', 'devblog-plugin-templates' ),
		'description' => __( 'An example block template from a plugin.', 'devblog-plugin-templates' ),
		'content'     => '
			<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->
			<!-- wp:group {"tagName":"main"} -->
			<main class="wp-block-group">
				<!-- wp:group {"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph --><p>This is a plugin-registered template.</p><!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</main>
			<!-- /wp:group -->
			<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->'
	] );
	*/

	// Step 1: Register with no content (blank template):
	/*
	wp_register_block_template( 'devblog-plugin-templates//example', [
		'title'       => __( 'Example', 'devblog-plugin-templates' ),
		'description' => __( 'An example block template from a plugin.', 'devblog-plugin-templates' )
	] );
	*/
}

function devblog_get_template_content( $template ) {
	ob_start();
	include __DIR__ . "/templates/{$template}";
	return ob_get_clean();
}

// Filter for handling virtual template.
add_filter( 'template_include', 'devblog_template_include' );

function devblog_template_include( $template ) {
	if (
		! isset( $_GET['all-categories'] )
		|| 1 !== absint( $_GET['all-categories'] )
	) {
		return $template;
	}

	// Create a hierarchy of templates.
	$templates = [
		'all-categories.php',
		'index.php'
	];

	// First, search for PHP templates, which block themes can also use.
	$template = locate_template( $templates );

	// Pass the result into the block template locator and let it figure
	// out whether block templates are supported and this template exists.
	$template = locate_block_template( $template, 'all-categories', $templates );

	return $template;
}
