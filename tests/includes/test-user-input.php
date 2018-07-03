<?php

/**
 * Pyp_Reviews_Create_Rating_Test
 * @group frontend
 */

//use Pyp_Reviews_Create_Rating;

class Pyp_Reviews_Create_Rating_Test extends WP_UnitTestCase {

	/** @test */
	public function test_pyp_save_comment_meta_data_w_good_input() {
		//instantiate the class
		$create_rating_class = new Pyp_Reviews_Create_Rating;

		//create and set the user
		$commenter = $this->factory->user->create( array( 'role' => 'subscriber' ) );
		wp_set_current_user( $commenter );

		//create the comment
		$comment_id = $this->factory->comment->create();

		//create and set the nonce
		$_REQUEST['_pyp_review_nonce'] = wp_create_nonce( 'create_review' );

		//create comment options (good, evil, empty)
		$_POST['pyp-rating-input'] = 4;

		//run it
		$create_rating_class->pyp_save_comment_meta_data( $comment_id );

		$this->assertTrue( '4' === get_comment_meta( $comment_id, 'pyp_rating', true ) );
	}
}
