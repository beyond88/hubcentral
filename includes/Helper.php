<?php
namespace HubCentral;

/**
 * Helper class for common functions
 */
class Helper
{

    /**
     * Check if the current user has permission to access certain functionality.
     *
     * @return bool True if the user has permission, false otherwise.
     */
    public static function user_has_permission()
    {
        $status = false;

        // Check if the user has administrator or customer_support role
        if (current_user_can('administrator') ||  current_user_can('customer_support')) {
            $status = true;
        }

        return $status;
    }
}
