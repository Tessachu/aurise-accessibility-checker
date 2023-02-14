<?php

namespace AuRise\Plugin\AccessibilityChecker;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use AuRise\Plugin\AccessibilityChecker\Utilities;

/**
 * Plugin Settings File
 *
 * @package AuRise\Plugin\AccessibilityChecker
 */
class Settings
{
    /**
     * The single instance of the class.
     *
     * @var Settings
     *
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Plugin variables of settings and options
     *
     * @var array $vars
     *
     * @since 1.0.0
     */
    public static $vars = array();

    /**
     * Main Instance
     *
     * Ensures only one instance of is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @static
     *
     * @return Settings Main instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct()
    {
        $basename = plugin_basename(AURISE_ACCESSIBILITY_CHECKER_FILE);
        $path = plugin_dir_path(AURISE_ACCESSIBILITY_CHECKER_FILE);
        $url = plugin_dir_url(AURISE_ACCESSIBILITY_CHECKER_FILE);
        $slug = dirname($basename);
        $slug_underscore = str_replace('-', '_', $slug);
        load_plugin_textdomain($slug, false, $slug . '/languages'); //Translations

        self::$vars = array(

            // Basics
            'name' => __('Accessibility Checker', 'aurise-accessibility-checker'),
            'version' => AURISE_ACCESSIBILITY_CHECKER_VERSION,
            'capability_post' => 'edit_post',
            'capability_settings' => 'manage_options',

            // URLs
            'file' => AURISE_ACCESSIBILITY_CHECKER_FILE,
            'basename' => $basename, // E.g.: "plugin-folder/file.php"
            'path' => $path, // E.g.: "/path/to/wp-content/plugins/plugin-folder/"
            'url' => $url, // E.g.: "https://domain.com/wp-content/plugins/plugin-folder/"
            //'admin_url' => admin_url(sprintf('tools.php?page=%s', $slug)), // E.g.: "https://domain.com/wp-admin/tools.php?page=plugin-folder"
            //'admin_url' => admin_url(sprintf('admin.php?page=%s', $slug)), // E.g.: "https://domain.com/wp-admin/admin.php?page=plugin-folder"
            'admin_url' => admin_url(sprintf('options-general.php?page=%s', $slug)), // E.g.: "https://domain.com/wp-admin/options-general.php?page=plugin-folder"
            'slug' => $slug, // E.g.: "plugin-folder"
            'slug_underscore' => $slug_underscore, // E.g.: "plugin_folder"
            'prefix' => $slug_underscore . '_', // E.g.: "plugin_folder_"

            //Plugin-specific Options
            'options' => array(
                //Default Settings Group
                'settings' => array(
                    'title' => __('Settings', 'aurise-accessibility-checker'),
                    'options' => array(
                        'enabled' => array(
                            'label' => __('Enabled', 'aurise-accessibility-checker'),
                            'description' => __('Display the widget fixed to the bottom of the page on the frontend for the allowed roles.', 'aurise-accessibility-checker'),
                            'default' => '1',
                            'global_override' => true,
                            'atts' => array(
                                'type' => 'switch',
                                'options' => array(
                                    '1' => __('Enabled', 'aurise-accessibility-checker'),
                                    '0' => __('Disabled', 'aurise-accessibility-checker')
                                )
                            )
                        ),
                        'allowed_roles' => array(
                            'label' => __('Allowed User Roles', 'aurise-accessibility-checker'),
                            'description' => __('A comma separated list of user roles that are allowed to use this feature.', 'aurise-accessibility-checker'),
                            'default' => 'administrator',
                            'global_override' => true,
                            'atts' => array(
                                'type' => 'text',
                                'required' => 'required'
                            )
                        ),
                        'debug_mode' => array(
                            'label' => __('Debug Mode', 'aurise-accessibility-checker'),
                            'description' => __("If enabled, it will only show to the allowed roles when the website's <code>WP_DEBUG</code> constant is set to true.", 'aurise-accessibility-checker'),
                            'default' => '0',
                            'global_override' => true,
                            'atts' => array(
                                'type' => 'switch',
                                'options' => array(
                                    '1' => __('Enabled', 'aurise-accessibility-checker'),
                                    '0' => __('Disabled', 'aurise-accessibility-checker')
                                )
                            )
                        ),
                    )
                )
            )
        );

        //Plugin Setup
        add_action('admin_init', array($this, 'register_settings')); //Register plugin option settings
        add_action('admin_menu', array($this, 'admin_menu')); //Add admin page link in WordPress dashboard
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets_admin')); //Enqueue styles/scripts for admin page
        add_filter('plugin_action_links_' . $basename, array($this, 'plugin_links')); //Add link to admin page from plugins page
    }

    //**** Plugin Settings ****//

