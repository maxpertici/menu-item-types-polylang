<?php
/*
Plugin Name:  Menu Item Types — Polylang
Plugin URI:   https://maxpertici.fr#menu-item-types
Description:  Add the ability to use polylang selector in your nav menu
Version:      1.0
Author:       @maxpertici
Author URI:   https://maxpertici.fr
Contributors:
License:      GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  mitypes-polylang
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or	die();


/**
 * Run plugin - test
 * @since 1.0
 */

function mitypes_polylang_run(){

	if( ! mitypes_polylang_is_mitypes_loaded() ){
		add_action('admin_notices', 'mitypes_polylang_notice_plugin_required');
	}

	
}

add_action( 'plugins_loaded', 'mitypes_polylang_run' );



/**
 * Add custom nav menu item
 */
function mitypes_polylang_add_item_types( $types ){
    $types[] = array(
        'slug'        => "polylang",
        'icon'        => plugin_dir_url( __FILE__ ) . 'img/mitypes-polylang.svg',
        'label'       => __( 'Polylang', 'mitypes-polylang' ),
        'field-group' => plugin_dir_path( __DIR__ ) . 'menu-item-types-button/acf/polylang-field-group.php',
		'render'      => plugin_dir_path( __DIR__ ) . 'menu-item-types-button/render/polylang.php',
    );
    return $types;
}

add_filter( 'mitypes_item_types', 'mitypes_polylang_add_item_types' );



/**
 * Handle attributes : skip href
 */

function mitypes_polylang_attributes_skiper( $atts, $item, $args, $depth, $custom_item_type ){
	if( ( 'polylang' === $custom_item_type ) ){ unset( $atts['href'] ); }
	return $atts ;
}

add_filter( 'mitypes_nav_menu_link_attributes', 'mitypes_polylang_attributes_skiper', 11, 5 );




/**
 * Test if Menu Items Types is loaded
 *
 * @since 1.0
 */
function mitypes_polylang_is_mitypes_loaded(){

    /**
     * Load ACF & configure it
     */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
    if ( ! is_plugin_active( 'menu-item-types/menu-item-types.php'     ) ){
        return false ;
    }

    return true ;
}


/**
 * 
 * MITYPES notice
 * 
 * @since 1.0
 */
function mitypes_polylang_notice_plugin_required(){
    
	//print the message
    $mitypes_search_url = 'plugin-install.php?s=menu-item-types&tab=search&type=term';
    $mitypes_link = get_admin_url() . $mitypes_search_url ;

    echo '<div id="message" class="error notice is-dismissible">
        <p>'. __( 'Please install and activate', 'mitypes-polylang') . ' ' . '<a href="'.$mitypes_link.'">Menu Item Types</a>'. ' ' . __('for using Menu Item Types — Polylang plugin.' , 'mitypes-polylang').'</p>
    </div>';

    
    //make sure to remove notice after its displayed so its only displayed when needed.
    remove_action('admin_notices', 'mitypes_polylang_notice_plugin_required');

    // shutdown
    deactivate_plugins( 'menu-item-types-polylang/menu-item-types-polylang.php' );
}
