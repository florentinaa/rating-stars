<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.florentinasultan.co.uk
 * @since      1.0.0
 *
 * @package    Rating_Stars
 * @subpackage Rating_Stars/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rating_Stars
 * @subpackage Rating_Stars/admin
 * @author     Florentina <florentina@florentinasultan.co.uk>
 */
class Rating_Stars_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rating_stars    The ID of this plugin.
	 */
	private $rating_stars;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $rating_stars       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $rating_stars, $version ) {

		$this->rating_stars = $rating_stars;
		$this->version = $version;
		add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);
		add_action('admin_init', array( $this, 'registerAndBuildFields' ));
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
		 * defined in Rating_Stars_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rating_Stars_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->rating_stars, plugin_dir_url( __FILE__ ) . 'css/rating-stars-admin.css', array(), $this->version, 'all' );

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
		 * defined in Rating_Stars_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rating_Stars_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->rating_stars, plugin_dir_url( __FILE__ ) . 'js/rating-stars-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function addPluginAdminMenu() {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(  $this->rating_stars, 'Rating Stars', 'administrator', $this->rating_stars, array( $this, 'displayPluginAdminDashboard' ), 'dashicons-chart-area', 26 );
		
		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		// add_submenu_page( $this->rating_stars, 'Plugin Name Settings', 'Settings', 'administrator', $this->rating_stars.'-settings', array( $this, 'displayPluginAdminSettings' ));
	}

	public function displayPluginAdminDashboard() {
		require_once 'partials/'.$this->rating_stars.'-admin-display.php';
  	}

	// public function displayPluginAdminSettings() {
	// 	// set this var to be used in the settings-display view
	// 	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
	// 	if(isset($_GET['error_message'])){
	// 		add_action('admin_notices', array($this,'pluginNameSettingsMessages'));
	// 		do_action( 'admin_notices', $_GET['error_message'] );
	// 	}
	// 	require_once 'partials/'.$this->rating_stars.'-admin-settings-display.php';
	// }

	public function pluginNameSettingsMessages($error_message){
		switch ($error_message) {
			case '1':
				$message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'my-text-domain' );                 
				$err_code = esc_attr( 'rating_stars__setting' );                 
				$setting_field = 'rating_stars__setting';                 
				break;
		}
		$type = 'error';
		add_settings_error(
			   $setting_field,
			   $err_code,
			   $message,
			   $type
		   );
	}

	public function registerAndBuildFields() {
		/**
	   * First, we add_settings_section. This is necessary since all future settings must belong to one.
	   * Second, add_settings_field
	   * Third, register_setting
	   */     
		add_settings_section(
			// ID used to identify this section and with which to register options
			'rating_stars_general_section', 
			// Title to be displayed on the administration page
			'',  
			// Callback used to render the description of the section
			array( $this, 'rating_stars_display_general_account' ),    
			// Page on which to add this section of options
			'rating_stars_general_settings'                   
		);
		unset($args);
		$args = array (
					'type' => 'select',
					'id' => 'rating_stars__setting',
					'name' => 'rating_stars__setting',
					'get_options_list' => '',
					'value_type'=>'normal',
					'wp_data' => 'option'
				);
		add_settings_field(
			'rating_stars__setting',
			'Select Post Type',
			array( $this, 'rating_stars_render_settings_field' ),
			'rating_stars_general_settings',
			'rating_stars_general_section',
			$args
		);


		register_setting(
				'rating_stars_general_settings',
				'rating_stars__setting'
				);

	}

	public function rating_stars_display_general_account() {
		echo '<p>These settings apply to all Plugin Name functionality.</p>';
	}
		
	public function rating_stars_render_settings_field($args) {  
		if($args['wp_data'] == 'option'){
			$wp_data_value = get_option($args['name']);
		} elseif($args['wp_data'] == 'post_meta'){
			$wp_data_value = get_post_meta($args['post_id'], $args['name'], true );
		}
		$argms = array(
			'public'   => true
		 );
	 
		$output = 'names'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'
	 
		$taxonomies = get_post_types( $argms, $output, $operator );
		$post_type = '';
		
		$options = get_option( 'rating_stars_general_settings' );
		switch ($args['type']) {
			
			case 'select':
				
				foreach ($taxonomies as $taxonomy) {
					$post_type .= '<option value="' . $taxonomy . '">' . $taxonomy . '</option>';
				}
				echo '<select>' . $post_type . '</select>';
				break;
			default:
				# code...
				break;
		}
		}
}