    /**
     * Register Plugin Settings
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function register_settings()
    {
        foreach (self::$vars['options'] as $option_group_name => $group) {
            $option_group = self::$vars['prefix'] . $option_group_name;
            //Register the section
            add_settings_section(
                $option_group, //Slug-name to identify the section. Used in the `id` attribute of tags.
                $group['title'], //Formatted title of the section. Shown as the heading for the section.
                array($this, 'display_plugin_setting_section'), //Function that echos out any content at the top of the section (between heading and fields).
                self::$vars['slug'] //The slug-name of the settings page on which to show the section.
            );

            //Register the individual settings in the section
            foreach ($group['options'] as $setting_name => $setting_data) {
                $option_name = self::$vars['prefix'] . $setting_name;
                $input_type = $setting_data['atts']['type'];
                $registration_args = array();
                switch ($input_type) {
                    case 'switch':
                    case 'checkbox':
                        $type = 'integer';
                        $registration_args['sanitize_callback'] = array($this, 'sanitize_setting_bool');
                        break;
                    case 'number':
                        $type = 'number';
                        $registration_args['sanitize_callback'] = array($this, 'sanitize_setting_number');
                        break;
                    case 'text':
                        $type = 'string';
                        if (strpos(Utilities::array_has_key('class', $setting_data['atts']), 'au-color-picker') !== false) {
                            $registration_args['sanitize_callback'] = array($this, 'sanitize_setting_color');
                        } else {
                            $registration_args['sanitize_callback'] = 'sanitize_text_field';
                        }
                        break;
                    default:
                        $type = 'string';
                        $registration_args['sanitize_callback'] = 'sanitize_text_field';
                        break;
                }
                $registration_args['type'] = $type; //Valid values are string, boolean, integer, number, array, and object
                $registration_args['description'] = $setting_name;
                $registration_args['default'] = Utilities::array_has_key('default', $setting_data);

                //Register the setting
                register_setting($option_group, $option_name, $registration_args);

                //Add the field to the admin settings page (excluding the hidden ones)
                if ($input_type != 'hidden') {
                    $input_args = array(
                        'type' => $input_type,
                        'type_option' => 'string', //Option type
                        'default' => $registration_args['default'],
                        'label' => $setting_data['label'],
                        'description' => Utilities::array_has_key('description', $setting_data),
                        'global' => Utilities::array_has_key('global_override', $setting_data) ? strtoupper($option_name) : '', //Name of constant variable should it exist
                        'private' => Utilities::array_has_key('private', $setting_data),
                        'label_for' => $option_name,
                        //Attributes for the input field
                        'atts' => array(
                            'type' => $input_type,
                            'name' => $option_name,
                            'id' => $option_name,
                            'value' => get_option($option_name, $registration_args['default']), //The currently selected value (or default if not selected)
                            'class' => Utilities::array_has_key('class', $setting_data['atts']),
                            'data-default' => $registration_args['default']
                        )
                    );
                    if (Utilities::array_has_key('required', $setting_data['atts'])) {
                        $input_args['atts']['required'] = 'required';
                    }
                    //Add data attributes
                    $data_atts = Utilities::array_has_key('data', $setting_data['atts'], array());
                    if (count($data_atts)) {
                        foreach ($data_atts as $data_key => $data_value) {
                            $input_args['atts']['data-' . $data_key] = $data_value;
                        }
                    }
                    switch ($input_type) {
                        case 'select':
                            $input_args['options'] = $setting_data['atts']['options'];
                            break;
                        case 'checkbox':
                        case 'switch':
                            $input_args['label_for'] .= '_check';
                            $input_args['checked'] = checked(1, $input_args['atts']['value'], false);
                            $input_args['reverse'] = Utilities::array_has_key('reverse', $setting_data['atts']);
                            $input_args['yes'] = Utilities::array_has_key('yes', $setting_data['atts'], __('On', 'accessible-reading'));
                            $input_args['no'] = Utilities::array_has_key('no', $setting_data['atts'], __('Off', 'accessible-reading'));
                            break;
                        case 'number':
                            $input_args['atts']['min'] = Utilities::array_has_key('min', $setting_data['atts']);
                            $input_args['atts']['max'] = Utilities::array_has_key('max', $setting_data['atts']);
                            $input_args['atts']['step'] = Utilities::array_has_key('step', $setting_data['atts']);
                            //Purposely not breaking here
                        default:
                            $input_args['atts']['placeholder'] =  esc_attr(Utilities::array_has_key('placeholder', $setting_data['atts']));
                            break;
                    }
                    add_settings_field(
                        $option_name, //ID
                        esc_attr($setting_data['label']), //Title
                        array($this, 'display_plugin_setting'), //Callback (should echo its output)
                        self::$vars['slug'], //Page
                        $option_group, //Section
                        $input_args //Attributes
                    );
                }
            }
        }
    }

    /**
     * Sanitize plugin options for boolean fields
     *
     * @since 1.0.0
     *
     * @param string $value Value to sanitize.
     *
     * @return int Either a 1 or 0
     */
    public function sanitize_setting_bool($value)
    {
        return $value ? 1 : 0;
    }

