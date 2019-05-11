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

    $(document).ready(function () { $('body').bootstrapMaterialDesign(); });
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

        /* Agent Chart */

        $("#agent_analytics_modal").on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var agent = button.data('user');

            var modal = $(this);
            modal.find('#agent-name').html(agent.display_name);

            let data = {
                action: 'get_agent_analytics',
                id: agent.id
            };

            jQuery.ajax({
                type: 'POST',
                url: followed_object.ajax_url,
                data: data,
                success: function (data) {
                    initAgentChart(JSON.parse(data));
                }
            });
        });

        /* User chart */
        $("#user_analytics_modal").on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var user = button.data('user');

            var modal = $(this);
            modal.find('#user-name').html(user.display_name);

            let data = {
                action: 'get_user_analytics',
                id: user.id
            };

            jQuery.ajax({
                type: 'POST',
                url: followed_object.ajax_url,
                data: data,
                success: function (data) {
                    initUserChart(JSON.parse(data));
                }
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

        retrieveTicketMoments().then(ticketMoments => {
            initChartTickets(ticketMoments);
            initInfoBoxTickets(ticketMoments);
        });
        initHardwareChart();
        initSoftwareChart();
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

        for (let dateI = startDate.clone().startOf(unit); dateI <= endDate; dateI.add(1, unit)) {
            const todaysTickets = moments.filter(date => date.clone().startOf(unit).isSame(dateI));

            outputCounts.push({ t: dateI.toDate(), y: todaysTickets.length });
        }

        return outputCounts;
    }

    function retrieveTicketMoments() {
        return new Promise(resolve => {
            jQuery.get(followed_object.ajax_url, { action: 'get_tickets' }, response => {
                resolve(JSON.parse(response).map(dateString => moment(dateString)));
            });
        });
    }

    function addRangePicker(id, onDateRangeChangeCallback, callbackArgs = {}, args = {}) {
        let start = args.start ? args.start : moment().subtract(29, 'days');
        let end = args.end ? args.end : moment();

        const callback = (start, end) => {
            $(`#${id}`).html(`${start.format('MMMM D, YYYY')} - ${end.format('MMMM D, YYYY')}`);
            onDateRangeChangeCallback(start, end, callbackArgs);
        };

        $(`#${id}`).daterangepicker({
            startDate: start,
            endDate: args.end ? args.end : end,
            ranges: args.ranges ? args.ranges : {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 6 Months': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Past Year': [moment().subtract(1, 'Year').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Past 5 Years': [moment().subtract(5, 'Year').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, callback);

        callback(start, end);
    }

    // Updates the charts data using newly selected dates
    function onTicketDateRangeChange(start, end, args) {
        const duration = moment.duration(end.diff(start));
        if (duration.asMonths() <= 1) {
            args.ticketsChart.data.datasets[0].data = generateChartDataBetweenMoments(args.ticketMoments, start, end, 'days');
            args.ticketsChart.options.scales.xAxes[0].time = {
                unit: 'day',
                tooltipFormat: 'MMM D'
            };
        }
        else if (duration.asMonths() <= 6) {
            args.ticketsChart.data.datasets[0].data = generateChartDataBetweenMoments(args.ticketMoments, start, end, 'weeks');
            args.ticketsChart.options.scales.xAxes[0].time = {
                unit: 'day',
                tooltipFormat: 'MMM D'
            };
        }
        else if (duration.asYears() <= 3) {
            args.ticketsChart.data.datasets[0].data = generateChartDataBetweenMoments(args.ticketMoments, start, end, 'months');
            args.ticketsChart.options.scales.xAxes[0].time = {
                unit: 'month',
                tooltipFormat: 'MMMM Y'
            };
        }
        else {
            args.ticketsChart.data.datasets[0].data = generateChartDataBetweenMoments(args.ticketMoments, start, end, 'years');
            args.ticketsChart.options.scales.xAxes[0].time = {
                unit: 'year',
                tooltipFormat: 'Y'
            };
        }
        args.ticketsChart.update();
    }

    function initChartTickets(ticketMoments) {
        const ticketsChartElement = document.getElementById('chartTickets');
        if (!ticketsChartElement) return;


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

        addRangePicker('ticketsrange', onTicketDateRangeChange, { ticketsChart, ticketMoments });
    }

    function initInfoBoxTickets(ticketMoments) {
        const ticketsSubmittedToday = $('#tickets-submitted-today');
        const ticketsSubmittedWeekNum = $('#tickets-submitted-week-num');
        const ticketsSubmittedWeekUpArrow = $('#tickets-submitted-week-up');
        const ticketsSubmittedWeekDownArrow = $('#tickets-submitted-week-down');
        const ticketsSubmittedWeekPercentage = $('#tickets-submitted-week-perc');

        ticketsSubmittedToday.html(ticketMoments.filter(ticketMoment => moment().isSame(ticketMoment.clone(), 'day')).length);
        const ticketsSubmittedWeek = ticketMoments.filter(ticketMoment => ticketMoment.isBetween(moment().startOf('isoWeek'), moment())).length;
        const ticketsSubmittedLastWeek = ticketMoments.filter(ticketMoment => ticketMoment.isBetween(moment().subtract(1, 'week').startOf('isoWeek'), moment().subtract(1, 'week'))).length;
        ticketsSubmittedWeekNum.html(ticketsSubmittedWeek);
        const percentChange = Math.floor((ticketsSubmittedWeek - ticketsSubmittedLastWeek) / ticketsSubmittedWeek * 100);
        ticketsSubmittedWeekPercentage.html(percentChange.toString() + "%");
        if (percentChange >= 0) {
            ticketsSubmittedWeekUpArrow.removeClass("d-none");
            ticketsSubmittedWeekDownArrow.addClass("d-none");
            ticketsSubmittedWeekPercentage.addClass("text-success");
            ticketsSubmittedWeekPercentage.removeClass("text-danger");
        }
        else {
            ticketsSubmittedWeekUpArrow.addClass("d-none");
            ticketsSubmittedWeekDownArrow.removeClass("d-none");
            ticketsSubmittedWeekPercentage.addClass("text-danger");
            ticketsSubmittedWeekPercentage.removeClass("text-success");
        }
    }

    const baseHardwareReliabilityThreshold = 0.6;
    const baseSoftwareReliabilityThreshold = 0.6;
    const baseUserTrainingThreshold = 2;

    function initHardwareChart() {
        const hardwareChartElement = document.getElementById('hardware-chart');
        if (!hardwareChartElement) return;

        Promise.all([fetch(`${followed_object.ajax_url}?action=get_problem_hardware`).then(response => response.json()),])
        .then(([parents,]) => {
            const chart = new Chart(hardwareChartElement, {
                type: 'bar',
                options: {
                    legend: { display: false },
                    scales: {
                        xAxes: [{ stacked: true }],
                        yAxes: [{ ticks: { beginAtZero: true } }]
                    },
                    tooltips: {
                        displayColors: false
                    }
                }
            });

            const ranges = {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            };

            const device_slider = $('#device-slider');

            addRangePicker('hardwarerange', onTermDateChange, { chart, slider: device_slider, term: parents, baseReliabilityThreshold: baseHardwareReliabilityThreshold }, { start: moment().subtract(6, 'days'), ranges });
        });
    }

    function onTermDateChange(start, end, { chart, slider, term, baseReliabilityThreshold }) {
        // group into categories
        if (slider.is(':checked')) {
            chart.data = generateChartParentTermDataSet(term, baseReliabilityThreshold, start, end);
        } else {
            chart.data = generateChartSingleTermDataSet(term, baseReliabilityThreshold, start, end);
        }

        chart.update();

        slider.unbind('change');
        slider.change(e => {onTermDateChange(start, end, { chart, slider, term, baseReliabilityThreshold});})
    }

    function generateChartParentTermDataSet(term, baseReliabilityThreshold, start, end) {
        const termDated = term.filter(({ post_date }) => moment(post_date).isBetween(start, end));
        // Get occurences of each piece of hardware and convert to array.
        let termInfo = Object.values(termDated.reduce((output, term) => {
            if (!output[term.parent_name])
            {
                output[term.parent_name] = { name: term.parent_name, count: 1, items: [term.parent_name]};
            }
            else {
                output[term.parent_name].count += 1;
                if (output[term.parent_name].items.indexOf(term.child_name) === -1) {
                    output[term.parent_name].items.push(term.child_name);
                }
            }

            return output;
        }, {}));
        // Descending order sort.
        termInfo.sort((a, b) => b.count - a.count);
        termInfo = termInfo.slice(0, 5);

        const duration = moment.duration(end.diff(start));

        const dateAdjustedThreshold = baseReliabilityThreshold * duration.asDays();

        return {
            labels: termInfo.map(data => data.name),
            datasets: [
                {
                    // End threshold is the baseReliabilityThreshold (how many tickets per device per day) * days spanned in graph * items for the type.
                    // i.e. how many tickets a device has per day
                    data: termInfo.map(({items}) => Math.ceil(items.length * dateAdjustedThreshold)),
                    fill: false,
                    radius: 0,
                    backgroundColor: 'transparent',
                    borderColor: "#dc3545",
                    borderWidth: {
                        top: 2,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                    type: 'bar',
                    label: "Reliability Threshold"
                },
                {
                    label: 'Problems Submitted',
                    data: termInfo.map(data => data.count),
                    backgroundColor: barColours
                }]
        };
    }

    function generateChartSingleTermDataSet(term, baseReliabilityThreshold, start, end) {
        const termDated = term.filter(({ post_date }) => moment(post_date).isBetween(start, end));
        // Get occurences of each piece of hardware and convert to array.
        let termInfo = Object.values(termDated.reduce((output, term) => {
            if (!output[term.child_name])
            {
                output[term.child_name] = { name: term.child_name, count: 1, items: []};
            }
            else {
                output[term.child_name].count += 1;
            }

            return output;
        }, {}));
        // Descending order sort.
        termInfo.sort((a, b) => b.count - a.count);
        termInfo = termInfo.slice(0, 5);
        const duration = moment.duration(end.diff(start));

        const dateAdjustedThreshold = baseReliabilityThreshold * duration.asDays();

        return {
            labels: termInfo.map(data => data.name),
            datasets: [
                {
                    // End threshold is the baseReliabilityThreshold (how many tickets per device per day) * days spanned in graph * items for the type.
                    // i.e. how many tickets a device has per day
                    data: termInfo.map(() => Math.ceil(dateAdjustedThreshold)),
                    fill: false,
                    radius: 0,
                    backgroundColor: 'transparent',
                    borderColor: "#dc3545",
                    borderWidth: {
                        top: 2,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                    type: 'bar',
                    label: "Reliability Threshold"
                },
                {
                    label: 'Problems Submitted',
                    data: termInfo.map(data => data.count),
                    backgroundColor: barColours
                }]
        };
    }

    function initSoftwareChart() {
        const softwareChartElement = document.getElementById('software-chart');
        if (!softwareChartElement) return;
        Promise.all([fetch(`${followed_object.ajax_url}?action=get_problem_software`).then(response => response.json()), fetch(`${followed_object.ajax_url}?action=get_software_term_and_parent`).then(response => response.json())])
        .then(([parents]) => {
            const chart = new Chart(softwareChartElement, {
                type: 'bar',
                options: {
                    legend: { display: false },
                    scales: {
                        xAxes: [{ stacked: true }],
                        yAxes: [{ ticks: { beginAtZero: true } }]
                    },
                    tooltips: {
                        displayColors: false
                    }
                }
            });

            const ranges = {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            };

            const software_slider = $('#software-slider');

            addRangePicker('softwarerange', onTermDateChange, { chart, slider: software_slider, term: parents, baseReliabilityThreshold: baseSoftwareReliabilityThreshold }, { start: moment().subtract(6, 'days'), ranges });
        });
    }

    function initAgentChart(agent_object) {
        const agentTicketPieChartElement = $('#agent-ticket-pie-chart');
        const agentReassignedPieChartElement = $('#agent-reassign-pie-chart');
        if (!agentTicketPieChartElement || !agentReassignedPieChartElement) return;

        if (agentTicketPieChartElement.data('ticket-pie'))  agentTicketPieChartElement.data('ticket-pie').destroy();;
        if (agentReassignedPieChartElement.data('reassign-pie')) agentReassignedPieChartElement.data('reassign-pie').destroy();

        const closed_moments = agent_object.closed_tickets.map(dateString => moment(dateString));

        const agentTicketPieChart = new Chart(agentTicketPieChartElement, {
            type: 'pie',
            data: {
                labels: ["Ticket Unresolved", "Tickets Resolved"],
                datasets: [{
                    data: [agent_object.open_tickets],
                    backgroundColor: ['rgb(62, 150, 81)',
                        'rgb(204, 37, 41)']
                }]
            }
        });

        agentTicketPieChartElement.data('ticket-pie', agentTicketPieChart);

        const solo_moments = agent_object.solo.map(dateString => moment(dateString));
        const reassign_moments = agent_object.reassigned.map(dateString => moment(dateString));
        
        const agentReassignPieChart = new Chart(agentReassignedPieChartElement, {
            type: 'pie',
            data: {
                labels: ["Tickets Solved Individually", "Tickets Solved Collaboratively"],
                datasets: [{
                    data: [],
                    backgroundColor: ['rgb(50, 225, 255)',
                        'rgb(240, 240, 50)']
                }]
            }
        });

        agentReassignedPieChartElement.data('reassign-pie', agentReassignPieChart);

        const ranges = {
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        };

        addRangePicker('agent-ticket-range', onAgentTicketDateRangeChange, { agentTicketPieChart, closed_moments, agentReassignPieChart, solo_moments, reassign_moments }, { start: moment().subtract(6, 'days'), ranges });
    }

    // Updates the charts data using newly selected dates
    function onAgentTicketDateRangeChange(start, end, args) {
        const newAgentTicketData = generateChartDataBetweenMoments(args.closed_moments, start, end, 'days').reduce((acc, day) => {return acc += day.y;}, 0);
        if (newAgentTicketData === 0) args.agentTicketPieChart.data.datasets[0].data[1] = undefined;
        else args.agentTicketPieChart.data.datasets[0].data[1] = newAgentTicketData;
        args.agentTicketPieChart.update();

        let newAgentSoloData = generateChartDataBetweenMoments(args.solo_moments, start, end, 'days').reduce((acc, day) => {return acc += day.y;}, 0);
        let newAgentReassignData = generateChartDataBetweenMoments(args.reassign_moments, start, end, 'days').reduce((acc, day) => {return acc += day.y;}, 0);

        if (newAgentSoloData === 0) newAgentSoloData = undefined;
        if (newAgentReassignData === 0) newAgentReassignData = undefined;

        args.agentReassignPieChart.data.datasets[0].data = [newAgentSoloData, newAgentReassignData]
        args.agentReassignPieChart.update();
    }

    function initUserChart(user_object) {
        const userPieChartElement = $('#user-pie-chart');
        const userBarChartElement = $('#user-bar-chart');
        if (!userPieChartElement || !userBarChartElement) return;

        if (userPieChartElement.data('pie') || userPieChartElement.data('bar')) {
            userPieChartElement.data('pie').destroy();
            userBarChartElement.data('bar').destroy();
        }

        const closed_moments = user_object.closed_tickets.map(dateString => moment(dateString));

        const userPieChart = new Chart(userPieChartElement, {
            type: 'pie',
            data: {
                labels: ["Tickets Unresolved", "Tickets Resolved"],
                datasets: [{
                    data: [user_object.open_tickets],
                    backgroundColor: ['rgb(62, 150, 81)',
                        'rgb(204, 37, 41)']
                }]
            }
        });

        const common_problems = Object.values(user_object.common.reduce((output, problem) => {
            if (output[problem.name] === undefined) output[problem.name] = { name: problem.name, time: [moment(problem.time)], count: 1 };
            else { output[problem.name].count += 1; output[problem.name].time.push(moment(problem.time)); }
            return output;
        }, {}));

        // Descending order sort.
        common_problems.sort((a, b) => { return b.count - a.count; });

        const userBarChart = new Chart(userBarChartElement, {
            type: 'bar',
            data: {
                labels: common_problems.map((data) => { return data.name; }),
                datasets: [
                    {
                        data: Array.apply(null, new Array(common_problems.length)).map(Number.prototype.valueOf, baseUserTrainingThreshold),
                        fill: false,
                        radius: 0,
                        borderColor: '#ff0000',
                        type: 'line',
                        label: "Training Threshold"
                    },
                    {
                        label: 'Problems Submitted',
                        data: [],
                        backgroundColor: barColours
                    }]
            },
            options: {
                title: { display: true, text: 'Problem Type Submitted by User' },
                legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
            }
        });

        userPieChartElement.data('pie', userPieChart);
        userBarChartElement.data('bar', userBarChart);

        const ranges = {
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        };

        addRangePicker('user-ticket-range', onUserTicketDateRangeChange, { userPieChart, closed_moments, userBarChart, common_problems }, { start: moment().subtract(6, 'days'), ranges });
    }

    // Updates the charts data using newly selected dates
    function onUserTicketDateRangeChange(start, end, args) {
        const newUserTicketData = generateChartDataBetweenMoments(args.closed_moments, start, end, 'days').reduce((acc, day) => {return acc += day.y;}, 0);
        if (newUserTicketData === 0) args.userPieChart.data.datasets[0].data[1] = undefined;
        else args.userPieChart.data.datasets[0].data[1] = newUserTicketData;
        args.userPieChart.update();

        let tempBarData = {};
        args.common_problems.forEach(problem => tempBarData[problem.name] = { name: problem.name, time_count: generateChartDataBetweenMoments(problem.time, start, end, 'days').reduce((acc, day) => acc += day.y, 0) });
        tempBarData = Object.values(tempBarData);
        tempBarData.sort((a, b) => b.time_count - a.time_count);
        args.userBarChart.data.labels = tempBarData.map(object => object.name);
        args.userBarChart.data.datasets[1].data = tempBarData.map(object => object.time_count);
        args.userBarChart.update();
    }
})(jQuery);

