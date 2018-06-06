<?php
/**
 * Creates a class for the admin options page
 *
 * @since 1.0
 */

/**
 * s_Options Class.
 *
 * @since 1.0
 */
class Pyp_Reviews_Options {

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
	 * Contains the instance of the page
	 *
	 * @since 1.0
	 * @access private
	 */
	private $pyp_review_admin_page = null;

	/**
	 * Contains the available post types on a site
	 *
	 * @since 1.0
	 * @access private
	 */
	private $available_post_types = null;


	/**
	 * Class constructor.
	 *
	 * Left empty on purpose. This class uses the init method.
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
		 * Runs all hooks required for this class
		 *
		 * @since 1.0
		 *
		 * @return null
		 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ), 150 );
	}

		//Create the options page

		/**
		 * Add options page
		 */
	public function add_plugin_page() {
			// This page will be under "Settings"
			$this->pyp_review_admin_page = add_options_page(
				'Review Settings',
				'Review Settings',
				'manage_options',
				'pyp-review-settings',
				array( $this, 'create_admin_page' )
			);
	}

		/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'pyp_review_options' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Review Settings', 'pyp_review' ); ?></h1>
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'pyp_review_option_group' );
				do_settings_sections( 'pyp-review-setting-admin' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

		//Create the options on the options page
		/**
	 * Register and add settings
	 */
	public function page_init() {

		register_setting(
			'pyp_review_option_group', // Option group
			'pyp_review_options', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'pyp_review_setting_section_id', // ID
			'', // Title
			array( $this, 'print_section_info' ), // Callback
			'pyp-review-setting-admin' // Page
		);

		add_settings_field(
			'posts_selected', // ID
			__( 'Select the Post Types where you would like to enable reviews.', 'pyp_review' ), // Title
			array( $this, 'pyp_review_posts_callback' ), // Callback
			'pyp-review-setting-admin', // Page
			'pyp_review_setting_section_id' // Section
		);

	}

		/**
	 * Print the Section text
	 */
	public function print_section_info() {
		echo '<p>' . esc_html__( 'Enter your settings below:', 'pyp_review' ) . '</p>';
	}

		/**
		* Sanitize each setting field as needed
		*
		* @param array $input Contains all settings fields as array keys
		*/
	public function sanitize( $input ) {
		if ( check_admin_referer( 'pyp_review_option_group-options' ) && current_user_can( 'edit_posts' ) ) {
			return $input;
		} else {
			exit();
		}
	}

	/**
		* Render checkbox to see if users need to be logged in
		*/
	public function pyp_review_posts_callback() {

		$this->get_relevant_post_types();

		foreach ( $this->available_post_types as $pt_value => $pt_name ) {
			$checked = '';
					// let's see if this one is checked
			if ( ! empty( $this->options['post_type'] ) && in_array( $pt_value, $this->options['post_type'] ) ) {
					$checked = $pt_value;
			}

					$echo = false;

					echo '<p><label for="post_type_' . esc_attr( $pt_value ) . '">';
					echo '<input id="post_type_' . esc_attr( $pt_value ) . '" type="checkbox" name="pyp_review_options[post_type][]" value="' . esc_attr( $pt_value ) . '"' . checked( $checked, $pt_value, $echo ) . '> ' . esc_html( $pt_name );
					echo '</label></p>';

		}
		echo '<p>' . esc_html__( "Don't see the post type your are looking for? Make sure the post type is declared as a public post type." ) . '</p>';
	}

	/**
		* get a listing of public post types in a theme so we can use them as options
		*/
	public function get_relevant_post_types() {
		$post_type_args = array(
			'public' => true,
		);
		$this->available_post_types = get_post_types( $post_type_args, 'names', 'and' );
	}
}
