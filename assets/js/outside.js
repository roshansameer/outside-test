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
				$('.outside-event-type-filter').select2({
					width: 'resolve'
				});
			});
			$(document).on('change', '#outside_event_filter', function(e) {
				OutSide.filterByEventType(this, e);
			});
			$(document).on('change', '#outside_month_filter', function(e) {
				OutSide.filterByEventType(this, e);
			});
			$(document).on('change', '#outside_tag_filter', function(e) {
				OutSide.filterByEventType(this, e);
			});
	   },

	   filterByEventType: function(el, e) {
			e.preventDefault();

			var $this    = $(el),
				tax_id = $('#outside_event_filter').val();
				month  = $('#outside_month_filter').val();
				tag    = $('#outside_tag_filter').val();

			var data =  {
				action  : 'outside_filter_event_type',
				tax_ids : tax_id,
				month   : month,
				tag     : tag,
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