 jQuery( 'document' ).ready( function( $ ){
    $( '#page-specific-sidebar-settings .top-menu li a' ).click( function(){
        var toshow = $( this ).attr( 'href' ).replace( '#', '' );
        $( '.top-menu li' ).removeClass( 'current-menu-tab' );
        $( this ).parent( 'li' ).addClass( 'current-menu-tab' );
        $( '.group' ).hide().removeClass( 'current-tab' );
        $( '.' + toshow ).show().addClass( 'current-tab' );
        return false;
    } );
});