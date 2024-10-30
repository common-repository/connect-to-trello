<?php
/*
 * Connect to Trello
 * Settings Class
 */

class C2Trello_Settings {

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
	 * Register Settings
	 */
	public function register() {

		register_setting( 'c2trello_serialized_settings_group', 'c2trello_serialized_settings' );

		add_settings_section( 
			'api-settings', 
			__( 'Connect to Trello', $this->textdomain ),
			array( $this, 'api_settings'),
			'c2trello_settings_page'
			 );	

		add_settings_section( 
			'checklist-settings', 
			__( 'Create your Checklist', $this->textdomain ),
			array( $this, 'checklist_settings'),
			'c2trello_settings_page'
			 );	

		add_settings_field(
			'api-key', 
			__('API Key', $this->textdomain),
			array( $this, 'api_key'),
			'c2trello_settings_page',
			'api-settings'
			);

		add_settings_field(
			'board-id', 
			__('Board', $this->textdomain),
			array( $this, 'board_id'),
			'c2trello_settings_page',
			'api-settings'
			);

		add_settings_field(
			'list-id', 
			__('List', $this->textdomain),
			array( $this, 'list_id'),
			'c2trello_settings_page',
			'api-settings'
			);

		add_settings_field(
			'checklist-title', 
			__('Title', $this->textdomain),
			array( $this, 'checklist_title'),
			'c2trello_settings_page',
			'checklist-settings'
			);

		add_settings_field(
			'checklist-items', 
			__('Items', $this->textdomain),
			array( $this, 'checklist_items'),
			'c2trello_settings_page',
			'checklist-settings'
			);

	}

	/*
	 * Render
	 */
	public function render() {
		add_options_page(
			'Trello',
			'Trello',
			'manage_options',
			'c2trello_settings_page',
			array( $this, 'content' )
			);
	}

	/*
	 * Content
	 */
	public function content() {

		?>
		<div class="wrap">
			<h2><?php _e('Trello Settings', $this->textdomain); ?></h2>
			<div id="c2trello-notice"></div>
			<form action="options.php" method="POST">
				<?php settings_fields( 'c2trello_serialized_settings_group' ); ?>
				<?php do_settings_sections( 'c2trello_settings_page' ); ?>
				<?php submit_button( __( 'Save' , $this->textdomain) ); ?>
			</form>
		</div>

		<?php
	}

	/*
	 * Section: API Settings
	 */
	public function api_settings() {
		_e('Visit <a target="_blank" href="https://trello.com/">Trello</a> and make sure you are logged in. Then get your Trello key <a target="_blank" href="https://trello.com/app-key">here</a>, enter below and save. Once a valid key is stored, you need to give this plugin access to your Trello via a popup.', $this->textdomain);
	}

	/*
	 * Section: Checklist Settings
	 */
	public function checklist_settings() {
		_e('This checklist will be added to all new cards you generate from WordPress posts. Make sure to save your changes after you edit it.', $this->textdomain);
	}

	/*
	 * Field: API Key
	 */
	public function api_key() {

		$field = 'api-key';
		$value = $this->get_trello_option( $field );

		echo "<input type='text' name='c2trello_serialized_settings[$field]' value='$value' />";
	
	}

	/*
	 * Field: Board ID
	 */
	public function board_id() {

		$field = 'board-id';
		$value = $this->get_trello_option( $field );

		//invisible value container
		echo "<input type='hidden' id='$field' name='c2trello_serialized_settings[$field]' value='$value' />";

		//selector container
		echo "<div id='$field-selector'></div>";
	}

	/*
	 * Field: List ID
	 */
	public function list_id() {

		$field = 'list-id';
		$value = $this->get_trello_option( $field );

		//invisible value container
		echo "<input type='hidden' id='$field' name='c2trello_serialized_settings[$field]' value='$value' />";

		//selector container
		echo "<div id='$field-selector'></div>";
	}

	/*
	 * Field: Checklist Titel
	 */
	public function checklist_title() {

		$field = 'checklist-title';
		$value = $this->get_trello_option( $field );

		echo "<input type='text' name='c2trello_serialized_settings[$field]' value='$value' />";
	}

	/*
	 * Field: Checklist Items
	 */
	public function checklist_items() {

		$field = 'checklist-items';
		$value = $this->get_trello_option( $field );

		//invisible value container
		echo "<input type='hidden' id='$field' name='c2trello_serialized_settings[$field]' value='$value' />";

		//editor container
		echo "<div id='$field-editor'></div>";
	}

	/*
	 * Get Options Helper
	 */
	public function get_trello_option( $optionname ) {

		$defaults = array(
			'api-key' => '',
			'board-id' => '',
			'list-id' => '',
			'checklist-title' => 'Post Checklist',
			'checklist-items' => '["Text Content","Thumbnail","Images","Links"]'
			);

		return wp_parse_args( get_option( 'c2trello_serialized_settings' ), $defaults )[$optionname];
	}

}

?>