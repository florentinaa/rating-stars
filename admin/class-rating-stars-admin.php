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
		// add_action('product_edit_form_fields', array( $this, 'edit_rating_field' ));
		add_action( 'add_meta_boxes',  array( $this, 'rating_add_custom_box') );
			//Add rating to admin taxonomy
		add_action( 'save_post_wporg_product',  array( $this, 'update_rating_field'));
		add_action( 'save_post', array( $this, 'show_rating_option_save'));
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
		wp_enqueue_style(
			'font-awesome',
			"//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
		);
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

	public function rating_add_custom_box(){
		$args = array(
			'public'   => true,
			'_builtin' => false,
		 );
	 
		$output = 'names'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'
		$post_types = [ 'post', 'page' ];
		array_push($post_types, get_post_types( $args, $output, $operator ));
		foreach ($post_types as $post_type ) {
			add_meta_box(
				'rating_box_id',                 // Unique ID
				'Rating Stars',      // Box title
				array( $this, 'add_post_rating_field' ),  // Content callback, must be of type callable
				$post_types                            // Post type
			);
		}
	}

	public function add_post_rating_field($post){
		$post_id = $post->ID;
		$rating_0_5star = get_post_meta($post_id, 'votes-0.5', true) ? get_post_meta($post_id, 'votes-0.5', true) : 0;
		$rating_1star = get_post_meta($post_id, 'votes-1', true) ? get_post_meta($post_id, 'votes-1', true) : 0;
		$rating_1_5star = get_post_meta($post_id, 'votes-1.5', true) ? get_post_meta($post_id, 'votes-1.5', true) : 0;
		$rating_2stars = get_post_meta($post_id, 'votes-2', true) ? get_post_meta($post_id, 'votes-2', true) : 0;
		$rating_2_5star = get_post_meta($post_id, 'votes-2.5', true) ? get_post_meta($post_id, 'votes-2.5', true) : 0;
		$rating_3stars = get_post_meta($post_id, 'votes-3', true) ? get_post_meta($post_id, 'votes-3', true) : 0;
		$rating_3_5star = get_post_meta($post_id, 'votes-3.5', true) ? get_post_meta($post_id, 'votes-3.5', true) : 0;
		$rating_4stars = get_post_meta($post_id, 'votes-4', true) ? get_post_meta($post_id, 'votes-4', true) : 0;
		$rating_4_5star = get_post_meta($post_id, 'votes-4.5', true) ? get_post_meta($post_id, 'votes-4.5', true) : 0;
		$rating_5stars = get_post_meta($post_id, 'votes-5', true) ? get_post_meta($post_id, 'votes-5', true) : 0;
		$ratings = [
			"0.5" => $rating_0_5star, 
			"1" => $rating_1star, 
			"1.5" => $rating_1_5star, 
			"2" => $rating_2stars,
			"2.5" => $rating_2_5star,
			"3" => $rating_3stars,
			"3.5" => $rating_3_5star,
			"4" => $rating_4stars, 
			"4.5" => $rating_4_5star,
			"5" => $rating_5stars
		];
		$avg = 0;
		$no_votes = 0;
		
		foreach ($ratings as $index => $rating){
			$avg += $index*$rating;
			$no_votes += $rating;
		}
		$stars_avg = $no_votes ? $avg/$no_votes : $avg;
		$starClasses = ['fa fa-star-o', 'fa fa-star-half-o', 'fa fa-star'];
									
		printf(
			'<script>window.starClasses = %s</script>',
			json_encode($starClasses)
		);

		$values = get_post_meta( $post_id );
		$show_rating_stars_check = isset( $values['show_rating_stars_check'] ) && $values['show_rating_stars_check'][0] == 1? 1 : 0;
		wp_nonce_field( 'my_show_rating_stars_nonce', 'show_rating_nonce' );
		?>
		<p>
			<label>
				<input type="checkbox" name="show_rating_stars_check" id="show_rating_stars_check" value="1" <?php checked( $show_rating_stars_check, 1); ?> /><?= __('Display rating stars?'); ?>
			</label>
		</p>
		<?php 
			$rating_class = '';
		?>
		<?php
		if (isset($values['show_rating_stars_check'][0]) && $values['show_rating_stars_check'][0]==1): 
			$rating_class = 'show-rating';
		endif;
		?>
		<div class="rating-stars <?= $rating_class ?>">
			<div class="rating-container">
				<ul class="star-rating" id="rating-store">
					<?php for ($index = 1; $index <= 5; $index++) : ?>
						<?php if ($index <= $stars_avg) : ?>
							<li><i class="star-<?= $index ?> fa fa-star"></i></li>
						<?php elseif ($index - .5 == $stars_avg) : ?>
							<li><i class="star-<?= $index ?> fa fa-star-half-o"></i></li>
						<?php else : ?>
							<li><i class="star-<?= $index ?> fa fa-star-o"></i></li>
						<?php endif; ?>
					<?php endfor; ?>
				</ul>
				
				<input type="hidden" id="star-rating-hidden" data-allow-half="1" name="star-rating-hidden" value="<?= $stars_avg ?>">
				<?php if ($no_votes) {?>
				<div>(<?= $no_votes  ?> <?= __('Votes', 'rating-stars') ?>) </div>
				<?php } else { ?>
					<div>(<?= __('No rating yet', 'rating-stars') ?>) </div>
				<?php }; ?>
			</div>
			<?php for ($index = 1; $index <= 5; $index++) : ?>
				<ul class="star-rating"> <li class="first"><?= $index . __(' Star(s): ') ?></li>
				<?php foreach (range(1, $index) as $star) : ?>
					<li><i class="star-<?= $star ?> fa fa-star"></i></li>
				<?php endforeach; ?>
				<li class="last">
					<?php $votes = get_post_meta($post_id, 'votes-' .$index. '', true) ? get_post_meta($post_id, 'votes-' .$index. '', true) : 0;?>
					<?=  ' ' . $votes. __(' votes') ?><input type="number" name="votes-<?= $index ?>" id="votes-<?= $index ?>" value="<?= $votes ?>" max="100"/>
				</li>
				</ul>
			<?php endfor; ?>
		</div>
		<?php
	}

	public function show_rating_option_save( $post_id )
	{
		
		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['show_rating_nonce'] ) || !wp_verify_nonce( $_POST['show_rating_nonce'], 'my_show_rating_stars_nonce' ) ) return;
	
		$show_rating_stars_check = ($_POST["show_rating_stars_check"]==1) ? 1 : 0;
		update_post_meta($post_id, "show_rating_stars_check", $show_rating_stars_check);
	
	}

	public function update_rating_field( $post_id ){
		update_post_meta(
			$post_id,
			'votes-1',
			$_POST[ 'votes-1' ]
		);
		update_post_meta(
			$post_id,
			'votes-2',
			$_POST[ 'votes-2' ]
		);
		update_post_meta(
			$post_id,
			'votes-3',
			$_POST[ 'votes-3' ]
		);
		update_post_meta(
			$post_id,
			'votes-4',
			$_POST[ 'votes-4' ]
		);
		update_post_meta(
			$post_id,
			'votes-5',
			$_POST[ 'votes-5' ]
		);
	}
}
