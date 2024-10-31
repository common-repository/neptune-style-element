
/**
 * Customizer Previewer
 */
( function ( wp, $ ) {
	"use strict";
  
	// Inspect preview
    var first = false;

    $('#sub-accordion-panel-neptune-style-element .panel-title', parent.document).append('<span id="inspect" style="font-size: 16px;position: fixed; left: 346px; top: 77px; border: 1px solid #000; padding: 5px 10px; background: #222; color: #fff;">+ Add Element</span>');
    $('#sub-accordion-panel-neptune-style-element .panel-title', parent.document).append('<a href="admin.php?page=neptune-style-element" id="page-setting" style="font-size: 12px; text-decoration: none; position: fixed; left: 346px; top: 46px; padding: 0 8px; background: #ff8383; color: #fff;">Setting Page</a>');
    $('.customize-panel-back').on('click',function(){
    	first = true;
    });
    var $element_active = '#inspect';
    $( parent.document ).on('click', $element_active , function() {
   		$('body').append('<span id="tail"></span>'); 	
    	first = !first;
    	if(first) {
            $(document).mouseover(function (event) {
                var $target = $(event.target);
                $(this).find('.neptune-current').removeClass('neptune-current');
                $target.addClass('neptune-current'); 

				var entry = $target.prop('tagName').toLowerCase();
				var this_class = $target.attr('class').replace(' neptune-current',''); 

				if (this_class != 'neptune-current') {
					entry = $target.attr('class').replace(' neptune-current','');
				}

                $('#tail').text(entry);
                $('#tail').css({
			       left:  event.pageX + 20,
			       top:   event.pageY - 60
			    });
                return false;
            });
        }else{
        	$('#tail').remove();
            $(document).off('mouseover').find('.neptune-current').removeClass('neptune-current');
		}       
    } );

	// Bail if the customizer isn't initialized
	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize, OldPreview;

	// Custom Customizer Preview class (attached to the Customize API)
	api.myCustomizerPreview = {
		// Init
		init: function () {
			var self = this; // Store a reference to "this"

			// When the previewer is active, the "active" event has been triggered (on load)
			this.preview.bind( 'active', function() {
				
				$('html').on('click', function( e ) {
					
					if( first ){
						var label = prompt('Please Enter Name Title Element:', '');
						if(  label != null && label != '' ) {
		                    $(document).off('mouseover').find('.neptune-current').removeClass('neptune-current');

							var parents_strict,
								parents_nostrict,
								i = 0;	
								var theseParents = $.map($(e.target).parents().not('html').not('body'), function(elm) {
									var entry = elm.tagName.toLowerCase();
									if (elm.className) {
										entry += "." + elm.className.replace(/ /g, '.');
									}
									if( i == 0 ){	
										if($(e.target).attr('class') == '' ){							
											entry = entry + ' ' + $(e.target).prop('tagName').toLowerCase();
										}else{
											entry = '.' + $(e.target).prop('class').toLowerCase().replace(/ /g, '.');
										}
									}

									i++;
									return entry;

								});

							parents_strict = theseParents[1] + ' ' + theseParents[0];

							theseParents.reverse();
							parents_nostrict = theseParents.join(" ");



							var PreviewData = {
								parents : {
									'strict'     : parents_strict,
									'non_strict' : parents_nostrict
								},

								label   : label,
								default : $( e.target ).css('color')
							};
						
							self.preview.send( 'crystal-custom-event', PreviewData );
						
						}
					}
				} );
					
			} );
		}
	};

	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	OldPreview = api.Preview;
	api.Preview = OldPreview.extend( {
		initialize: function( params, options ) {
			// Store a reference to the Preview
			api.myCustomizerPreview.preview = this;

			// Call the old Preview's initialize function
			OldPreview.prototype.initialize.call( this, params, options );
		}
	} );

	// Document ready
	$( function () {
		// Initialize our Preview
		api.myCustomizerPreview.init();
	} );
} )( window.wp, jQuery );