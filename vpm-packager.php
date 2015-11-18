<?php
/*
Plugin Name: VPM Packager
Plugin URI: https://www.vanpattenmedia.com/
Description: Set up your own Composer repo on a WordPress site
Version: 0.9
Author: Chris Van Patten
Author URI: https://www.vanpattenmedia.com/
Text Domain: vpm
*/

class VpmPackager {

	function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'init', array( $this, 'add_rewrite_rule' ) );
		add_filter( 'query_vars', array( $this, 'handle_custom_query_vars' ) );
		add_filter( 'template_include', array( $this, 'load_template' ) );
	}

	function register_post_type() {
		if ( !post_type_exists( 'packages' ) ) {
			$label_singular = 'Package';
			$label_plural   = 'Packages';

			register_post_type(
				'packages',
				array(
					'label'           => $label_plural,
					'description'     => '',
					'public'          => true,
					'show_ui'         => true,
					'show_in_menu'    => true,
					'capability_type' => 'page',
					'hierarchical'    => true,
					'query_var'       => true,
					'has_archive'     => true,
					'menu_icon'       => 'dashicons-archive',
					'rewrite' => array(
						'slug'       => 'package',
						'with_front' => false,
					),
					'supports' => array(
						'title',
						'custom-fields',
					),
					'labels' => array (
						'name'               => $label_plural,
						'singular_name'      => $label_singular,
						'menu_name'          => $label_plural,
						'add_new'            => 'Add New',
						'add_new_item'       => 'Add New ' . $label_singular,
						'edit'               => 'Edit',
						'edit_item'          => 'Edit ' . $label_singular,
						'new_item'           => 'New ' . $label_singular,
						'view'               => 'View ' . $label_singular,
						'view_item'          => 'View ' . $label_singular,
						'search_items'       => 'Search ' . $label_plural,
						'not_found'          => 'No ' . $label_plural . ' Found',
						'not_found_in_trash' => 'No ' . $label_plural . ' Found in Trash',
						'parent'             => 'Parent ' . $label_singular,
					)
				)
			);
		}
	}

	function add_rewrite_rule() {
		add_rewrite_rule( 'packages.json', 'index.php?vpmpackager=render', 'top' );
	}

	function handle_custom_query_vars( $query_vars ) {
		$query_vars[] = 'vpmpackager';
		return $query_vars;
	}

	function render() {
		$args = array(
			'post_type'      => 'packages',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);

		$packages = new WP_Query( $args );

		if ( $packages->have_posts() ) {

			while ( $packages->have_posts() ) {
				$packages->the_post();

				$pkg_versions = array();
				$versions     = CFS()->get('versions');

				$pkg_name = CFS()->get('name');
				$pkg_type = CFS()->get('type');

				foreach ( $versions as $version ) {
					if ( empty( $version['version'] ) || empty( $version['file'] ) )
						continue;

					$pkg_version = $version['version'];
					$pkg_file    = wp_get_attachment_url( $version['file'] );

					$pkg_versions[ $pkg_version ] = array(
						'name'     => $pkg_name,
						'version'  => $pkg_version,
						'dist'     => array(
							'url'  => $pkg_file,
							'type' => 'zip',
						),
						'type'     => $pkg_type,
						'require'  => array(
							'composer/installers' => '~1.0',
						),
					);
				}

				$pkg[ $pkg_name ] = $pkg_versions;
			}

			$arr = array(
				'packages' => $pkg
			);

			header('Content-Type: application/json');
			echo json_encode( $arr, JSON_FORCE_OBJECT );
		}
	}


	function load_template( $template ) {
		global $wp_query;

		if ( $wp_query->query_vars['vpmpackager'] == 'render' ) {
			$this->render();
		}

		return $template;
	}
}

new VpmPackager;
