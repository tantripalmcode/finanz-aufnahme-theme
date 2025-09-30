<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Cuts a string to a maximum of 100 characters without cutting words in half.
 *
 * If the string is 100 characters or less, it returns the original string.
 * Otherwise, it truncates the string at the last complete word within the first
 * 100 characters and appends ' ...' to indicate truncation.
 *
 * @param string $str The string to be cut.
 * @return string The cut string, potentially with ' ...' appended.
 */
if (!function_exists('cut_string')) {
    function cut_string($str)
    {
        if (strlen($str) <= 100) {
            return $str;
        }

        $cut_string = substr($str, 0, 100);

        if ($cut_string[99] !== ' ') {
            $last_space_position = strrpos($cut_string, ' ');
            if ($last_space_position !== false) {
                $cut_string = substr($cut_string, 0, $last_space_position);
            }
        }

        return $cut_string . ' ...';
    }
}

/**
 * Get the image size based on the given parameters.
 *
 * @param string $image_size The predefined image size or "custom" for a custom size.
 * @param string $image_size_custom The custom size in the format "WIDTHxHEIGHT".
 *
 * @return mixed The size as a string or an array with width and height, or an empty string if the format is incorrect.
 */
if (!function_exists('budi_get_image_size')) {
    function budi_get_image_size($image_size, $image_size_custom)
    {
        $size = $image_size;

        if ($image_size === "custom") {
            $image_size_custom = str_replace(' ', '', $image_size_custom);

            if (strpos($image_size_custom, 'x') !== false) {
                $size = explode('x', $image_size_custom);
            } else {
                $size = '';
            }
        }

        return $size;
    }
}

/**
 * Replace special characters in the given content.
 *
 * @param string $content The content to fix.
 * @return string The content with special characters replaced.
 */
if (!function_exists('budi_fix_special_characters')) {
    function budi_fix_special_characters($content)
    {
        $search  = ['`{`', '`}`', '&amp;'];
        $replace = ['[', ']', '&'];
        return str_replace($search, $replace, $content);
    }
}


/**
 * Leitet alle Kategorieseiten auf die /news Seite um.
 *
 * Diese Funktion überprüft, ob die aktuelle Seite eine Kategorieseite ist.
 * Wenn dies der Fall ist, wird der Benutzer auf die /news Seite weitergeleitet.
 *
 * @return void
 */
function redirect_all_categories_to_news()
{
    if (is_category()) {
        wp_redirect(home_url('/news/'), 301);
        exit();
    }
}
// add_action('template_redirect', 'redirect_all_categories_to_news');

/**
 * Get the formatted date for the given date string.
 *
 * @param string $date_string The date string to format.
 * @return string The formatted date.
 */
function budi_get_formatted_date($date_string)
{
    // Check if date string is empty or invalid
    if (empty($date_string) || !strtotime($date_string)) {
        return '';
    }

    try {
        $date = new DateTime($date_string);
        $formatter = new IntlDateFormatter(
            'de_DE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            null,
            null,
            'd. MMMM Y'
        );
        return $formatter->format($date);
    } catch (Exception $e) {
        return '';
    }
}