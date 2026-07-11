<?php
/**
 * Helper Functions
 */

/**
 * Validate channel ID
 * @param string $channelId
 * @return bool
 */
function isValidChannel($channelId) {
    return !empty($channelId) && preg_match('/^[a-zA-Z0-9_]+$/', $channelId);
}

/**
 * Sanitize stream URL
 * @param string $url
 * @return string
 */
function sanitizeStreamUrl($url) {
    return filter_var($url, FILTER_SANITIZE_URL);
}

/**
 * Check if channel is allowed
 * @param string $channelId
 * @return bool
 */
function isAllowedChannel($channelId) {
    if (!defined('ALLOWED_CHANNELS')) {
        return true;
    }
    return in_array($channelId, ALLOWED_CHANNELS, true);
}

/**
 * Get stream URL for channel
 * @param string $channelId
 * @return string
 */
function getStreamUrl($channelId) {
    if (!isValidChannel($channelId)) {
        return '';
    }

    if (defined('STREAM_BASE_URL')) {
        return STREAM_BASE_URL . urlencode($channelId);
    }

    return '';
}

/**
 * Log error (if debug mode enabled)
 * @param string $message
 */
function logError($message) {
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log('[CricHD] ' . $message);
    }
}
