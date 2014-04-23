 
 /**
  * page-spec_scripts.js
  * 
  * Contains javascript for managing content hiding behaviors in 
  * Page Specific Sidebars WP plugin.
  * 
  * Updated: Apr 14, 2014  Patrick Jackson
  * copyright 2014 IvyCat Web Services
  */
 
 
 
 jQuery( 'document' ).ready( function( $ ){
     
     //Minor performance boost by storing jQuery searches, and pruning searches
     $iscustom = $('#iscustom');                                            //"use custom sidebar" checkbox
     $custom_sidebar = $iscustom.parent().find('.custom-sidebar');            //hidden content ul
     
     $grpselect = $custom_sidebar.find( '.grpselect' );                     //"use existing" radio buttons
     $groupsb = $custom_sidebar.find('#groupsb');                           //"use existing" radio button
     $existing_sidebars = $custom_sidebar.find( '.existing-sidebars' );     //hidden content ul
     
     $addrplce = $custom_sidebar.find( '#addrplce' );                       //"add or replace sidebar" checkbox
     $sidebar_add = $custom_sidebar.find( '.sidebar-add' );                 //hidden content ul
     
     $select_primary = $('select#primary-slug');                            //Dropdown for selecting the primary sidebar
     
     
     //Perform default style-setting behavior on primary sidebar selector
     setSelectOnVal($select_primary);
     
     //Attach style-setting behavior to the change event of the primary sidebar selector
     $select_primary.change( function(){
         setSelectOnVal($select_primary);
     })
     
     
     
     //Attach behaviors to the plugin settings tabs
    $( '#page-specific-sidebar-settings .top-menu li a' ).click( function(){
        var toshow = $( this ).attr( 'href' ).replace( '#', '' );
        $( '.top-menu li' ).removeClass( 'current-menu-tab' );
        $( this ).parent( 'li' ).addClass( 'current-menu-tab' );
        $( '.group' ).hide().removeClass( 'current-tab' );
        $( '.' + toshow ).show().addClass( 'current-tab' );
        return false;
    } );
    
    
    //checkbox state may differ depending on browser when page is refreshed,
    //so need to explicitely initialize checkbox/hidden states
    if ( $iscustom.prop('checked') ){
        $custom_sidebar.show();
        
        if ( $groupsb.prop('checked') ){
            $existing_sidebars.show();
        } else {
            $existing_sidebars.hide();
        }
        
        if ( $addrplce.prop('checked') ){
            $sidebar_add.show();
        } else {
            $sidebar_add.hide();
        }
        
    } else {
        $custom_sidebar.hide();
    }
    
    //toggle behavior on checkboxes...
    $iscustom.change( function(){
        $custom_sidebar.toggle();	
    });
    
    $grpselect.change( function(){
	$existing_sidebars.toggle();	
    });
        
     $addrplce.change( function(){
        $sidebar_add.toggle();
    });


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