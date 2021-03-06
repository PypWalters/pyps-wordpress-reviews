<?php
/**
 *
 * The user interface to add a star review
 * @since 1.0
 */


/**
 * Pyp_Reviews_Create_Rating Class.
 *
 */
class Pyp_Reviews_Create_Rating {

	/**
	 * Contains the instance of the class
	 *
	 * @since 1.0
	 * @access private
	 * @var static
	 */
	private static $instance = null;

	/**
	 * Holds the values to be used in the fields callbacks
	 *
	 * @since 1.0
	 * @access private
	 */
	private $options = null;


	/**
	 * Class constructor.
	 *
	 * Left empty in purpose. This class uses the init method.
	 *
	 * @since 1.0
	 * @access private
	 *
	 * @return null
	 */
	private function __construct() {}

	/**
	 * Creates and instance of this class.
	 *
	 * @since 1.0
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Hooks up the functions
	 *
	 * @since 1.0
	 */
	public function hooks() {
		add_action( 'comment_form_before_fields', array( $this, 'pyp_add_rating_field' ) );
		add_action( 'comment_form_logged_in_after', array( $this, 'pyp_add_rating_field' ) );
		add_action( 'comment_post', array( $this, 'pyp_save_comment_meta_data' ) );
	}

	/**
	 * Get the post type of the current page and see if it matches any post
	 * types saved in 'pyp_review_options'
	 *
	 * @return boolean
	 */
	public function get_review_options() {
		$this->options = get_option( 'pyp_review_options' );
		if ( isset( $this->options['post_type'] ) && in_array( get_post_type(), $this->options['post_type'], true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Displays the radio butons in the comment form for the rating
	 *
	 * @since 1.0
	 */
	public function pyp_add_rating_field() {
		global $post;
		if ( $this->get_review_options() ) :
		?>
		<div id="comment-form-rating">
			<label>Your Rating for:</label>
			<div class="star-holder">
		    <input type="radio" class="rating-input" id="pyp-rating-5" name="pyp-rating-input" value="5">
			  <label for="pyp-rating-5" class="rating-star"></label>
			  <input type="radio" class="rating-input" id="pyp-rating-4" name="pyp-rating-input" value="4" checked="checked">
			  <label for="pyp-rating-4" class="rating-star"></label>
			  <input type="radio" class="rating-input" id="pyp-rating-3" name="pyp-rating-input" value="3">
			  <label for="pyp-rating-3" class="rating-star"></label>
			  <input type="radio" class="rating-input" id="pyp-rating-2" name="pyp-rating-input" value="2">
			  <label for="pyp-rating-2" class="rating-star"></label>
			  <input type="radio" class="rating-input" id="pyp-rating-1" name="pyp-rating-input" value="1">
			  <label for="pyp-rating-1" class="rating-star"></label>
			</div>
		<?php wp_nonce_field( 'create_review', '_pyp_review_nonce' ); ?>
		</div>
		<?php
		endif;
	}

	/**
	 * Cleans and saves the review to the database as meta data
	 *
	 * @since 1.0
	 */
	public function pyp_save_comment_meta_data( $comment_id ) {
		//safety first
		$retrieved_nonce = $_REQUEST['_pyp_review_nonce'];
		if ( isset( $_POST['pyp-rating-input'] ) && wp_verify_nonce( $retrieved_nonce, 'create_review' ) ) {

			if ( is_int( intval( $_POST['pyp-rating-input'] ) ) && 0 < intval( $_POST['pyp-rating-input'] ) ) {
				$clean_rating = intval( $_POST['pyp-rating-input'] );
				add_comment_meta( $comment_id, 'pyp_rating', $clean_rating );
			}
		}
	}

} //end class
