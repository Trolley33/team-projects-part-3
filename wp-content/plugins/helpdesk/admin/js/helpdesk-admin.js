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

            start_input.change(function () { end_input.attr('min', $(this).val()); });

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

            start_input.change(function () { end_input.attr('min', $(this).val()); });

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
        initChart1();
    });


})(jQuery);

function initChart1() {
    const myChart = document.getElementById('myChart');
    if (!myChart) return;

    const data = {
        action: 'test_ajax'
    };
    console.log(followed_object.ajax_url);
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.get(followed_object.ajax_url, data, response => {
        // const dates = JSON.parse(response);
        // console.log(dates);
        // const currentDate = new Date();
        // const currentYear = currentDate.getFullYear();
        // const currentMonth = currentDate.getMonth() + 1;

        // const monthCount = dates.reduce((acc, date) => {
        //     const dateString = date.split(' ')[0];
        //     const month = parseInt(dateString.split('-')[1]);
        //     const year = parseInt(dateString.split('-')[2]);


        //     return acc;
        // }, [0, 0]);

        // const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        // const months = [];
        // for (let i = 12; i > 0; i -= 1) {
        //     const d = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
        //     const month = monthNames[d.getMonth()];
        //     const year = d.getFullYear();
        //     months.push(month);
        // }
        // console.log(months);
        const monthCount = [35, 42, 46, 50, 43, 40, 35, 30, 31, 28, 33, 34];
        new Chart(myChart, {
            type: 'bar',
            data: {
                labels: ['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'Febuary', 'March'],
                datasets: [{
                    label: 'Tickets submitted',
                    data: monthCount,
                    // backgroundColor: 'green'
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderWidth: 1,
                    borderColor: '#777',
                    hoverBorderWidth: 1,
                    hoverBorderColor: '#000'
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Number of Tickets Submitted in the Past Year'
                    // fontSize: 25
                },
                legend: {
                    display: false,
                    position: 'right',
                    labels: {
                        fontColor: '#000'
                    }
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 0,
                        bottom: 0,
                        top: 0
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                tooltips: {
                    enabled: true
                }
            }
        });
    });
}

