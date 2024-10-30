<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
delete_option( 'c2trello_serialized_settings' );
 
// For site options in Multisite
delete_site_option( 'c2trello_serialized_settings' );

?>