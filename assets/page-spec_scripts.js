 
 /**
  * page-spec_scripts.js
  * 
  * Contains javascript for managing content hiding behaviors in 
  * Page Specific Sidebars WP plugin.
  * 
  * Updated: May 16, 2014  Patrick Jackson
  * copyright 2014 IvyCat Web Services
  */
 
 /***************************************************************************
  * Edit Page Meta Box Settings
  ***************************************************************************/
 
 jQuery( 'document' ).ready( function( $ ){
     
     
     //SET LOCAL VARS --------------------------------------------------------
     
     //Minor performance boost by storing jQuery searches, and pruning searches
     var $custom_sidebar_group = $( '#custom-sidebar.group' ),
     
     //Checkbox: inherit parent settings
     $input_is_inherit = $custom_sidebar_group.find( '#is-inherit-parent-settings' ),  
     
     //Checkbox: "use custom sidebar"
     $input_use_custom = $custom_sidebar_group.find('#iscustom'),             
     
     //List: children of use custom sidebar. hidden when $input_use_custom is unchecked
     $custom_sidebar_children = $custom_sidebar_group.find('.custom-sidebar'),
     
     //Radio Button Group: Use Custom Sidebar
     $grpselect = $custom_sidebar_children.find( '.grpselect' ),
     
     //Radio Button: Use Existing Sidebar
     $input_use_custom_sidebar_not_existing = $custom_sidebar_children.find('#customsb'),
     
     //Radio Button: Use Existing Sidebar
     $input_use_existing_sidebar = $custom_sidebar_children.find('#groupsb'),
     
     //List: children of $input_use_existing_sidebar, hidden when unselected
     $use_existing_sidebar_children = $custom_sidebar_children.find( '.existing-sidebars' ),
     
     //Select Box: Custom Sidebars
     $select_existing_sidebars = $use_existing_sidebar_children.find( 'select#existing_sidebar_list' ),
     
     //Options: Custom Sidebars Select, also added the Select box
     $options_existing_sidebars = $select_existing_sidebars.find('option')
                                 .add( $select_existing_sidebars ),
     
     //Checkbox: add to sidebar
     $input_add_to_sidebar = $custom_sidebar_children.find( '#addrplce' ),
     
     //List: children of $input_add_to_sidebar, hidden when unchecked
     $add_to_sidebar_children = $custom_sidebar_children.find( '.sidebar-add' ),
     
     //Radio: Prepend
     $input_prepend = $add_to_sidebar_children.find('#prepend'),
     
     //Radio: Append
     $input_append = $add_to_sidebar_children.find('#append'),
     
     //handle all inputs and checkboxes that are NOT the inherit one as a group.
     $isNotInheritInputs = $custom_sidebar_group.find( 'input:not(#is-inherit-parent-settings)' )
                                                .add( $select_existing_sidebars ),
     
     //handle all labels for inputs and checkboxes that are NOT the inherit one as a group.
     $isNotInheritLabels = $custom_sidebar_group.find( 'label:not([for=is-inherit-parent-settings])' )
                                                .add ( $options_existing_sidebars );
     
     //INITIALIZE PAGE ELEMENTS ----------------------------------------------
     
     //verify disabled state based on whether inheritance is checked
     //also re-applies parent settings if appropriate
     setInhertanceDisabled();
     
     //Verify elements are hidden/shown as needed
     refreshHidden();
     
     
     //Attach toggle behavior to Inherit Parent Sidebar checkbox
     $input_is_inherit.change( setInhertanceDisabled );
     
     
    
    //toggle behavior on checkboxes...
    $input_use_custom.change( function(){
        $custom_sidebar_children.toggle();
        refreshHidden();
    });
    
    $grpselect.change( function(){
	$use_existing_sidebar_children.toggle();
    });
        
     $input_add_to_sidebar.change( function(){
        $add_to_sidebar_children.toggle();
    });
    
    
    //FUNCTIONS -------------------------------------------------------------

     
    /**
     * convenience function that sets all of the page options to disabled
     * when isDisabled = true, otherwise unsets disabled.
     * 
     * @param {type} isDisabled
     * @returns {undefined}
     */
    function setOptionsDisabled( isDisabled ){

        $isNotInheritInputs.prop('disabled', isDisabled);
        
        if (isDisabled){
            $isNotInheritLabels.addClass('disabled');
        } else {
            $isNotInheritLabels.removeClass('disabled');
        }
    }
    
    function setInhertanceDisabled(){
        if ( $input_is_inherit.prop('checked') ){
            setOptionsDisabled(true);
            applyParentSettings();
        } else {
            setOptionsDisabled(false);
        }
    }
    
    
    /**
     * Uses vars set in page script to set state of page elements.
     * Assumes the following has been set (bails if does not exist)
     * 
     * var parentSettings = {
     *              isCustom : bool,
     *              isAddToSidebar : bool,
     *              isUseExistingSidebar : bool,
     *              existingSidebarToUse : "string"
     *          };
     * 
     */
    function applyParentSettings(){
        
        if ( !parentSettings ) return;
        
        $input_use_custom.prop('checked', parentSettings.isCustom );
        $input_add_to_sidebar.prop( 'checked', parentSettings.isAddToSidebar );
        
        if (parentSettings.isUseExistingSidebar)
            $input_use_existing_sidebar.prop( 'checked', true);
        else
            $input_use_custom_sidebar_not_existing.prop( 'checked', true);
        
        if ( parentSettings.existingSidebarToUse )
            $select_existing_sidebars.val( parentSettings.existingSidebarToUse );
        
        if ( parentSettings.isPrepend )
            $input_prepend.prop( 'checked', true );
        else
            $input_append.prop( 'checked', true);
        
        refreshHidden();
    }
    
    /**
     * When parent setting have been applied, we may need to hide/unhide
     * other elements.
     */
    function refreshHidden(){
        
        //checkbox state may differ depending on browser when page is refreshed,
        //so need to explicitely initialize checkbox/hidden states
        if ( $input_use_custom.prop('checked') ){
            $custom_sidebar_children.show();

            if ( $input_use_existing_sidebar.prop('checked') ){
                $use_existing_sidebar_children.show();
            } else {
                $use_existing_sidebar_children.hide();
            }

            if ( $input_add_to_sidebar.prop('checked') ){
                $add_to_sidebar_children.show();
            } else {
                $add_to_sidebar_children.hide();
            }

        } else {
            $custom_sidebar_children.hide();
        }
            
    }
    
});




 /***************************************************************************
  * Plugin Settings Page
  ***************************************************************************/
 
