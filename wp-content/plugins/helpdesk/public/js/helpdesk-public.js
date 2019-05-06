
let hide_duplicates = true;

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
                    filter_search();
                }, false);

            search_bar.addEventListener('keyup',
                function (event) {
                    if (event.keyCode !== 13) {
                        return;
                    }

                    filter_search();
                }, false);

            var clear_filter = document.getElementsByClassName('wpas-clear-filter');
            if (clear_filter.length !== 0) {
                clear_filter[0].addEventListener('click', function (event) {
                    search_bar.value = "";
                    filter_search();
                }, false);
            }

            filter_search(list, "", "");
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
            page_filter(table);

            $('#prev-page-button').click(function () {change_page(-1, table)});
            $('#next-page-button').click(function () {change_page(1, table)});
        }

    }, false);


    let page = 0;
    const page_range = 10;

    function change_page(amount) {
        const rows = document.getElementById('wpas_ticketlist').children[1].children;
        const max_shown = Array.from(rows).reduce((acc, row) => {if (row.searched) return acc += 1; else return acc;}, 0);
        
        if ((page+amount)*page_range < 0) {page = 0;}
        else if ((page+amount)*page_range > max_shown) {page = Math.floor(max_shown/page_range);}
        else {
            page += amount;
            page_filter();
            $('#page-number').html(page + 1);
        }
    }

    function page_filter() {
        let rows = document.getElementById('wpas_ticketlist').children[1].children;
        let shown = 0;
        for (let i = 0; i < rows.length; i++) {
            rows[i].hidden = true;
            if (rows[i].searched === true){
                shown++;
                if (page*page_range <= shown && shown <= (page+1)*page_range) {
                    rows[i].hidden = null;
                }
            }

        }
    }

    function filter_search() {
        var rows = document.getElementById('wpas_ticketlist').children[1].children;
        let terms = document.getElementById('wpas_filter').value.toLowerCase();
        let status_filter = document.getElementById('status-filter');
        let status = status_filter.options[status_filter.selectedIndex].value.toLowerCase();

        for (let i = 0; i < rows.length; i++) {
            // Don't show duplicate tickets.
            if (hide_duplicates === true) {
                if (rows[i].cells[1].innerText.toLowerCase().includes("duplicate")) {
                    rows[i].searched = false;
                    continue;
                }
            }
            // Check right status is met.
            if (!rows[i].innerText.toLowerCase().includes(status)) {
                rows[i].searched = false;
                continue;
            } else {
                rows[i].searched = true;
                continue;
            }
            // Do search term search.
            rows[i].searched = rows[i].innerText.toLowerCase().includes(terms);
        }
        change_page(-page);
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