
/**
 * Customizer Communicator
 */
( function ( exports, $ ) {
    "use strict";

    
    var api = wp.customize, OldPreviewer;
    // Custom Customizer Previewer class (attached to the Customize API)
    api.myCustomizerPreviewer = {
        // Init
        init: function () {
            var self = this; // Store a reference to "this" in case callback functions need to reference it
            $('#accordion-panel-neptune-style-element .accordion-section-title').css('background', '#ecfdff');

            if( document.cookie.indexOf('focus=') >= 0 ){
                var c = document.cookie.split('; ');
                var cookies = {};
                for(var i=c.length-1; i>=0; i--){
                   var C = c[i].split('=');
                   cookies[C[0]] = C[1];
                }

                $('.wp-full-overlay').addClass('in-sub-panel section-open');
                $('#sub-accordion-panel-neptune-style-element').addClass('current-panel');
                $('.accordion-section .customize-control-notifications-container').css('display', 'none');
                $('.accordion-section-title').attr('tabindex', 0);

                if ( cookies['elem'] ) {  
                    var $elem = '#sub-accordion-section-' + cookies['elem'] + '_font';             
                   $($elem).addClass('open');
                }
            }

            // Listen to the "my-custom-event" event has been triggered from the Previewer
            this.preview.bind( 'crystal-custom-event', function( data ) {
                $.post(
                    NeptuneCustomData.ajaxurl,
                    {
                        action: 'customize-new-object',
                        label:   data.label,
                        selector: data.parents.strict,
                    }, function (data) {
                        location.reload();
                    }
                );
            } );
        }
    };

    /**
     * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
     *
     * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
     */
    OldPreviewer = api.Previewer;
    api.Previewer = OldPreviewer.extend( {
        initialize: function( params, options ) {
            // Store a reference to the Previewer
            api.myCustomizerPreviewer.preview = this;

            // Call the old Previewer's initialize function
            OldPreviewer.prototype.initialize.call( this, params, options );
        }
    } );

    // Document Ready
    $( function() {
        // Initialize our Previewer
        api.myCustomizerPreviewer.init();
    } );
} )( wp, jQuery );