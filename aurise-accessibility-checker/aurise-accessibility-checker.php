<?php

/**
 * AuRise Accessibility Checker Plugin
 *
 * @package AuRise\Plugin\AccessibilityChecker
 * @copyright Copyright (c) 2022, AuRise Creative - support@aurisecreative.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * Plugin Name: AuRise Accessibility Checker
 * Plugin URI: https://aurisecreative.com/accessibility-checker/
 * Description: Visualize how your site works with assistive technologies to improve accessibility using tota11y.
 * Version: 1.0.0
 * Author: AuRise Creative
 * Author URI: https://aurisecreative.com/
 * License: GPL v3
 * Requires at least: 5.8
 * Requires PHP: 5.6.20
 * Text Domain: aurise-accessibility-checker
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define root file
defined('AURISE_ACCESSIBILITY_CHECKER_FILE') || define('AURISE_ACCESSIBILITY_CHECKER_FILE', __FILE__);

// Define plugin version
defined('AURISE_ACCESSIBILITY_CHECKER_VERSION') || define('AURISE_ACCESSIBILITY_CHECKER_VERSION', '1.0.0');

// Load the utilities class: AuRise\Plugin\AccessibilityChecker\Utilities
require_once('includes/class-utilities.php');

// Load the settings class: AuRise\Plugin\AccessibilityChecker\Settings
require_once('includes/class-settings.php');

// Load the main plugin class: AuRise\Plugin\AccessibilityChecker\Main
require_once('includes/class-main.php');
