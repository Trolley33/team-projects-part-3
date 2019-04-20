(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
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

    /* This was written without jQuery prepared, so can be converted at some point */

    /* --- Ticket submit page --- */
    document.addEventListener('DOMContentLoaded', function () {
        var tag_label = document.getElementById('wpas_ticket-tag');
        var os_label = document.getElementById('wpas_OS');
        var hardware_label = document.getElementById('wpas_hardware');
        var software_label = document.getElementById('wpas_software');

        // Put a page break after the label to match the rest of the style.
        if (tag_label !== null && os_label !== null && hardware_label !== null && software_label !== null) {
            tag_label.labels[0].after(document.createElement('BR'));
            os_label.labels[0].after(document.createElement('BR'));
            hardware_label.labels[0].after(document.createElement('BR'));
            software_label.labels[0].after(document.createElement('BR'));
        }

        /* --- Ticket list filters --- */

        // Check if both status filter and search bar are on this page, meaning we are on list.php
        var search_bar = document.getElementById('wpas_filter');
        var status_filter = document.getElementById('status-filter');
        if (search_bar !== null && status_filter !== null) {
            var list = document.getElementById('wpas_ticketlist');
            status_filter.addEventListener('change',
                function (event) {
                    var status = status_filter.options[status_filter.selectedIndex].value.toLowerCase();
                    var search = search_bar.value.toLowerCase();

                    filter_search(list, status, search);
                }, false);

            search_bar.addEventListener('keyup',
                function (event) {
                    if (event.keyCode !== 13) {
                        return;
                    }
                    var status = status_filter.options[status_filter.selectedIndex].value.toLowerCase();
                    var search = this.value.toLowerCase();

                    filter_search(list, status, search);
                }, false);

            var clear_filter = document.getElementsByClassName('wpas-clear-filter');
            if (clear_filter.length !== 0) {
                clear_filter[0].addEventListener('click', function (event) {
                    var status = status_filter.options[status_filter.selectedIndex].value.toLowerCase();
                    filter_search(list, status, '');
                }, false);
            }
        }

        // If the table exists, sort by ratings.
        var table = document.getElementById("wpas_ticketlist");
        if (table !== null) {
            var rows, switching, i, x, y, shouldSwitch;
            switching = true;
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                    one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[0];
                    y = rows[i + 1].getElementsByTagName("TD")[0];
                    // Check if the two rows should switch place:
                    if (Number(x.innerHTML) < Number(y.innerHTML)) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                    and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
    }, false);

    function filter_search(table_list, status, terms) {
        var rows = table_list.children[1].children;
        for (let i = 0; i < rows.length; i++) {
            // Check right status is met.
            if (!rows[i].innerText.toLowerCase().includes(status)) {
                rows[i].hidden = true;
                continue;
            } else {
                rows[i].hidden = null;
            }
            // Do search term search.
            if (!rows[i].innerText.toLowerCase().includes(terms)) {
                rows[i].hidden = true;
            } else {
                rows[i].hidden = null;
            }
        }
    }


})(jQuery);

function follow_thread(user_id, post_id) {
    var followed_button = document.getElementById('wphd-follow-button');

    jQuery.ajax({
        type: 'POST',
        dataType: 'html',
        url: followed_object.ajax_url,
        data: 'action=follow_thread&pid=' + post_id + '&uid=' + user_id + "&follow=1",
        success: function (event) {
            followed_button.onclick = function () {
                unfollow_thread(user_id, post_id);
            };
            followed_button.innerHTML = "Unfollow this ticket?";
        }
    });

}

function unfollow_thread(user_id, post_id) {
    var followed_button = document.getElementById('wphd-follow-button');

    jQuery.ajax({
        type: 'POST',
        dataType: 'html',
        url: followed_object.ajax_url,
        data: 'action=follow_thread&pid=' + post_id + '&uid=' + user_id + "&follow=0",
        success: function (event) {
            followed_button.onclick = function () {
                follow_thread(user_id, post_id);
            };
            followed_button.innerHTML = "Follow this ticket?";
        }
    });
}