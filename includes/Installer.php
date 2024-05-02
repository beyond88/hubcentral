<?php

namespace HubCentral;

use WP_Roles;

/**
 * Installer class
 */
class Installer
{

    /**
     * Run the installer
     * 
     * @since   1.0.0
     * @access  public
     * @param   none
     * @return  void
     */
    public function run()
    {
        $this->add_version();
        $this->add_customer_support_role();
    }

    /**
     * Add time and version on DB
     * 
     * @since   1.0.0
     * @access  public
     * @param   none
     * @return  void
     */
    private function add_version()
    {
        $installed = get_option('hubcentral_installed');

        if (!$installed) {
            update_option('hubcentral_installed', time());
        }

        update_option('hubcentral_version', HUBCENTRAL_VERSION);
    }

    /**
     * Add customer support role
     * 
     * @since   1.0.0
     * @access  private
     * @return  void
     */
    private function add_customer_support_role()
    {
        $wp_roles = new WP_Roles();

        // Add customer support role if not already exists
        if (!$wp_roles->is_role('customer_support')) {
            $wp_roles->add_role(
                'customer_support',
                __('Customer Support', 'hubcentral'),
                array(
                    'read' => true,
                    'edit_orders' => true, // Allow editing orders
                    'manage_woocommerce' => true, // Allow managing WooCommerce
                )
            );
        }
    }
}
