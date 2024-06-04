<?php
    require_once('models/PermissionCode.php');
    // The time that user session will last after last user activity: 1 hour
    define('SESSION_TIMEOUT', 3600);

    function checkSessionTimeout() {
        if (isset($_SESSION['last_ping']) && (time() - $_SESSION['last_ping']) > SESSION_TIMEOUT) {
            // Last activity is more than 15 minutes ago
            session_unset(); // Unset $_SESSION variables
            session_destroy(); // Destroy session data
        } else {
            // Update last activity time stamp
            $_SESSION['last_ping'] = time();
        }
    }

    function redirectUnauthorized(array $permissionsAllowed = null) {
	    if ($permissionsAllowed === null) {
		    $permissionsAllowed = [PermissionCode::Guest->value, PermissionCode::User->value];
	    }
	    if (!in_array($_SESSION['permission'], $permissionsAllowed)) {
		    header("Location: login");
		    exit();
	    }
    }
?>
