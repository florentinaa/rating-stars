<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.florentinasultan.co.uk
 * @since      1.0.0
 *
 * @package    Rating_Stars
 * @subpackage Rating_Stars/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rating_Stars
 * @subpackage Rating_Stars/public
 * @author     Florentina <florentina@florentinasultan.co.uk>
 */
class Rating_Stars_Public {

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
	 * @param      string    $rating_stars       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $rating_stars, $version ) {

		$this->rating_stars = $rating_stars;
		$this->version = $version;

		add_action('wp_ajax_rating_ajax', array($this, 'rating_ajax'));
		add_action( 'wp_ajax_nopriv_rating_ajax', array($this, 'rating_ajax'));
		add_shortcode( 'rating-stars', array($this, 'add_rating_stars'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_enqueue_style(
			'font-awesome',
			"//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
		);

		wp_enqueue_style( $this->rating_stars, plugin_dir_url( __FILE__ ) . 'css/rating-stars-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->rating_stars, plugin_dir_url( __FILE__ ) . 'js/rating-stars-public.js', array( 'jquery' ), $this->version, false );
		$script_data_array = array(
			'url' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script(  $this->rating_stars , 'ajax_object', $script_data_array );
		wp_enqueue_script( $this->rating_stars );
	}

	public function rating_ajax() {
   
		$numbers_stars = floatval($_POST['stars']);
		$post_id = intval($_POST['post_id']);
		$numbers_votes = intval(get_term_meta($post_id, 'votes-'.$numbers_stars, true));
		$new_no = strval($numbers_votes+1);
	
		update_term_meta($post_id, 'votes-'.$numbers_stars, $new_no);

		// Don't forget to stop execution afterward.
		die();
	}	

	public function add_rating_stars( $content ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/rating-stars-public-display.php';
	}

}
