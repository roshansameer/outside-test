/**
 * OutSide JS
 * global outside_params
 */
 ;(function($) {
	var OutSide = {
		/**
		* Start the engine.
		*
		*/
		init: function() {
			OutSide.bindUIActions();
		},

	   /**
		* Element bindings.
		*
		*/
		bindUIActions: function() {
			$(document).on('change', '.outside-event-type', function(e) {
				OutSide.filterByEventType(this, e);
			});
	   },

	   filterByEventType: function(el, e) {
			e.preventDefault();

			var $this    = $(el),
				tax_id = $this.val();

			var data =  {
				action  : 'outside_filter_event_type',
				id      : tax_id,
				security: outside_params.ajax_nonce
			}
			$.ajax({
				url: outside_params.ajax_url,
				data: data,
				type: 'POST',
				success: function(response) {
					console.log( response );
				}
			});
	   },
	}
   OutSide.init();
})(jQuery);