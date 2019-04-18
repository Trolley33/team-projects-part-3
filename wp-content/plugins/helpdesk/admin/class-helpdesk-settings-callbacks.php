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
        <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'helpdesk');?></p>
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
        <select id="<?php echo esc_attr($args['label_for']); ?>"
        data-custom="<?php echo esc_attr($args['helpdesk_custom_data']); ?>"
        name="helpdesk_options[<?php echo esc_attr($args['label_for']); ?>]"
        >
        <option value="red" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
        <?php esc_html_e('red pill', 'wporg');?>
        </option>
        <option value="blue" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
        <?php esc_html_e('blue pill', 'wporg');?>
        </option>
        </select>
        <p class="description">
        <?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg');?>
        </p>
        <p class="description">
        <?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg');?>
        </p>
        <?php
}

    public function timeoff_manager (){
        ?>
        <h1>Time Off Manager</h1>
        <table class="display">
            <thead>
                <tr>
                    <th>ID</th><th>Time Off Reason</th><th>Start</th><th>End</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>0</td><td>Example</td><td>2019-04-01</td><td>2019-04-30</td></tr>
            </tbody>
        </table>
        <?
    }
}