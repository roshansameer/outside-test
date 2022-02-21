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
			$(document).ready(function() {
				$('.outside-month-filter, .outside-event-type-filter').select2({
					width: 'resolve'
				});
			});
			$(document).on('change', '#outside_event_filter', function(e) {
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
					$('.category-posts-container').html( response.data.posts );
				}
			});
	   },
	}
   OutSide.init();
})(jQuery);