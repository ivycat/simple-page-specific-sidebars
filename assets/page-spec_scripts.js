 
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

    
});