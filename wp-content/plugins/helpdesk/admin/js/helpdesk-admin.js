(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function () {
		var table = $('.display').dataTable();
		$("#timeoff_modal").on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var timeoff_object = button.data('timeoff');

			var modal = $(this);
			let reason_input = modal.find('.modal-body #modal_reason');
			reason_input.val(timeoff_object.reason)
			let start_input = modal.find('.modal-body #modal_start');
			start_input.val(timeoff_object.time_start);
			let end_input = modal.find('.modal-body #modal_end');
			end_input.val(timeoff_object.time_end);

			modal.find('.modal-footer #submit-button').unbind('click');
			modal.find('.modal-footer #submit-button').click(function() {
				let data = {
					action: 'edit_timeoff',
					tid: timeoff_object.id,
					reason: reason_input.val(),
					time_start: start_input.val(),
					time_end: end_input.val(),
					type: 'update'
				};

				jQuery.ajax({
					type: 'POST',
					url: followed_object.ajax_url,
					data: data,
					success: function (data) {
						location.reload();
					}
				});
			});
		});
	});
})( jQuery );