    /**
     * Sanitize plugin options for number fields
     *
     * @since 1.0.0
     *
     * @param string $value Value to sanitize.
     *
     * @return int|float|string The numeric value or an empty string.
     */
    public function sanitize_setting_number($value)
    {
        return is_numeric($value) ? $value : '';
    }

    /**
     * Sanitize plugin options for color picker fields
     *
     * @since 1.0.0
     *
     * @param string $value Value to sanitize.
     *
     * @return string Sanitized and validated HEX color. Empty string otherwise.
     */
    public function sanitize_setting_color($value)
    {

        $value = sanitize_text_field($value);
        if ($this->validate_color($value)) {
            return $value;
        }
        return '';
    }

    /**
     * Validate HEX color
     *
     * @since 1.0.0
     *
     * @param string $value Value to validate.
     *
     * @return bool True if valid, false otherwise.
     */
    private function validate_color($value)
    {
        // if user insert a HEX color with #
        if (preg_match('/^#[a-f0-9]{6}$/i', $value)) {
            return true;
        }
        return false;
    }

    /**
     * Register Plugin Setting Section Callback
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function display_plugin_setting_section()
    {
        //Do nothing
    }

    /**
     * Display plugin setting input in admin dashboard
     *
     * Callback for `add_settings_field()`
     *
     * @since 1.0.0
     *
     * @param array $args Input arguments.
     *
     * @return void
     */
    public function display_plugin_setting($args = array())
    {
        /**
         * Variables that are already escaped:
         * type, name, id, value, label, required, global, private, checked, min, max, step, placeholder
         */
        if ($args['global'] && defined($args['global'])) {
            //Display constant values set in wp-config.php
            if ($args['private']) {
                // This field is readonly and do not reveal the value
                printf(
                    '<input %s />',
                    Utilities::format_atts(array_replace($args['atts'], array(
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'type' => 'password',
                        'value' => '**********'
                    )))
                );
            } else {
                // This field is readonly
                printf(
                    '<input %s />',
                    Utilities::format_atts(array_replace($args['atts'], array(
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'type' => 'text',
                        'value' => esc_attr(constant($args['global']))
                    )))
                );
            }
        } else {
            //Render the setting
            switch ($args['type']) {
                case 'hidden':
                    //Silence is golden
                    break;
                case 'switch': //Fancy Toggle Checkbox Switch
                    $checkbox_args = array(
                        'type' => 'checkbox',
                        'id' => $args['atts']['id'] . '_check',
                        'name' => $args['atts']['name'] . '_check',
                        'class' => 'input-checkbox'
                    );
                    if ($args['reverse']) {
                        $checkbox_args['class'] .= ' reverse-checkbox'; //whether checkbox should be visibly reversed
                    }
                    printf(
                        '<span class="checkbox-switch %6$s">
                                <input %1$s />
                                <input %2$s %3$s />
                                <span class="checkbox-animate">
                                    <span class="checkbox-off">%4$s</span>
                                    <span class="checkbox-on">%5$s</span>
                                </span>
                            </span>
                        </label>',
                        Utilities::format_atts(array_replace($args['atts'], array('type' => 'hidden'))), // 1 - Hidden input field
                        Utilities::format_atts(array_replace($args['atts'], $checkbox_args)), // 2 - Visible checkbox field
                        checked(($args['reverse'] ? '0' : '1'), $args['atts']['value'], false), //3 - Checked attribute, if reversed, compare against the opposite value
                        esc_attr($args['no']), //4 - on value
                        esc_attr($args['yes']), //5 - off value
                        esc_attr($args['atts']['class']) //6 - additional classes to wrapper object
                    );
                    break;
                case 'select': //Simple Drop-Down Select Field
                    printf('<select %s />', Utilities::format_atts($args['atts']));
                    foreach ($args['options'] as $key => $value) {
                        $option_name = is_array($value) ? $value['label'] : $value;
                        $option_atts = array('value' => $key);
                        if ($args['atts']['value'] == $key) {
                            $option_atts['selected'] = 'selected';
                        }
                        printf(
                            '<option %s>%s</option>',
                            Utilities::format_atts($option_atts),
                            esc_html($option_name)
                        );
                    }
                    echo ('</select>');
                    break;
                case 'checkbox':
                case 'radio':
                    printf('<input %s %s />', Utilities::format_atts($args['atts']), $args['checked']);
                    break;
                default:
                    printf('<input %s />', Utilities::format_atts($args['atts']));
                    break;
            }
        }
        if ($args['description']) {
            printf('<small class="note">%s</small>', wp_kses(
                $args['description'],
                array(
                    'a' => array('href', 'title', 'target', 'rel'),
                    'strong' => array(),
                    'em' => array(),
                    'code' => array()
                ),
                array('https')
            ));
        }
    }

