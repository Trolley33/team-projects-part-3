<?php

/**
 * The admin settings callbacks
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/settings_callbacks
 */

/**
 * The admin settings callbacks.
 *
 *
 * @package    Helpdesk
 * @subpackage Helpdesk/settings_callbacks
 * @author     Your Name <email@example.com>
 */
class Helpdesk_Settings_Callbacks
{

    public function section_developers($args)
    {
        ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'helpdesk'); ?></p>
<?php
}

public function dashboard()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('helpdesk_dashboard_messages', 'helpdesk_dashboard_message', __('Settings Saved', 'helpdesk_dashboard'), 'updated');
    }

    // show error/update messages
    settings_errors('helpdesk_dashboard_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "helpdesk_dashboard"
            settings_fields('helpdesk_dashboard');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('helpdesk_dashboard');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}
// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
public function field_pill($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('helpdesk_options');
    // output the field
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['helpdesk_custom_data']); ?>" name="helpdesk_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="red" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
            <?php esc_html_e('red pill', 'wporg'); ?>
        </option>
        <option value="blue" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
            <?php esc_html_e('blue pill', 'wporg'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg'); ?>
    </p>
    <p class="description">
        <?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg'); ?>
    </p>
<?php
}

public function timeoff_manager()
{
    global $wpdb;
    $uid = get_current_user_id();
    $query = "SELECT * FROM wp_timeoff WHERE userid='$uid' ORDER BY time_start DESC";
    $results = $wpdb->get_results($query);

    $this->make_timeoff_create_modal();
    $this->make_timeoff_edit_modal();
    ?>
    <h1>Time Off Manager</h1>
    <hr />
    <h4>Time Booked Off For: <?php echo wp_get_current_user()->display_name; ?></h4>
    <button class='btn btn-secondary' data-toggle='modal' data-target='#timeoff_create_modal'>Create New</button>
    <br /><br />
    <table class="display compact cell-border">
        <thead>
            <tr>
                <th>ID</th>
                <th>Time Off Reason</th>
                <th>Start</th>
                <th>End</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($results as $key => $value) {
                echo "<tr>";

                echo "<td>$value->id</td>";
                echo "<td>$value->reason</td>";
                echo "<td>$value->time_start</td>";
                echo "<td>$value->time_end</td>";
                echo "<td><button class='btn btn-secondary' data-timeoff='" . json_encode($value) . "' data-toggle='modal' data-target='#timeoff_edit_modal'>Edit</button></td>";
                echo "<td><button class='btn btn-danger timeoff_delete_button' value='$value->id' '>Delete</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<?
}

private function make_timeoff_edit_modal()
{
    ?>
    <div id="timeoff_edit_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Timeoff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit-form">
                    <div class="modal-body">

                        <label>
                            Reason:
                            <input required class="form-control" type="text" name="edit_reason" />
                        </label>
                        <br />
                        <label>
                            Start Date:
                            <input required class="form-control start-date" type="date" name="edit_start" />
                        </label>
                        <br />
                        <label>
                            End Date:
                            <input required class="form-control end-date" type="date" name="edit_end" />
                        </label>
                        <br />

                    </div>
                    <div class="modal-footer">
                        <input id="submit-button" type="submit" class="btn btn-primary" value="Save Changes" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?
}

private function make_timeoff_create_modal()
{
    ?>
    <div id="timeoff_create_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book New Timeoff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="create-form">
                    <div class="modal-body">

                        <label>
                            Reason:
                            <input required class="form-control" type="text" name="create_reason" />
                        </label>
                        <br />
                        <label>
                            Start Date:
                            <input required class="form-control start-date" type="date" name="create_start" />
                        </label>
                        <br />
                        <label>
                            End Date:
                            <input required class="form-control end-date" type="date" name="create_end" />
                        </label>
                        <br />

                    </div>
                    <div class="modal-footer">
                        <input id="submit-button" type="submit" class="btn btn-primary" value="Submit" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?
}

function analytics_page()
{
    ?>
    <div class="container-fluid">
        <h2>Tickets Overview</h2>
        <h4>Overall Submitted Tickets</h4>
        <div class="row">
            <div class="col-xl-8">
                <div class="card card-body">
                    <div class="d-flex d-flex justify-content-between">
                        <h5 class="input-group-text">Tickets Submitted</h5>
                        <button id="ticketsrange" class="btn date-button btn-outline-info"></button>
                    </div>
                    <canvas id="chartTickets"></canvas>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="row">
                    <div class="col-sm-6 col-xl-12">
                        <div class="card card-body">
                            <small class="text-muted">TICKETS SUBMITTED TODAY</small>
                            <h1 id="tickets-submitted-today"></h1>
                            <hr />
                            <small class="text-muted">TICKETS SUBMITTED THIS WEEK</small>
                            <h5 class="card-title">
                                <span id="tickets-submitted-week-num" style="margin-right: 0.5em"></span>
                                <span id="tickets-submitted-week-up" class="arrow-up d-none"></span>
                                <span id="tickets-submitted-week-down" class="arrow-down d-none"></span>
                                <span id="tickets-submitted-week-perc" class="text-success"></span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-12">
                        <div class="card card-body">
                            <div class="d-flex d-flex justify-content-between">
                                <h5 class="input-group-text">Tickets Submitted</h5>
                                <button id="ticketsrange" class="btn date-button btn-outline-info"></button>
                            </div>
                            <canvas id="chartTickets"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Dummy Card</small>
                        <h1>##</h1>
                        <hr />
                        <small class="text-muted">Dummy Card</small>
                        <h5 class="card-title"><span>##</span> <span>##</span></h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <canvas id="hardware-chart"></canvas>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <canvas id="software-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
<?php
}

public function agent_analytics()
{
    global $wpdb;

    $query = "
            SELECT wp_users.id, wp_users.display_name FROM wp_users
            JOIN wp_usermeta
            ON wp_users.id = wp_usermeta.user_id
            WHERE wp_usermeta.meta_key = 'wp_wpas_can_be_assigned' 
            AND wp_usermeta.meta_value = 'yes';
        ";

    $results = $wpdb->get_results($query);

    $this->make_agent_analytics_modal()
    ?>
    <h1>Agent Analytics</h1>
    <hr />
    <br /><br />
    <table class="display compact cell-border">
        <thead>
            <tr>
                <th>Agent ID</th>
                <th>Agent Name</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($results as $key => $value) {
                echo "<tr>";

                echo "<td>$value->id</td>";
                echo "<td>$value->display_name</td>";
                echo "<td><button class='btn btn-secondary' data-user='" . json_encode($value) . "' data-toggle='modal' data-target='#agent_analytics_modal'>View</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<?
}

public function user_analytics()
{
    global $wpdb;

    $query = "
        SELECT wp_users.id, wp_users.display_name FROM wp_users
        JOIN wp_usermeta
          ON wp_users.id = wp_usermeta.user_id
        WHERE 
              wp_usermeta.meta_key = 'wp_user_level' 
          AND wp_usermeta.meta_value = '0';
    ";

    $results = $wpdb->get_results($query);

    $this->make_user_analytics_modal()
    ?>
    <h1>Agent Analytics</h1>
    <hr />
    <br /><br />
    <table class="display compact cell-border">
        <thead>
        <tr>
            <th>User ID</th>
            <th>User Name</th>
            <th>View</th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($results as $key => $value) {
            echo "<tr>";

            echo "<td>$value->id</td>";
            echo "<td>$value->display_name</td>";
            echo "<td><button class='btn btn-secondary' data-user='" . json_encode($value) . "' data-toggle='modal' data-target='#user_analytics_modal'>View</button></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <?
}

function make_agent_analytics_modal()
{
    ?>
    <div id="agent_analytics_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg"  role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Viewing Graph for: <span id="agent-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <button id="agent-ticket-range" class="btn date-button btn-outline-info"></button>
                    <div class="container">
                        <div id="no-data" hidden>No data found.</div>
                        <canvas id="agent-pie-chart"></canvas>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?
}

    function make_user_analytics_modal()
    {
        ?>
        <div id="user_analytics_modal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Viewing Graph for: <span id="user-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <button id="user-ticket-range" class="btn date-button btn-outline-info"></button>
                        <div class="container">
                            <div id="no-data" style="display: none;">No data found.</div>
                            <canvas id="user-pie-chart"></canvas>
                        </div>

                        <div class="container">
                            <canvas id="user-bar-chart"></canvas>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?
    }
}

?>