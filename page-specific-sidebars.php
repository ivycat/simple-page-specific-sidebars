<?php

/**
 *  Plugin Name: Simple Page Specific Sidebars
 *  Plugin URI: http://wordpress.org/extend/plugins/page-specific-sidebars/
 *  Description: Add a sidebar to any specific page by creating a widget area on demand.
 *  Author: IvyCat Web Services
 *  Author URI: http://www.ivycat.com
 *  Version: 2.14.2
 *  License: GNU General Public License v2.0
 *  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
 ------------------------------------------------------------------------
    Simple Page Specific Sidebars, Copyright 2014 IvyCat, Inc. (admins@ivycat.com)
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

 */

require_once 'assets/class-page-sidebar-settings-model.php';

if( !defined( 'DGSIDEBAR_DIR' ) ) define( 'DGSIDEBAR_DIR', dirname( __FILE__ ) ) ;
if( !defined( 'DGSIDEBAR_URL' ) ) define( 'DGSIDEBAR_URL', str_replace( ABSPATH, site_url( '/' ), DGSIDEBAR_DIR ) ) ;

class DGPageSidebarCustom{
    
    //protected $home_id;
    protected $widget_name;
    protected $my_page_id;                      //grab the page id and keep it handy
    protected $is_global_inheritance = true;    //default setting on activate
    
