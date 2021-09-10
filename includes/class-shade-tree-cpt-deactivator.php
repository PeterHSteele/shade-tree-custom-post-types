<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/includes
 * @author     Your Name <email@example.com>
 */
class Shade_Tree_CPT_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;

		$sql_episode = $wpdb->prepare( self::gen_query(), 'episodes' );
		//$sql_drama = $wpdb->prepare( gen_query(), 'drama');
		//var_dump($sql_episode);

		$episodes_rows_deleted = $wpdb->query( $sql_episode );
		//$drama_rows_deleted = $wpdb->query( $sql_drama );

		//unregister_post_type( 'Drama' );
		unregister_post_type( 'episodes' );

		var_dump('episode rows deleted:' . $episodes_rows_deleted);
	}

	public static function gen_query(){
		$query = 'Delete from wp_posts where post_type = %s;';
		$query .= 'DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);';
		$query .= 'DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts);';
		return $query;
	}

}
