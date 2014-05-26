<?php


/**
 * 
 * Sidebar_Settings_Container.php
 * 
 * Author: Patrick Jackson, IvyCat
 * Date: 2014-04-28
 * 
 * This class encapsulates sidebar settings for a given page, and abstracts
 * functions for managing those settings.
 */

class Page_Sidebar_Settings_Model {    
    
    private $parent_settings = null;                //the parent's Sidebar_Settings_Container
    private $page = null;                           //the page that these settings are for
    private $is_global_inheritance = null;          //true if global option is set to inherit by default
    private $is_override_global_settings = null;    //true if this page overrides global inheritance setting
    private $is_inherit_parent_settings = null;     //true if this page inherits its parent's settings
    private $is_custom_sidebar = null;              //true if this page is using a custom sidebar
    private $is_add_to_sidebar = null;              //true if custom sidebar is added to the primary sidebar
    private $is_prepend = false;                    //true if custom sidebar is prepended to the primary sidebar, false if appended
    private $is_use_existing_sidebar = false;       //true if using existing sidebar as custom sidebar
    private $existing_sidebar_to_use = null;        //if using existing sidebar, this is the existing sidebar being used
    
    /**
     * Constructor tries to set all of the variables
     * @param type $post
     */
    function __construct( $post ){
                
        if (!$post){
            return new WP_Error("Page_Sidebar_Settings_Model Construct Failed", __("Attempted to instantiate a Page_Sidebar_Settings_Model object without passing the required $post parameter"));
        }
        
        $this->page = $post;
        $this->load();
        
    }
    
    public function get_sidebar_term(){
        return "page-sidebar-{$this->page->ID}";
    }
    
    //
    //  GET/SET Boilerplate
    //
    
    /**
     * 
     * @return type
     */
    public function get_page() {
        return $this->page;
    }

    public function is_custom_sidebar() {
        return $this->is_custom_sidebar;
    }

    public function is_add_to_sidebar() {
        return $this->is_add_to_sidebar;
    }

    public function is_prepend() {
        return $this->is_prepend;
    }

    public function is_use_existing_sidebar() {
        return $this->is_use_existing_sidebar;
    }

    public function get_existing_sidebar_to_use() {
        return $this->existing_sidebar_to_use;
    }
    
    public function is_global_inheritance() {
        return $this->is_global_inheritance;
    }
    
    public function get_parent_settings(){
        return $this->parent_settings;
    }

    public function set_global_inheritance($is_global_inheritance) {
        $this->is_global_inheritance = $is_global_inheritance;
    }

        
    public function is_override_global_settings() {
        return $this->is_override_global_settings;
    }

    public function is_inherit_parent_settings() {
        return $this->is_inherit_parent_settings;
    }

    public function set_override_global_settings($is_override_global_settings) {
        $this->is_override_global_settings = $is_override_global_settings;
    }

    public function set_inherit_parent_settings($is_inherit_parent_settings) {
        $this->is_inherit_parent_settings = $is_inherit_parent_settings;
    }
    
    public function set_page($page) {
        $this->page = $page;
    }

    public function set_custom_sidebar($is_custom_sidebar) {
        $this->is_custom_sidebar = $is_custom_sidebar;
    }

    public function set_add_to_sidebar($is_add_to_sidebar) {
        $this->is_add_to_sidebar = $is_add_to_sidebar;
    }

    public function set_prepend($is_prepend) {
        $this->is_prepend = $is_prepend;
    }

    public function set_use_existing_sidebar($is_use_existing_sidebar) {
        $this->is_use_existing_sidebar = $is_use_existing_sidebar;
    }

    public function set_existing_sidebar_to_use($existing_sidebar_to_use) {
        $this->existing_sidebar_to_use = $existing_sidebar_to_use;
    }
    
    
     /**
     * Loads settings from the database
     * Used when this page's settings are in the database, and not inherited
     */
    private function load(){
        
        
        //if there's a parent, load it
        //warning: will recursively load all ancestors
        if ( $this->page->post_parent > 0 ){
            $this->parent_settings = new self( $this->get_parent_post() );
            $this->set_inheritance_settings();
        } 
        
                
        //if this page has no parent, or if we are not inheriting, 
        //use this page's meta options to load custom sidebar settings; 
        //otherwise copy the parent's settings (for convenience)
        if ( !$this->page->post_parent > 0 || !$this->is_inherit_parent_settings){

            $this->is_custom_sidebar = get_post_meta( $this->page->ID, 'is_custom', true ) === 'y';
            $this->existing_sidebar_to_use = get_post_meta( $this->page->ID, 'use_sidebar_group', true );
            $this->is_use_existing_sidebar = !empty($this->existing_sidebar_to_use);
            $this->is_add_to_sidebar = get_post_meta( $this->page->ID, 'add2sidebar', true ) === 'add2chk';
            $this->is_prepend = get_post_meta( $this->page->ID, 'prepend_to_sidebar', true ) === 'prepend';

        } else {

            $this->copy_parent_settings();

        }
        
        /*echo "<pre>";
        var_dump($this);
        echo "</pre>";*/
    }
    
