<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/includes
 * @author     Your Name <email@example.com>
 */
class Shade_Tree_CPT_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if (! current_user_can('activate_plugins') ){
			return;
		}

		add_option('shade-tree-cpt-post-types',array());
	}

}
