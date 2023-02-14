<?php

namespace AuRise\Plugin\AccessibilityChecker;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AuRise Utilities Class
 *
 * @package AuRise\Plugin\AccessibilityChecker
 */
class Utilities
{
    /**
     * Array Key Exists and Has Value
     *
     * @since 1.0.0
     *
     * @param string|int $key The key to search for in the array.
     * @param array $array The array to search.
     * @param mixed $default The default value to return if not found or is empty. Default is an empty string.
     *
     * @return mixed|null The value of the key found in the array if it exists or the value of `$default` if not found or is empty.
     */
    public static function array_has_key($key, $array = array(), $default = '')
    {
        //Check if this key exists in the array
        $valid_key = (is_string($key) && !empty($key)) || is_numeric($key);
        $valid_array = is_array($array) && count($array);
        if ($valid_key && $valid_array && array_key_exists($key, $array)) {
            //Always return if it's a boolean or number, otherwise only return it if it has any value
            if ($array[$key] || is_bool($array[$key]) || is_numeric($array[$key])) {
                return $array[$key];
            }
        }
        return $default;
    }

    /**
     * Format Attributes Array to String
     *
     * Can be used for shortcode attributes and form HTML fields.
     *
     * @since 1.0.0
     *
     * @param array $atts An associative array of key/value pairs to convert
     * @param string $key_prefix Optional. A string to prepend to every key
     *
     * @return string A string formatted as `%s="%s"` for every attribute separated by a space
     */
    public static function format_atts($atts = array(), $key_prefix = '')
    {
        if (is_array($atts) && count($atts)) {
            $output = array();
            foreach ($atts as $key => $value) {
                $type = gettype($value);
                $key = strtolower(trim($key_prefix . $key));
                switch ($type) {
                    case 'string':
                    case 'integer':
                        $output[] = sprintf('%s="%s"', esc_attr($key), esc_attr($value));
                        break;
                    case 'array':
                    case 'object':
                        $output[] = sprintf('%s="%s"', esc_attr($key), esc_attr(http_build_query($value)));
                        break;
                    default:
                        break;
                }
            }
            return implode(' ', $output);
        }
        return '';
    }
}

if (!function_exists('array_key_first')) {
    /**
     * Gets the first key of an array
     *
     * Gets the first key of the given array without affecting the internal array pointer.
     * This function is added for compatibility with PHP versions prior to 7.3.0
     *
     * @since 1.0.0
     *
     * @param array $array An array.
     *
     * @return int|string|null Returns the first key of array if the array is not empty; null otherwise.
     */
    function array_key_first($array = array())
    {
        foreach ($array as $key => $value) {
            return $key;
        }
        return null;
    }
}
