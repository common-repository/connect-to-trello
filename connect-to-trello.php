<?php
/*
Plugin Name: Connect to Trello
Plugin URI:  http://www.webkinder.ch
Description: Quickly add your WordPress tasks to Trello
Version:     1.0
Author:      Mauro Bringolf
Author URI:  http://www.webkinder.ch
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /lang/
Text Domain: connect-to-trello
*/

class C2Trello_Main {

    /*==========================CONSTRUCTOR====================*/

    public function __construct() {

    	//Internal Setup
    	$this->textdomain = 'connect-to-trello';

    	//Lifecycle
    	require_once( plugin_dir_path( __FILE__ ) . 'includes/connect-to-trello-lifecycle.php');
    	$this->lifecycle = new C2Trello_Lifecycle();

    	register_activation_hook( __FILE__, array( $this, "activation" ) );
    	register_deactivation_hook( __FILE__, array( $this, "deactivation" ) );
    	//===

    	//i18n
    	require_once( plugin_dir_path( __FILE__ ) . 'includes/connect-to-trello-i18n.php');
    	$this->i18n = new C2Trello_i18n( $this->textdomain );

    	add_action('plugins_loaded', array( $this, "load_textdomain") );
    	//===

        //Settings
    	require_once( plugin_dir_path( __FILE__ ) . 'admin/connect-to-trello-settings.php' );
        $this->settings = new C2Trello_Settings( $this->textdomain );

        add_action( 'admin_init', array( $this, "register_settings" ) );
        add_action( 'admin_menu', array( $this, "render_settings_page" ) );
        //===

        //Loader
        require_once( plugin_dir_path( __FILE__ ) . 'includes/connect-to-trello-loader.php');
        $this->loader = new C2Trello_Loader( $this->textdomain );
        add_action( 'admin_enqueue_scripts', array( $this, "load_scripts") );
        //===

        //Trello Controls
        require_once( plugin_dir_path( __FILE__ ) . 'admin/connect-to-trello-controls.php');
        $this->controls = new C2Trello_Controls( $this->textdomain );
        add_action( 'add_meta_boxes', array( $this, "render_controls" ) );
        //===

    }

    /*==========================MEMBERS=========================*/

    private $settings, $lifecycle, $i18n, $textdomain;

    /*==========================METHODS========================*/

    /*
     * Load Text Domain
     */
	public function load_textdomain() {
        $this->i18n->load_textdomain();
	}

    /*
     * Plugin Activation
     */
    public function activation() {
        $this->lifecycle->activate();
    }

    /*
     * Plugin Deactivation
     */
    public function deactivation() {
        $this->lifecycle->deactivate();
    }
    /*
     * Register Settings
     */
    public function register_settings() {
        $this->settings->register();
    }

    /*
     * Render Settings Page
     */
    public function render_settings_page() {
       	$this->settings->render();
    }

    /*
     * Load Scripts
     */
    public function load_scripts( $hook ) {
        $api_key = $this->settings->get_trello_option('api-key');
    	$this->loader->load_scripts( $hook, $api_key );
    }

    /*
     * Add Post
     */
    public function render_controls( $post_type ) {
    	$list_id = $this->settings->get_trello_option('list-id');
    	$checklist_title = $this->settings->get_trello_option('checklist-title');
    	$checklist_items = $this->settings->get_trello_option('checklist-items');

    	$this->controls->render_controls( $post_type, $list_id, $checklist_title, $checklist_items );
    }

}

$c2_trello_main = new C2Trello_Main();

?>