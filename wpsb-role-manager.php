<?php
/*
 * Plugin Name: WP Sitebuilder - Role Manager
 * Plugin URI:
 * Description: Add on for WP Sitebuilder plugin that lets you create and manage roles and capabilities.
 * Author: Mithu A Quayium
 * Author URI:
 * Version: 0.0.1
 * Text Domain: sbrm
 * License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists( 'WPSB_Pagebuilder' ) ) {
    ?>
    <div class="notice notice-error">
        <p><?php _e('Errot ! <strong>WP Sitebuilder</strong> plugin is not activated ! <strong>"Role Manager"</strong> needs "WP Sitebuilder" to be activated to work properly !  ', 'wpsb'); ?></p>
    </div>
    <?php
    return;
};

define( 'SBRM_VERSION', '0.0.1' );
define( 'SBRM_ROOT', dirname(__FILE__) );
define( 'SBRM_ASSET_PATH', plugins_url('assets',__FILE__) );

class Sbrm_Init {

    /**
     * @var Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Instantiate the add on
     * Sbrm_Init constructor.
     */
    public function __construct() {
        add_action('wpsb_admin_menu', array($this,'build_submenu_page'));
        add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts_styles'));
        $this->includes();
    }

    function includes() {
        include_once SBRM_ROOT.'/ajaxaction.php';
    }

    /**
     * Submenu page for
     * admin frontend
     */
    public function build_submenu_page($menu_slug) {
        add_submenu_page( $menu_slug, __('Role Manager','sbrm'), __('Role Manager','sbrm'), 'manage_options','sbrm_role_mananger', array($this,'build_role_manager_page') );
    }

    /**
     * Admin frontend page
     */
    public function build_role_manager_page() {
        include_once SBRM_ROOT.'/admin/role-manager-panel.php';
    }

    public function admin_enqueue_scripts_styles( $hook ) {

        if( $hook == 'wp-sitebuilder_page_sbrm_role_mananger' ) {
            //style
            wp_enqueue_style('lego-wrapper-css');
            wp_enqueue_style('lego-framework-css');
            wp_enqueue_style('sbrm-admin-css', SBRM_ASSET_PATH.'/css/admin-sbrm.css');
            //script
            wp_enqueue_script('wpsb-vue');
        }
    }

}

Sbrm_Init::get_instance();