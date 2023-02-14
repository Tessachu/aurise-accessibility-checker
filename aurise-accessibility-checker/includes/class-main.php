<?php

/**
 * Main Plugin File
 *
 */

namespace AuRise\Plugin\AccessibilityChecker;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use AuRise\Plugin\AccessibilityChecker\Settings;

/**
 * Class Main
 *
 * @package AuRise\Plugin\AccessibilityChecker
 */
class Main
{
    /**
     * The single instance of the class.
     *
     * @var Main
     *
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Instance
     *
     * Ensures only one instance of is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @static
     *
     * @return Main Main instance.
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
        $settings = Settings::instance(); //Initialize settings

        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 20); //Register assets for frontend

    }

    /**
     * Enqueue Frontend Assets
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_assets()
    {
        if ($this->can_user_test()) {
            wp_enqueue_script(
                Settings::$vars['prefix'] . 'tota11y', //Handle
                Settings::$vars['url'] . 'assets/scripts/tota11y.min.js',
                array(),
                '0.2.0', // Version
                true // In footer
            );
        }
    }

    /**
     * Get Current User Roles
     *
     * @since 1.0.0
     *
     * @return array A sequential array of roles (as strings) if user has them. Empty array otherwise.
     */
    private function get_current_user_roles()
    {
        $current_user = wp_get_current_user();
        if ($current_user->exists() && count($current_user->roles)) {
            return $current_user->roles;
        }
        return array();
    }

    /**
     * Check if User Can Test
     *
     * Allow if the feature is enabled in Settings, AND;
     * If `WP_DEBUG` is not a requirement, OR
     * If `WP_DEBUG` is a requirement and it is truthy
     *
     * @since 1.0.0
     *
     * @return bool True if allowed, false otherwise.
     */
    private function can_user_test()
    {
        $debug = Settings::get('debug_mode', true);
        //Allow if enabled in settings AND (if debug is not a requirement OR if debug is a requirement, is it set to true)
        if (Settings::get('enabled', true) && (!$debug || ($debug && defined('WP_DEBUG') && WP_DEBUG))) {
            $allowed_roles = Settings::get('allowed_roles', true);
            if (!empty($allowed_roles)) {
                $allowed_roles = explode(',', $allowed_roles);
                $cleaned_roles = array();
                foreach ($allowed_roles as $allowed_role) {
                    $cleaned_roles[] = sanitize_text_field($allowed_role);
                }
                if (count($cleaned_roles)) {
                    $user_roles = $this->get_current_user_roles();
                    if (count($user_roles)) {
                        return count(array_intersect($cleaned_roles, $user_roles)) > 0;
                    }
                }
            }
        }
        return false;
    }
}

/**
 * Returns the main instance of Main.
 *
 * @since  1.0.0
 *
 * @return Main
 */
function aurise_accessibility_checker()
{
    return Main::instance();
}

/**
 * The global instance of the main class
 *
 * @var Main
 * @since 1.0.0
 */
global $aurise_accessibility_checker;
$aurise_accessibility_checker = aurise_accessibility_checker();//Run once to init