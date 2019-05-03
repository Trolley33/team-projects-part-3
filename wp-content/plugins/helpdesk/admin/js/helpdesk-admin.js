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
        initChartTickets();
        initHardwareChart();
    });

    const barColours = [
        'rgba(57, 106, 177, 0.8)',
        'rgba(218, 128, 48, 0.8)',
        'rgba(62, 150, 81, 0.8)',
        'rgba(204, 37, 41, 0.8)',
        'rgba(83, 81, 84, 0.8)',
        'rgba(107, 76, 154, 0.8)',
        'rgba(146, 36, 40, 0.8)',
        'rgba(148, 139, 61, 0.8)'
      ];

    function getNumberOfBarColours(amount) {
        const colours = [];

        for (let i = 0; i < Math.floor(amount / barColours.length); i++) {
            colours.push(...barColours);
        }

        colours.push(...barColours.slice(0, amount % barColours.length));
        return colours;
    }

    function generateChartDataBetweenMoments(moments, startDate, endDate, unit) {
        const outputCounts = [];

        for (let dateI = startDate.clone().startOf(unit); dateI < endDate; dateI.add(1, unit)) {
            const todaysTickets = moments.filter(date => date.clone().startOf(unit).isSame(dateI));

            outputCounts.push({ t: dateI.toDate(), y: todaysTickets.length });
        }

        return outputCounts;
    }

    function initChartTickets() {
        const ticketsChartElement = document.getElementById('chartTickets');
        if (!ticketsChartElement) {
            console.warn("tickets chart not found in HTML. Unabled to show chart.");
            return;
        }

        const data = {
            action: 'get_tickets'
        };

        jQuery.get(followed_object.ajax_url, data, response => {
            const ticketMoments = JSON.parse(response).map(dateString => moment(dateString));

            // Creates the base tickets chart
            const ticketsChart = new Chart(ticketsChartElement, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Tickets submitted',
                        backgroundColor: getNumberOfBarColours(1)[0]
                    }]
                },
                options: {
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'month',
                                tooltipFormat: 'MMMM Y'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: value => value % 1 === 0 ? value : null
                            }
                        }]
                    }
                }
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

            // Updates the charts data using newly selected dates
            function onTicketDateRangeChange(start, end) {
                $('#ticketsrange span').html(`${start.format('MMMM D, YYYY')} - ${end.format('MMMM D, YYYY')}`);
                const duration = moment.duration(end.diff(start));
                console.log(ticketMoments);

                if (duration.get('months') <= 3) {
                    ticketsChart.data.datasets[0].data = generateChartDataBetweenMoments(ticketMoments, start, end, 'days');
                    ticketsChart.options.scales.xAxes[0].time = {
                        unit: 'day',
                        tooltipFormat: 'MMM D'
                    };
                }
                else if (duration.get('years') <= 10) {
                    ticketsChart.data.datasets[0].data = generateChartDataBetweenMoments(ticketMoments, start, end, 'months');
                    ticketsChart.options.scales.xAxes[0].time = {
                        unit: 'month',
                        tooltipFormat: 'MMMM Y'
                    };
                }

                ticketsChart.update();
            }

            $('#ticketsrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Past Year': [moment().subtract(1, 'Year').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, onTicketDateRangeChange);

            onTicketDateRangeChange(start, end);
        });

    }

})(jQuery);


function initHardwareChart() {
    const hardwareChart = document.getElementById('hardware-chart');
    if (!hardwareChart) return;

    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.get(followed_object.ajax_url, {action: 'get_problem_hardware_past_year'}, response => {
        const hardware = JSON.parse(response).map(data => {return data});
        // Want an object where each name is the identifier (for 'indexing').
        const hardwareObjects = Array.from(new Set(hardware)).reduce((obj, item) => {
            obj[item.name] = item;
            return obj;
        }, {});

        // Calculate number of occurrences per hardware.
        hardware.forEach((data) => {if (hardwareObjects[data.name].count !== undefined) hardwareObjects[data.name].count += 1; else hardwareObjects[data.name].count = 0;});

        // Convert object into array of objects.
        const hardwareInfo = Object.values(hardwareObjects);
        // Descending order sort.
        hardwareInfo.sort((a, b) => {return b.count - a.count});

        new Chart(hardwareChart, {
            type: 'bar',
            data: {
                labels: hardwareInfo.map((data) => {return data.name}),
                datasets: [{
                    label: 'Problems Submitted',
                    data: hardwareInfo.map((data) => {return data.count}),
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
                    ]
                }]
            },
            options: {
                title: { display: true, text: 'Number of Tickets Hardware Involved In (past month).' },
                legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
            }
        });
    });
}
