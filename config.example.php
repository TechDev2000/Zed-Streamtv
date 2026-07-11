<?php
/**
 * Configuration File
 * Copy this to config.php and update values
 */

// Site Settings
define('SITE_NAME', 'CricHD');
define('SITE_URL', 'https://your-domain.com');

// Stream Sources
define('STREAM_BASE_URL', 'https://onstream.tiiny.io/embed.php?id=');
define('FALLBACK_STREAM', '');

// Player Settings
define('DEFAULT_PLAYER', 'clappr'); // clappr, jwplayer, kaltura
define('AUTOPLAY', true);
define('DEFAULT_VOLUME', 100);

// Security
define('ALLOWED_CHANNELS', [
    'skysme', 'skyscric', 'skysfott', 'skysare', 'skysact',
    'skysgol', 'skysprem', 'skysfor1', 'skysmixx', 'skysnews',
    'bbtsp1', 'bbtsp2', 'bbtsp3', 'bbtsp4',
    'star1in', 'star2in', 'star3in',
    'ten1endia', 'ten2endia', 'ten3endia', 'sonysixendia',
    'ptvpk', 'asports', 'astrocric', 'willowusa', 'geosp',
    'superspcric', 'superlaliga', 'superpremierleague',
    'superspfb', 'superspaction', 'supergs',
    'superspv1', 'superspv2', 'superspv3', 'superspv4',
    'tsn1ca', 'tsn2ca', 'tsn3ca', 'tsn4ca', 'tsn5ca',
    'viaplaysp1', 'viaplaysp2', 'viaplayextra',
    'eurosp1', 'eurosp2'
]);

// Cache Settings
define('CACHE_ENABLED', false);
define('CACHE_DURATION', 3600); // seconds

// Debug Mode (disable in production)
define('DEBUG_MODE', false);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
