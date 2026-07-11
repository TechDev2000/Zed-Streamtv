<?php
/**
 * Legacy Kaltura Player
 * Redirects to kplay.php for consistency
 */
header("Location: kplay.php?" . $_SERVER['QUERY_STRING']);
exit;
?>