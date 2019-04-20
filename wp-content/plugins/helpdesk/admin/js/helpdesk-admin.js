(function ($) {
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
        $("#timeoff_edit_modal").on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var timeoff_object = button.data('timeoff');

            var modal = $(this);

            let reason_input = modal.find('.modal-body input[name="edit_reason"]');
            let start_input = modal.find('.modal-body input[name="edit_start"]');
            let end_input = modal.find('.modal-body input[name="edit_end"]');

            reason_input.val(timeoff_object.reason);
            start_input.val(timeoff_object.time_start);
            end_input.val(timeoff_object.time_end);
            end_input.attr('min', timeoff_object.time_start);

            start_input.change( function () {end_input.attr('min', $(this).val());});

            modal.find('.modal-footer #submit-button').unbind('click');
            modal.find('#edit-form').submit(function () {
                const reason = reason_input.val();
                const start = start_input.val();
                const end = end_input.val();

                if (reason === "" || start === "0000-00-00" || end === "0000-00-00") {
                    return false;
                }

                let data = {
                    action: 'edit_timeoff',
                    tid: timeoff_object.id,
                    reason: reason,
                    time_start: start,
                    time_end: end,
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
                return false;
            });
        });

        $("#timeoff_create_modal").on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            let reason_input = modal.find('.modal-body input[name="create_reason"]');
            let start_input = modal.find('.modal-body input[name="create_start"]');
            let end_input = modal.find('.modal-body input[name="create_end"]');

            start_input.change( function () {end_input.attr('min', $(this).val());});

            modal.find('.modal-footer #submit-button').unbind('submit');
            modal.find('#create-form').submit(function () {
                const reason = reason_input.val();
                const start = start_input.val();
                const end = end_input.val();

                if (reason === "" || start === "0000-00-00" || end === "0000-00-00") {
                    return false;
                }

                let data = {
                    action: 'edit_timeoff',
                    reason: reason,
                    time_start: start,
                    time_end: end,
                    type: 'new'
                };

                jQuery.ajax({
                    type: 'POST',
                    url: followed_object.ajax_url,
                    data: data,
                    success: function (data) {
                        location.reload();
                    }
                });
                return false;
            });
        });

        $('.timeoff_delete_button').click(function () {
            let button = $(this);
            let tid = button.val();

            let flag = confirm("Really delete this time off?");
            if (!flag) {
                return;
            }

            let data = {
                action: 'edit_timeoff',
                tid: tid,
                type: 'delete'
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
})(jQuery);

