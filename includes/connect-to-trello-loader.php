<?php
/*
 * Connect to Trello
 * Loader Class
 */


class C2Trello_Loader {

	/*==========================MEMBERS=========================*/
	
	private $textdomain;

	/*==========================METHODS========================*/

	/*
	 * Constructor
	 */
	public function __construct( $textdomain ) {
		$this->textdomain = $textdomain;
	}

	/*
	 * Load Scripts
	 */
	public function load_scripts( $hook, $api_key ) {
		if( $hook == 'settings_page_c2trello_settings_page' || $hook == 'post.php' ) {

			$url = 'https://trello.com/1/client.js?key='.$api_key;
			wp_enqueue_script('c2trello-client-js', $url ,array('jquery'), true);
			
		}

		if( $hook == 'settings_page_c2trello_settings_page' ) {
			/*wp_enqueue_script('c2trello-settings-js', plugin_dir_url( __FILE__ ) . '/js/settings.js', array('c2trello-client-js', 'jquery'), true );*/


			wp_register_script('c2trello-settings-js', plugin_dir_url( __FILE__ ) . '/js/settings.js', array('c2trello-client-js', 'jquery'));

			$settings_translation = array(
				'loading' => __('Loading...', $this->textdomain),
				'new_item' => __('A new item', $this->textdomain),
				'select_a_board_first' => __('Select a board first.', $this->textdomain ),
				'not_connected' => __('Not connected.', $this->textdomain ),
				'not_authorized' => __('Not authorized.', $this->textdomain )
				);

			wp_localize_script('c2trello-settings-js', 'c2trello_settings_content', $settings_translation );

			wp_enqueue_script('c2trello-settings-js');

			wp_enqueue_style('c2trello-settings-style', plugin_dir_url( __FILE__ ). '/css/settings.css' );
		}

		if( $hook == 'post.php' ) {

			wp_register_script('c2trello-controls-js', plugin_dir_url( __FILE__ ) . '/js/controls.js', array('c2trello-client-js', 'jquery') );

			$controls_translation = array(
				'you_are_not_connected_to_trello' => __('You are not connected to Trello.', $this->textdomain),
				'this_post_has_been_added_to_trello' => __('This post has been added to your Trello.', $this->textdomain),
				'something_went_wrong' => __('Something went wrong. Please check your Trello Settings.', $this->textdomain)
				);

			wp_localize_script('c2trello-controls-js', 'c2trello_controls_content', $controls_translation );

			wp_enqueue_script('c2trello-controls-js');

		}

	}

}

?>