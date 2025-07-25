<?php

namespace App\Helpers;

use Mews\Purifier\Facades\Purifier;

class SanitizeHelper
{
    /**
     * Sanitize a single input value
     *
     * @param mixed $value
     * @param bool $allowHtml
     * @return mixed
     */
    public static function sanitize($value, $allowHtml = false)
    {
        if (is_string($value)) {
            $value = trim($value);

            if (!$allowHtml) {
                // Remove all HTML tags and encode special chars
                $value = strip_tags($value);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } else {
                // Allow safe HTML (clean with Purifier)
                $value = Purifier::clean($value);
            }
        }

        return $value;
    }

    /**
     * Sanitize an array of inputs recursively
     *
     * @param array $inputs
     * @param array $htmlFields (fields that allow safe HTML)
     * @param array $excludeFields (fields to exclude from sanitization)
     * @param bool $autoDetect (auto detect WYSIWYG fields)
     * @return array
     */
    public static function sanitizeArray(array $inputs, array $htmlFields = [], array $excludeFields = [], $autoDetect = false)
    {
        $sanitized = [];

        foreach ($inputs as $key => $value) {
            // Skip excluded fields
            if (in_array($key, $excludeFields)) {
                $sanitized[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value, $htmlFields, $excludeFields, $autoDetect);
            } else {
                // Decide if HTML is allowed
                $allowHtml = in_array($key, $htmlFields);

                // Auto-detect WYSIWYG fields (if enabled)
                if (!$allowHtml && $autoDetect && self::isWysiwygField($key)) {
                    $allowHtml = true;
                }

                $sanitized[$key] = self::sanitize($value, $allowHtml);
            }
        }

        return $sanitized;
    }
    public static function icon($html)
    {
        // Izinkan hanya <i> tag dengan class tertentu
        return strip_tags($html, '<i>');
    }
    /**
     * Detect WYSIWYG/editor fields by key name
     *
     * @param string $key
     * @return bool
     */
    protected static function isWysiwygField($key)
    {
        $wysiwygKeywords = ['content', 'description', 'body', 'html', 'note', 'editor'];
        foreach ($wysiwygKeywords as $keyword) {
            if (stripos($key, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
}
