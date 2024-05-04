<?php

namespace HubCentral;

use HubCentral\API\Resources\Order;

/**
 * API Class
 */
class API
{

    /**
     * Initialize the class
     * 
     * @since   1.0.0
     * @access  public
     * @param   none
     * @return  void
     */
    function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api'));
    }

    /**
     * Register the API
     *
     * @since   1.0.0
     * @access  public
     * @param   none
     * @return  void
     */
    public function register_api()
    {

        $order = new Order();

        register_rest_route('hubcentral/v1', '/order', array(
            'methods' => 'POST',
            'callback' => array($order, 'create_order'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('hubcentral/v1', '/order/update', array(
            'methods' => 'PUT',
            'callback' => array($order, 'update_order'),
            'permission_callback' => '__return_true',
        ));


        register_rest_route('hubcentral/v1', '/order/delete', array(
            'methods' => 'POST',
            'callback' => array($order, 'delete_order'),
            'permission_callback' => '__return_true',
        ));
    }
}