    /**
     * Determines whether this page inherits settings, 
     * and sets global inheritance and override global flags.
     * These are all moot if page has no parent.
     */
    private function set_inheritance_settings(){
        
        //when Global inheritance option is true, pages implicitly inherit
        //parent settings unless overridden at the page level
        $this->is_global_inheritance = get_option( 'page-specific-sidebars-is-global-inheritance') === 'true';

        //when true, page is overriding global (implicit) behavior
        $this->is_override_global_settings = get_post_meta($this->page->ID, 'is_override_global_settings', true)  === 'true';
        if ( $this->is_override_global_settings ){
            $this->is_inherit_parent_settings = get_post_meta($this->page->ID, 'is_inherit_parent_settings', true)  === 'true';
        }

        //Does this page inherit?...
        //If overriding, use page's post meta value to decide
        //otherwise use the global settings
        $this->is_inherit_parent_settings = $this->is_override_global_settings ?
                get_post_meta($this->page->ID, 'is_inherit_parent_settings', true) === 'true' : 
                $this->is_global_inheritance;
    }
    
    /**
     * loads the parent, and sets this page's settings with those of the parent
     */
    private function copy_parent_settings(){
                                            
        if ( $this->parent_settings ){

            $this->is_custom_sidebar = $this->parent_settings->is_custom_sidebar;
            $this->is_add_to_sidebar = $this->parent_settings->is_add_to_sidebar;
            $this->is_prepend = $this->parent_settings->is_prepend;
            
            /**
             * Since the expected behavior when using parent settings is to
             * use the parent's sidebar, we'll override the normal "use existing
             * sidebar" behavior to use the parent's sidebar.  Further progeny
             * will inherit this new setting as well so that they use the common
             * ancestor's sidebar as well.
             */
            
            $this->is_use_existing_sidebar = true; //always true when there's a parent
            $this->existing_sidebar_to_use = $this->parent_settings->is_use_existing_sidebar ?
                    $this->parent_settings->existing_sidebar_to_use :
                    $this->parent_settings->get_sidebar_term();
        }
    }


    /**
     * Stores all settings to database
     * This is used when there is no parent, when overriding parent,
     * or in the case where we are overriding a global do-not-inherit.
     * 
     * In the latter case, we only store the page meta: is-page-override = 'false'
     */
    public function store(){
                
        //During new page creation, this is called but the page doesn't 
        //exist yet, so just ignore for now.
        if( !$this->page )
            return;
        
        //If this page has a parent, store/update the inheritance settings.
        //If not, ignore the settings.
        //(Existing settings don't need to be cleared, since they'll be ignored
        //by default.  If a parent is applied, then we'll assume that any
        //previously applied inheritance setting should be re-applied by default.)
        if( $this->page->post_parent > 0 ) {
                        
            if ( $this->is_override_global_settings ){
                update_post_meta( $this->page->ID, 'is_override_global_settings', 'true' );
                update_post_meta( $this->page->ID, 'is_inherit_parent_settings', 
                    $this->is_inherit_parent_settings ? 'true' : 'false' );
            } else {
                delete_post_meta( $this->page->ID, 'is_override_global_settings' );
            }
                        
        }
        
        //if there is no parent, or if we are NOT inheriting from the parent, 
        //then store this page's sidebar settings as post metas
        if ( !$this->is_inherit_parent_settings ){
            
            if ( $this->is_custom_sidebar ){
                update_post_meta( $this->page->ID, 'is_custom', 'y');
            } else {
                delete_post_meta( $this->page->ID, 'is_custom');
            }
            
            if ( $this->is_use_existing_sidebar && $this->existing_sidebar_to_use ){
                update_post_meta( $this->page->ID, 'use_sidebar_group', $this->existing_sidebar_to_use );
            } else {
                delete_post_meta( $this->page->ID, 'use_sidebar_group' );
            }
            
            if ( $this->is_add_to_sidebar ){
                update_post_meta( $this->page->ID, 'add2sidebar', 'add2chk' );
            } else {
                delete_post_meta( $this->page->ID, 'add2sidebar');
            }
            
            if (isset( $_POST['pre-append'] ) ){
                update_post_meta( $this->page->ID, 'prepend_to_sidebar', $_POST['pre-append'] );
            } else {
                delete_post_meta( $this->page->ID, 'prepend_to_sidebar' );
            }
            
        }
    }
    
    /**
     * 
     * Gets parent post for this $page if it exists
     * 
     * @return null
     */
    private function get_parent_post( ){
        $parents = get_post_ancestors( $this->page->ID );
        
        if ( empty($parents) ){ return null; }
        
	$id = $parents[0];                              //1st ancestor is parent
        return get_page( $id );
    }
    
}