    /**
     * Get Option Key for Settings
     *
     * @since 1.0.0
     *
     * @return string $id With or without a prefix, get the option name and ID
     *
     * @return array an associative array with `id` and `name` properties
     */
    private static function get_key($id)
    {
        $return = array(
            'id' => '',
            'name' => ''
        );
        if (strpos($id, self::$vars['prefix']) === 0) {
            //Prefix is included
            $return['id'] = $id; //No change, keep prefix in ID
            $return['name'] = str_replace(self::$vars['prefix'], '', $id); //Remove prefix from name
        } else {
            //Prefix is not included
            $return['name'] = $id; //No change, no prefix in name
            $return['id'] = self::$vars['prefix'] . $id; //Add prefix to ID
        }
        return $return;
    }

    /**
     * Get Plugin Setting
     *
     * This checks if a constant value was defined to override it and returns that.
     *
     * @since 1.0.0
     *
     * @param string $id Option ID, including prefix
     * @param bool $value_only If true, returns just the value of the setting. Otherwise,
     * it returns an associatve array. Default is true.
     *
     * @return string|array An associative array with the keys `value` and `constant`
     * unless $value_only was true, then it returns just the value.
     */
    public static function get($id, $value_only = true, $group = '')
    {
        $return = array(
            'value' => '',
            'constant' => false
        );
        $setting = self::get_key($id);
        //$const_name = Utilities::array_has_key('global_override', self::$vars['options'][$setting['name']]) ? strtoupper($setting['id']) : '';
        $group = $group ? $group : 'settings';
        $const_name = Utilities::array_has_key('global_override', self::$vars['options'][$group]['options'][$setting['name']]) ? strtoupper($setting['id']) : '';
        if ($const_name && defined($const_name)) {
            //Display the value overriden by the constant value
            $return['value'] = constant($const_name);
            $return['constant'] = true;
        } else {
            $return['value'] = get_option($setting['id'], self::$vars['options'][$group]['options'][$setting['name']]['default']);
        }
        //Sanitize values
        if (is_string($return['value'])) {
            $return['value'] = sanitize_text_field($return['value']);
        }
        //Return appropriate format
        if ($value_only) {
            return $return['value'];
        }
        return $return;
    }