    public function __construct(){
        self::set_opts();
        register_activation_hook( __FILE__, 'activate_plugin' );
        add_filter( 'plugin_action_links_'. plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ), 10, 4 );
        add_action( 'widgets_init', array( $this, 'build_sidebars' ) );
        add_action( 'admin_init', array( $this, 'add_page_meta_box' ) );
        add_action( 'save_post' , array( $this, 'save_custom_page_meta' ) );
        add_filter( 'sidebars_widgets', array( $this, 'hijack_sidebar' ) );
        add_action( 'admin_menu', array( $this, 'options_page_init' ) );
        add_action( 'admin_notices', array( $this, 'check_primary_sidebar' ) );
    }
    
    
    /**************************************************************************
     * Do this when plugin is activated.
     * Sets initial state of plugin, and checks for appropriate version
     */
    public function activate_plugin(){
        
        //check for correct version of WP
        if ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ){
            deactivate_plugins( basename( __FILE__ ) ); //Deactivate our plugin 
            
            //Wrong version error message...?>
            <div class="error">Page Specific Sidebars is only compatible with WordPress version 3.0 or greater.  
                This instance is running version <?php echo get_bloginfo( 'version' ); ?>.  
                The plugin has been disabled.  To use this plugin, please update your version of WordPress and reactivate
                the plugin.
            </div><?php 
        }
        
        //set global child inheritance state if it doesn't already exist from
        //a previous installation.  If it does exist, use it to set the class variable.
        //save option as string true/false so that a false value is distinguishable from 
        //no value
        if ( !get_option('page-specific-sidebars-is-global-inheritance') ){
            if ( $this->is_global_inheritance )
                add_option('page-specific-sidebars-is-global-inheritance', 'true');
            else
                add_option('page-specific-sidebars-is-global-inheritance', 'false');
        } else {
            $this->is_global_inheritance = 
                    (get_option('page-specific-sidebars-is-global-inheritance') === 'true') ? true : false;
        }
    }
    
    /*************************************************************************
     * Initialize the options page
     */
    public function options_page_init(){
        if( !current_user_can( 'administrator' ) ) return;
        $hooks = array();
	$hooks[] = add_options_page( __( 'Page Sidebar Settings' ), __( 'Page Sidebar Settings' ), 'read', 'page-sidebar-settings', array( $this, 'option_page' ) );
         foreach( $hooks as $hook ) add_action( "admin_print_styles-{$hook}", array($this, 'load_assets' ) );

    }
    
    public function load_assets(){
         wp_enqueue_style( 'page-spec-sidebar-css', DGSIDEBAR_URL . '/assets/page_spec_styles.css' );
         wp_enqueue_script( 'page-spec-sidebar-js', DGSIDEBAR_URL . '/assets/page-spec_scripts.js' );
    }
    
    public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
        if ( is_plugin_active( $plugin_file ) )
            $actions[] = '<a href="' . $this->get_settings_url() . '">' . __( 'Settings & Help', 'page-sidebar-settings' ) . '</a>';
        return $actions;
    }
    
    private function get_settings_url(){
        return admin_url('options-general.php?page=page-sidebar-settings');
    }
    
    
  
    /**************************************************************************
     *  Display Options page / Save Options on submit
     */
    public function option_page(){
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ) self::page_sidebar_settings_save();
        
        global $wp_registered_sidebars;
        require_once 'assets/page-sidebar-options-view.php';
    }
    
    /**
     * Handle form from Edit Page screen.  
     * Update pade sidebar settings where necessary
     */
    public function page_sidebar_settings_save(){
        //update_option( 'page_sidebar_home_id', trim( $_POST['home_page_id'] ) );
        update_option( 'page_sidebar_widget_name', trim( $_POST['primary_sidebar_slug'] )  );
        
        //update global inheritance setting
        if ( isset( $_POST['is-global-inheritance'] ) && $_POST['is-global-inheritance'] === 'true'){
            update_option( 'page-specific-sidebars-is-global-inheritance', 'true');
            $this->is_global_inheritance = true;
        } else {
            update_option( 'page-specific-sidebars-is-global-inheritance', 'false' );
            $this->is_global_inheritance = false;
        }
        
        self::set_opts();
        
        //check to see if we need to flash the nag message.
        //Case: user selects, then deselects (selects the null option) the primary sidebar
        $this->check_primary_sidebar();
    }
    
    public function set_opts(){
        
        $this->widget_name = get_option( 'page_sidebar_widget_name' );
        
    }
    
    
    /**************************************************************************
     * Add meta box to Edit Page screen
     */
    public function add_page_meta_box(){
        $location = apply_filters( 'page_sidebar_location', 'side' );
        $priority = apply_filters( 'page_sidebar_priority', 'high' );
        add_meta_box(
            'custompageopt',
            'Custom Sidebar Options',
            array( $this, 'custom_page_meta' ),
            'page',
            $location ,
            $priority
        );
    }
    
    public function custom_page_meta(){
        global $post;
        
        self::load_assets(); //enqueue js & css
        
        //this constructor will populate the page settings
        $page_sidebar_settings = new Page_Sidebar_Settings_Model( $post );
        
        //render the contents of the this plugin's meta box on the Edit Page screen
        require_once 'assets/class-page-sidebar-settings-view.php';
        echo Page_Sidebar_Settings_View::get_view($page_sidebar_settings);

        ?>
        
        <?php
    }
    
    /**************************************************************************
     * 
     * Handle saving Edit Page meta box settings
     * 
     * @global type $post
     * @global Page_Sidebar_Settings_Model $page_sidebar_settings
     * @param type $post_id
     * @return type
     */
    
    public function save_custom_page_meta( $post_id ){
        if ( defined('DOING_AJAX') ) return;
        global $post, $page_sidebar_settings;
        
        if( !$page_sidebar_settings )
            $page_sidebar_settings = new Page_Sidebar_Settings_Model( $post );
                
        $page_sidebar_settings->set_inherit_parent_settings( isset($_POST['is-inherit-parent-settings']) && $_POST['is-inherit-parent-settings'] === 'true');
        
        //global is overridden when either global = false and inherit is true
        //or vice-versa
        $page_sidebar_settings->set_override_global_settings( 
                
                ($page_sidebar_settings->is_global_inheritance() && !$page_sidebar_settings->is_inherit_parent_settings())
             || (!$page_sidebar_settings->is_global_inheritance() && $page_sidebar_settings->is_inherit_parent_settings())
        );
        
        $page_sidebar_settings->set_custom_sidebar( isset( $_POST['is-custom'] ) && $_POST['is-custom'] === 'y' );
        $page_sidebar_settings->set_use_existing_sidebar( isset( $_POST['customsb'] ) && $_POST['customsb'] === 'group' );
        $page_sidebar_settings->set_add_to_sidebar( isset( $_POST['add2sidebar'] ) && $_POST['add2sidebar'] === 'add2chk' );
        $page_sidebar_settings->set_prepend( isset( $_POST['pre-append'] ) && $_POST['pre-append'] === 'prepend' );
        
        if ( isset($_POST['existing_sidebar_slug'] ) )
            $page_sidebar_settings->set_existing_sidebar_to_use( $_POST['existing_sidebar_slug'] );
        
        $page_sidebar_settings->store();
        
    }
    
    
    /**************************************************************************
     * 
     * Manage building front-end page sidebars
     * 
     * @global type $_wp_sidebars_widgets
     */
    public function build_sidebars(){
        
        $pages = self::get_pages();
        $stop = count( $pages );
        $count = 0;
        foreach( $pages as $page ){
            
            $sb_group = get_post_meta( $page->ID, 'use_sidebar_group', true );
            $count++; 
            if( $count <= $stop  && !$sb_group ){
                $args = array(
                'name'          => __( $page->post_title . ' Sidebar' ),
                'id'            => 'page-sidebar-'. $page->ID ,
                'description'   => '',
                'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
                'after_widget' => '</div></div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>' );

                register_sidebar( $args );
                
            }
        }
    }
     
    public function hijack_sidebar( $sidebars=array() ){
        
        global $_wp_sidebars_widgets, $post;
        
        
       if( did_action( 'sidebars_widgets' ) == 1 ) return $sidebars;
        
        $sidebar_title = apply_filters( 'page-sidebar-title' , $this->widget_name );
        
        //Only operate on front-end "Page" type posts
        if( !is_page() || is_admin() ) return $sidebars;
        
        
        //get the settings for this page
        $page_sidebar_settings = new Page_Sidebar_Settings_Model( $post );
        
        //stop if the custom sidebar box wasn't checked
	if( !$page_sidebar_settings->is_custom_sidebar() )
            return $sidebars;
        
        //use the selected existing sidebar, or get the custom sidebar for this page
        if( $page_sidebar_settings->is_use_existing_sidebar() ){
            
            $sidebar_term = $page_sidebar_settings->get_existing_sidebar_to_use();
            
        } else {
            
            //$sidebar_term = ( is_front_page() ) ? 'page-sidebar-' . self::home_pg_id() : 'page-sidebar-' . $post->ID;
            $sidebar_term = $page_sidebar_settings->get_sidebar_term();
            
        }
        
        //if the widgets we want are missing, bail
        if( !array_key_exists( $sidebar_term, $_wp_sidebars_widgets) || count($_wp_sidebars_widgets[$sidebar_term]) < 1 ){
            
            return $sidebars; 

        } else {

            //make sure widgets aren't corrupt (WP3+)
            if( $_wp_sidebars_widgets['array_version'] != 3  )
                    return $sidebars;

            //handle whether to add to existing sidebar
            if( $page_sidebar_settings->is_add_to_sidebar() ){
                
                $add_sidebar = (array)$sidebars[$sidebar_title];
                
                //there must be more than one widget if they're going to be added
                //together.  If there's more than one, they'll be stored as an array
                if( is_array( $_wp_sidebars_widgets[$sidebar_term] ) ){
                    
                    $sidebars[$sidebar_title] = ( $page_sidebar_settings->is_prepend() )
                        ? array_merge( $_wp_sidebars_widgets[$sidebar_term], $add_sidebar )
                        : array_merge( $add_sidebar, $_wp_sidebars_widgets[$sidebar_term] );
                    
                } else {
                    
                    //if there's only one sidebar, then use it
                    $sidebars[$sidebar_title] = $add_sidebar;
                }

            } else {
                
                //Here is where we replace the primary sidebar if we're
                //not adding to it.
                $sidebars[$sidebar_title] = $_wp_sidebars_widgets[$sidebar_term];

            }
            
            return $sidebars;
        }
    }
    
    /*protected function fprint_r($array){
        printf('<pre>%s</pre>', print_r($array, 1));
    }*/
    
    /**
     *  Often times the Homepage has posts on it, dictated by the template.  For some reason, this sets the post->ID for the page as the last post listed on the page
     *  which keeps this plugin from inserting the correct sidebar.  This is the fix.
     */
    /*protected function home_pg_id(){
        $home_slug = 'home';
        $home_slug = apply_filters( 'page-sidebar-homeslug', $home_slug );
        $pg = get_page_by_path( $home_slug );
        if ($pg){
            return $pg->ID;
        }
    }*/
    
    protected function get_pages(){
        $pages = get_posts( array( 'post_type'=>'page', 'numberposts'=>-1, 'orderby'=>'post_title', "meta_key"=>"is_custom", "meta_value"=>"y" ) );
        return $pages;
    }
    
    /**
     * Check to see whether the primary sidebar is selected.  Nag if not.
     * If the primary_sidebar_slug is present in a POST, then the primary sidebar
     * is in the act of being set (but the option hasn't been saved yet), so don't nag.
     */
    public function check_primary_sidebar(){

        if ( (!isset($_POST['primary_sidebar_slug']) || !$_POST['primary_sidebar_slug']) && !get_option( 'page_sidebar_widget_name' ) ){
            echo "<div class='updated'><p>You're almost ready to use Page Specific Sidebars.  Please go to the <a href='" . $this->get_settings_url() . "'>Plugin Settings</a> page, and select the Primary Sidebar.</p></div>";
        }
    }
    
    
} new DGPageSidebarCustom();