jQuery( 'document' ).ready( function( $ ){
    
    
    var $primary_sidebar_selector = $('select#primary-slug');
    
    //Attach style-setting behavior to the change event of the primary sidebar selector
    $primary_sidebar_selector.change( setSelectOnVal($primary_sidebar_selector) );
    
    //Attach behaviors to the plugin settings tabs
    $( '#page-specific-sidebar-settings .top-menu li a' ).click( function(){
        var toshow = $( this ).attr( 'href' ).replace( '#', '' );
        $( '.top-menu li' ).removeClass( 'current-menu-tab' );
        $( this ).parent( 'li' ).addClass( 'current-menu-tab' );
        $( '.group' ).hide().removeClass( 'current-tab' );
        $( '.' + toshow ).show().addClass( 'current-tab' );
        return false;
    } );
    
    //Perform default style-setting behavior on primary sidebar selector
    setSelectOnVal($primary_sidebar_selector);
    
    
    
    /**
     * 
     * Given a Select element, assume options with no value are "non-options":
     * options not intended to be selected, but that are not disabled.
     * Set the non-option's style, and makes sure all other options are set to the default.
     * 
     * @param {type} $element
     * @returns {undefined}
     */
    function setSelectOnVal($element){
         if (!$element.val()){
             $element.css("color", "#999999")
                     .css("font-style", "italic");
             $element.find("option").css("color", "#333333")
                     .css("font-style", "normal");
             $element.find("option[value=]").css("color", "#999999")
                     .css("font-style", "italic");
         } else { //unset the color
             $element.css("color","#333333")
                     .css("font-style","normal");
         }
     }
      
});