    /**
     * Set Plugin Setting
     *
     * @since 1.0.0
     *
     * @param string $id The ID of the plugin setting, with or without the prefix
     * @param mixed $value The value of the plugin setting
     *
     * @return bool True on success, false on failure
     */
    public static function set($id, $value)
    {
        $setting = self::get_key($id);
        $updated = update_option($setting['id'], $value);
        return $updated;
    }

    //**** Plugin Management Page ****//

    /**
     * Add Admin Page
     *
     * Adds the admin page to the WordPress dashboard sidebar
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_menu()
    {
        add_submenu_page(
            'options-general.php', //Parent Slug
            self::$vars['name'] . ' ' . __('by AuRise Creative'), //Page Title
            self::$vars['name'], //Menu Title
            self::$vars['capability_settings'], //Capability
            self::$vars['slug'], //Menu Slug
            array(&$this, 'admin_page'), //Callback
            null //1 //Position
        );
    }

    /**
     * Plugin Links
     *
     * Links to display on the plugins page.
     *
     * @since 1.0.0
     *
     * @param array $links
     *
     * @return array A list of links
     */
    public function plugin_links($links)
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            self::$vars['admin_url'],
            __('Settings', 'aurise-accessibility-checker')
        );
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Admin Scripts and Styles
     *
     * Enqueue scripts and styles to be used on the admin pages
     *
     * @since 1.0.0
     *
     * @param string $hook Hook suffix for the current admin page
     *
     * @return void
     */
    public function enqueue_assets_admin($hook)
    {
        // Load only on our plugin page (a subpage of "Settings")
        if ($hook == 'settings_page_' . self::$vars['slug']) {
            //Plugin Styles
            wp_register_style(
                self::$vars['prefix'] . 'layout',
                self::$vars['url'] . 'assets/styles/pseudo-bootstrap.css',
                array(),
                WP_DEBUG ? @filemtime(self::$vars['path'] . 'assets/styles/pseudo-bootstrap.css') : self::$vars['version']
            );
            wp_enqueue_style(
                self::$vars['prefix'] . 'dashboard',
                self::$vars['url'] . 'assets/styles/admin-dashboard.css',
                array(
                    self::$vars['prefix'] . 'layout' //Pseudo bootstrap
                ),
                WP_DEBUG ? @filemtime(self::$vars['path'] . 'assets/styles/admin-dashboard.css') : self::$vars['version']
            );

            //Plugin Scripts
            wp_enqueue_script(
                self::$vars['prefix'] . 'dashboard',
                self::$vars['url'] . 'assets/scripts/admin-dashboard.js',
                array('jquery'),
                WP_DEBUG ? @filemtime(self::$vars['path'] . 'assets/scripts/admin-dashboard.js') : self::$vars['version'],
                true
            );
        }
    }

    /**
     * Display Admin Page
     *
     * HTML markup for the WordPress dashboard admin page for managing this plugin's settings.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_page()
    {
        //Prevent unauthorized users from viewing the page
        if (!current_user_can(self::$vars['capability_settings'])) {
            return;
        }
        load_template(self::$vars['path'] . 'templates/dashboard-admin.php', true, array(
            'plugin_settings' => self::$vars,
            'debug_mode' => self::get('debug_mode', true)
        ));
    }
}
