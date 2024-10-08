<?php
class GFCLH_Settings {
    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
        add_filter('gform_settings_menu', array($this, 'add_menu_item'));
        add_action('gform_settings_conditional_logic_highlighter', array($this, 'settings_page'));
    }

    public function register_settings() {
        register_setting('gfclh_options', 'gfclh_options', array($this, 'sanitize_options'));
    }

    public function add_menu_item($menu_items) {
        $menu_items[] = array(
            'name' => 'conditional_logic_highlighter',
            'label' => __('CL Highlighter', 'conditional-logic-highlighter-for-gravity-forms'),
            'icon' => 'dashicons-visibility',
            'callback' => array($this, 'settings_page')
        );
        return $menu_items;
    }

    public function settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $options = get_option('gfclh_options');
        ?>
        <h3><span><i class="gform-icon gform-icon--visibility"></i> <?php esc_html_e('Conditional Logic Highlighter Settings', 'conditional-logic-highlighter-for-gravity-forms'); ?></span></h3>
        <form id="gform-settings" action="options.php" method="post">
            <?php settings_fields('gfclh_options'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Highlight in Admin', 'conditional-logic-highlighter-for-gravity-forms'); ?></th>
                    <td><input type="checkbox" name="gfclh_options[highlight_admin]" value="1" <?php checked(1, $options['highlight_admin'], true); ?> /></td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Highlight in Frontend', 'conditional-logic-highlighter-for-gravity-forms'); ?>
                        <p class="description" style="color: #d63638;">
                            <?php esc_html_e('Warning: This option is for debugging purposes only and is not recommended for public use.', 'conditional-logic-highlighter-for-gravity-forms'); ?>
                        </p>
                    </th>
                    <td><input type="checkbox" name="gfclh_options[highlight_frontend]" value="1" <?php checked(1, $options['highlight_frontend'], true); ?> /></td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Admin CSS', 'conditional-logic-highlighter-for-gravity-forms'); ?>
                        <p class="description"><?php esc_html_e('Applied to class:', 'conditional-logic-highlighter-for-gravity-forms'); ?> <code>.gfield_conditional_logic_active</code></p>
                    </th>
                    <td><textarea name="gfclh_options[admin_css]" rows="3" cols="50"><?php echo esc_textarea($options['admin_css']); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Frontend CSS', 'conditional-logic-highlighter-for-gravity-forms'); ?>
                        <p class="description"><?php esc_html_e('Applied to class:', 'conditional-logic-highlighter-for-gravity-forms'); ?> <code>.gfield_conditional_logic_active</code></p>
                    </th>
                    <td><textarea name="gfclh_options[frontend_css]" rows="3" cols="50"><?php echo esc_textarea($options['frontend_css']); ?></textarea></td>
                </tr>
            </table>
            <p class="submit">
                <button type="submit" id="gform-settings-save" name="gform-settings-save" value="save" form="gform-settings" class="primary button large">
                    <?php esc_html_e('Save Settings', 'conditional-logic-highlighter-for-gravity-forms'); ?> &nbsp;→
                </button>
            </p>
        </form>
        <?php
    }

    public function sanitize_options($input) {
        $input['highlight_admin'] = isset($input['highlight_admin']) ? 1 : 0;
        $input['highlight_frontend'] = isset($input['highlight_frontend']) ? 1 : 0;
        $input['admin_css'] = sanitize_textarea_field($input['admin_css']);
        $input['frontend_css'] = sanitize_textarea_field($input['frontend_css']);
        return $input;
    }
}