<?php
/*
 * Connect to Trello
 * i18n Class
 */

class C2Trello_i18n {

	/*
	 * Text Domain
	 */
	public $textdomain;

	/*
	 * Constructor
	 */
	public function __construct( $textdomain ) {
		$this->textdomain = $textdomain;
	}

	public function load_textdomain() {
		load_plugin_textdomain( $this->textdomain , false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	}

}

?>