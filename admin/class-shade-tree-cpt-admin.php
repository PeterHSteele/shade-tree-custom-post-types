<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shade_Tree_CPT
 * @subpackage Shade_Tree_CPT/admin
 * @author     Your Name <email@example.com>
 */
class Shade_Tree_CPT_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $shade_tree_cpt    The ID of this plugin.
	 */
	private $shade_tree_cpt;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The textdomain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The textdomain of this plugin.
	 */
	private $textdomain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $shade_tree_cpt       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	 public $debug_tags;
	public function __construct( $shade_tree_cpt, $version ) {

		$this->shade_tree_cpt = $shade_tree_cpt;
		$this->version = $version;
		$this->textdomain = $shade_tree_cpt;
		$this->debug_tags=array();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shade_Tree_CPT_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shade_Tree_CPT_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->shade_tree_cpt, plugin_dir_url( __FILE__ ) . 'css/shade-tree-cpt-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shade_Tree_CPT_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shade_Tree_CPT_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->shade_tree_cpt, plugin_dir_url( __FILE__ ) . 'js/shade-tree-cpt-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_settings_page(){
		add_options_page(
			esc_html__('Shade Tree CPT Settings', $this->textdomain), 
			esc_html__('Shade Tree CPT', $this->textdomain), 
			'manage_options',
			'shade-tree-settings-page',
			array($this, 'output_settings_page')
		);
	}

	public function output_settings_page(){
		?>
			<h1><?php esc_html_e( 'Custom Post Type Configuration', $this->textdomain ) ?></h1>
		<?php
	}

	public function settings_init(){
		register_setting('shade-tree-settings-page', 'shade-tree-settings', array(
			'type' => 'array',
			'sanitize_callback' => array($this, 'sanitize_settings'),
			'description' => esc_html__('Settings for the Shade Tree CPT Plugin', $this->textdomain),
			'default' => array()
		));

		add_settings_section(
			'shade-tree-current-post-types',
			esc_html__('Current Post Types', $this->textdomain),
			//array($this, 'output_current_post_types_settings_section_description'),
			'__return_false',
			'shade-tree-settings-page',
		);

		add_settings_field(
			'add-post-type',
			esc_html__('Add Post Type', $this->textdomain),
			array($this, 'output_add_post_type_field'),
			'shade-tree-settings-page',
			'shade-tree-current-post-types',
			array(
				'label_for' => 'add-post-type-input',
				'class' => 'shade-tree-add-post-field'
			)
		);
	}

	public function output_current_post_types_settings_section_description( $args ){
	
	}

	public function output_add_post_type_field(){
		include( plugin_dir_path( __FILE__ ) . 'partials/shade-tree-add-posts-field.php');
	}

	public function sanitize_settings(){

	}

	public function add_admin_page(){
		add_menu_page( 
			__('Shade Tree CPT', $this->textdomain), 
			__('Shade Tree CPT', $this->textdomain), 
			'install_plugins', 
			'shade-tree-cpt-admin-page', 
			array($this, 'load_admin_page' ) );
	}

	public function load_admin_page(){
		$post_types = get_option('shade-tree-cpt-post-types');
		require_once plugin_dir_path( __FILE__ ) . 'partials/shade-tree-cpt-admin-display.php';
	}

	/*public function register_query_vars( $vars ){
		$vars[]='stcpt_deleted';
		var_dump( 'stcpt deleted is in query vars: ' . in_array( 'stcpt_deleted', $vars ));	
		return $vars;
	}*/

	public function handle_delete_post_type(){
		/* Check the nonce and user privileges */
		if ( !isset($_POST['shade_tree_delete_type']) || !wp_verify_nonce( $_POST['shade_tree_delete_type'], 'Shade Tree delete post type') ){
			wp_die( esc_html__('Invalid nonce'), $this->textdomain ); 
		}

		if ( !current_user_can( 'manage_options' ) ){
			wp_die( esc_html__('You have insufficient privileges to delete this post type.', $this->textdomain));
		}

		//get the key of the post type to delete from user input
		$key_to_delete = $_POST['delete-type']['key'];
		//get the registered custom post types
		$post_types = get_option( 'shade-tree-cpt-post-types', array());

		//plugin page url. Redirect here when done deleting.
		$url = admin_url( 'admin.php?page=shade-tree-cpt-admin-page' );
		
		//if the user's type is registered, delete it
		if ( isset( $post_types[$key_to_delete] ) ){
			//check for deep delete
			if ( isset($_POST['deep-delete'])){
				$rows_deleted = $this->handle_deep_delete( $key_to_delete );

				//add number db records to url so we can generate a notice
				$url = add_query_arg( 'stcpt_deleted', $rows_deleted, $url );
			}
			
			unregister_post_type( $key_to_delete );
			
			//remove from list of post types registered by this plugin
			unset($post_types[$key_to_delete]);

			//add deleted type to $url generate a notice
			$url = add_query_arg( 'stcpt_deleted_type', $key_to_delete, $url );

		}

		//update wp_options
		update_option('shade-tree-cpt-post-types', $post_types );
		
		//if it's a deep delete, pass number of deleted rows to the plugin page as a query arg so we can generate a notice
		wp_redirect( $url );
	}

	public function handle_deep_delete( $post_type_key ){
		global $wpdb;

		$query = "DELETE a,b,c
    FROM {$wpdb->posts} a
    LEFT JOIN {$wpdb->term_relationships} b
        ON (a.ID = b.object_id)
    LEFT JOIN {$wpdb->postmeta} c
        ON (a.ID = c.post_id)
    WHERE a.post_type = %s;";
		
		$sql = $wpdb->prepare( $query, $post_type_key );
		return $wpdb->query( $sql );
		
	}

	public function show_notice(){
		if ( 
			! isset($_REQUEST['stcpt_deleted_type']) ||
			'toplevel_page_shade-tree-cpt-admin-page' != get_current_screen()->id 
		){ 
			return; 
		}

		?>
		<div class="notice notice-success">
			<?php
			if ( isset($_REQUEST['stcpt_deleted']) ){ 
				printf( 
					/* translators: %1$s: Number of deleted posts. %2$s: number of deleted database records */
					__( 'Removed %1$s post type and %2$s related records.', $this->textdomain ), 
					'<span style="font-weight: bold;">' . esc_html( $_REQUEST[ 'stcpt_deleted_type' ] ) . '</span>',
					esc_html( $_REQUEST[ 'stcpt_deleted'])
				);
			} else {
				printf( 
					/* translators: %1$s: Number of deleted posts. */
					__( 'Removed %1$s posts type.', $this->textdomain ), 
					'<span style="font-weight: bold;">' . esc_html( $_REQUEST[ 'stcpt_deleted_type' ] ) . '</span>'
				);
			} 
			?>
		</div>
		<?php 
	}

  public function success_notice(){
    /* prints success notice when post is added */
    if ( 
      !isset( $_REQUEST[ 'stcpt_added' ] ) ||
      'toplevel_page_shade-tree-cpt-admin-page' != get_current_screen()->id 
    ) {
      return;
    }

    ?>
    <div class="notice notice-success">
      <?php 
        printf(
          /* translators: %1$s: name of post type */
          __( 'Successfully added %1$s post type', $this->textdomain ),
          '<span style="font-weight: bold;">' . $_REQUEST[ 'stcpt_added' ] . '</span>'
        )
      ?>
    </div>
    <?php
  }

	public function handle_edit_post_types_submission(){
		if (! isset($_POST['ringo_starr']) || ! wp_verify_nonce($_POST['ringo_starr'], 'edit custom post types') ){
			wp_die(esc_html__('nonce invalid', $this->$textdomain));
		}

		if (! isset($_POST['action']) || 'edit_post_types' != $_POST['action'] ){
			wp_redirect( admin_url() );
			wp_die(esc_html__('There\'s been an error.', $this->textdomain ));
		}

		if (! current_user_can('manage_options')){
			wp_die(esc_html__('You\'re security clearance isn\'t high enough', $this->textdomain ));
		}
		
		/* sanitize the user input */
		$sanitized = $this->sanitize_post_types( $_POST['edit-post-types'] );

		/*
		grab a reference to the post type key,
		which will also become a key in the array of 
		registered post types in wp_options.
		*/
		$key = $sanitized['key'];

		/* get already added post types from database */
		$post_types=get_option('shade-tree-cpt-post-types', array());

    /* url for main plugin page. Will redirect here after db update */
    $url = admin_url( 'admin.php?page=shade-tree-cpt-admin-page' );

		/* make sure the post type user wants to register isn't aready registered */
		if ( !isset($post_types[$key]) ){
			$post_types[$key]=$sanitized['data'];
			update_option('shade-tree-cpt-post-types',  $post_types);
      
      /* add post type to query string so we can create a success notice */
      $url = add_query_arg( 'stcpt_added', $sanitized['data']['name'], $url );
		}

		wp_redirect( $url );
	}

	public function sanitize_post_types( $data ){

		if ( empty($data['key']) ){
			if ( !empty($data['name']) ){
				$data['key'] = $data['name'];
			} else {
				wp_die(
					esc_html__('You must provide a valid post type key', $this->textdomain )
				);
			}
		}

		if (20<strlen( $data['key'] ) || preg_match( '/[!@#$%^&*)(]/', $data['key'] )){
			wp_die(
				sprintf(
					esc_html__('%1$s is not a valid post type key', $this->textdomain),
					$singular
				)
			);
		}

		/*sanitize the post type key with sanitize key. */
		$key = sanitize_key($data['key']);
		unset( $data['key'] );
		
		/*All the other data can be sanitized with sanitize text field*/
		array_walk( $data, 'sanitize_text_field' );

		return array('key' => $key, 'data' => $data);
	}

	public function register_types(){
		$post_types = get_option('shade-tree-cpt-post-types', array());
		//var_dump($post_types);
		foreach( $post_types as $key => $data){
			$this->register_type( $key, $data );
		}
	}

	public function capitalize($str){
		$letters=str_split($str);
		$upper_first=strtoupper($letters[0]);
		array_shift($letters);
		return $upper_first . implode('',$letters);
	}

	public function register_type($key, $data){
		$capSingular = $this->capitalize($data['singular_name']);
		$capPlural = $this->capitalize($data['name']);

		$labels = array(
			'name'                  => $capPlural,
			'singular_name'         => $capSingular,
			'menu_name'             => $capPlural,
			'name_admin_bar'        => $capSingular,
			'add_new'               => __( 'Add New', $this->textdomain ),
			'add_new_item'          => sprintf( esc_html__( 'Add New %1$s', $this->textdomain ), $capSingular),
			'new_item'              => sprintf( esc_html__( 'New %1$s', $this->textdomain ), $capSingular),
			'edit_item'             => sprintf( esc_html__( 'Edit %1$s', $this->textdomain ), $capSingular ),
			'view_item'             => sprintf( esc_html__('View %1$s', $this->textdomain ), $capSingular),
			'all_items'             => sprintf( esc_html__( 'All %1$s', $this->textdomain ), $capPlural),
			'search_items'          => sprintf( esc_html__( 'Search %1$s', $this->textdomain ), $capPlural),
			'parent_item_colon'     => sprintf( esc_html__('Parent %1$s:', $this->textdomain ), $capPlural),
			'not_found'             => sprintf( esc_html__( 'No %1$s found.', $this->textdomain ), $capPlural),
			'not_found_in_trash'    => sprintf( esc_html__( 'No %1$s found in Trash.', $this->textdomain ), $capPlural),
			'archives'              => sprintf( esc_html_x( '%1$s archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', $this->textdomain ), $capSingular),
			'insert_into_item'      => sprintf( esc_html_x( 'Insert into %1$s', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', $this->textdomain ), $capSingular ),
			'uploaded_to_this_item' => sprintf( esc_html_x( 'Uploaded to this %1$s', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', $this->textdomain ), $capSingular),
			'filter_items_list'     => sprintf( esc_html_x( 'Filter %1$s list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', $this->textdomain ), $capPlural),
			'items_list_navigation' => sprintf( esc_html_x( '%1$s list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', $this->textdomain ), $capPlural),
			'items_list'            => sprintf( esc_html_x( '%1$s list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', $this->textdomain ), $capPlural),
		);

		$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $capSingular ),
				'rewrite'						 => false,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'page-attributes', 'custom-fields' ),
		);

		register_post_type( $key, $args );
	}

}