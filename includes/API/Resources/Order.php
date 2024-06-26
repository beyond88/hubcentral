<?php

namespace HubCentral\API\Resources;

use WP_REST_Request;
use WP_REST_Response;
use Automattic\WooCommerce\Client;

class Order
{

    /**
     * Holds the settings data retrieved from the database.
     *
     * @var     array   $settings   The settings data retrieved from the database.
     * @access  private
     */
    private $settings;

    /**
     * Constructor method for the class.
     *
     * Initializes the settings property with the option values retrieved from the database.
     *
     * @since  	1.0.0
     * @access	public
     * @return 	void
     */
    public function __construct()
    {
        $this->settings = get_option('hubcentral_settings');
    }

    /**
     * Handle POST request from HubCentral.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response The REST response object.
     */
    public function create_order(WP_REST_Request $request)
    {
        $data = $request->get_params();

        if (!$data) {
            return new WP_REST_Response(array('error' => 'Invalid data'), 400);
        }

        // Create a new post of type 'order'
        $post_id = wp_insert_post(array(
            'post_type' => 'orders',
            'post_status' => 'publish',
            'post_title' => 'Order #' . $data['order_id'], // Set a title for the order post
        ));

        // Check if the post was successfully created
        if (is_wp_error($post_id)) {
            //error_log('Failed to create order', print_r($data));
            return new WP_REST_Response(array('error' => 'Failed to create order'), 500);
        }

        // Update the post meta with the order data
        foreach ($data as $meta_key => $meta_value) {
            // Exclude nested arrays (e.g., order_notes)
            if (!is_array($meta_value)) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }

        // Handle order notes
        if (isset($data['order_notes'])) {
            foreach ($data['order_notes'] as $note) {
                // Create a new comment for the order post
                $comment_id = wp_insert_comment(array(
                    'comment_post_ID' => $post_id,
                    'comment_author' => $note['note_author'],
                    'comment_content' => $note['note_content'],
                    'comment_date' => $note['note_date'],
                ));
            }
        }

        return new WP_REST_Response(array('success' => true, 'post_id' => $post_id), 200);
    }

    /**
     * Handle POST request from HubCentral to update order status and add a note.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response The REST response object.
     */
    public function update_order(WP_REST_Request $request)
    {
        $data = $request->get_params();

        if (!$data) {
            return new WP_REST_Response(array('error' => 'Invalid data'), 400);
        }

        $woocommerce = new Client(
            esc_url($this->settings['base_url']),
            esc_attr($this->settings['consumer_key']),
            esc_attr($this->settings['consumer_secret']),
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );

        // Prepare data to update order
        $order_id = intval($data['id']);
        $order_data = array(
            'status' => $data['status'],
        );

        try {

            if (isset($data['note']) && !empty($data['note'])) {

                $existing_notes = $woocommerce->get("orders/$order_id/notes");

                $updated_note = '';
                if ($existing_notes) {
                    foreach ($existing_notes as $note) {
                        if (isset($note->note)) {
                            $updated_note .= $note->note . "\n";
                        }
                    }
                }
                $updated_note .= $data['note'];

                $note_data = [
                    'note' => trim($updated_note),
                ];

                $woocommerce->post("orders/$order_id/notes", $note_data);
            }

            $response = $woocommerce->put('orders/' . $order_id, $order_data);
            if (isset($response->id)) {

                update_post_meta($data['hub_item_id'], 'status', $data['status']);
                update_post_meta($data['hub_item_id'], 'order_notes', $data['note']);

                return new WP_REST_Response(array('success' => true, 'response' => $response), 200);
            } else {
                return new WP_REST_Response(array('error' => 'Failed to update order'), 500);
            }
        } catch (\Exception $e) {
            return new WP_REST_Response(array('error' => $e->getMessage()), 500);
        }
    }

    /**
     * Output the sections for the settings page.
     * 
     * This method generates HTML markup to display sections 
     * in the settings page. It checks for the current section 
     * and applies appropriate CSS class for styling.
     * 
     * @since  	1.0.0
     * @access	public
     * @return 	void
     */
    public function delete_order(WP_REST_Request $request)
    {
        $data = $request->get_params();

        // Check if order_id is provided
        if (empty($data['order_id'])) {
            return new WP_REST_Response(array('error' => 'Order ID is required'), 400);
        }

        // Get the order ID
        $order_id = $data['order_id'];

        // Check if the order exists
        $order = get_post($order_id);
        if (!$order || $order->post_type !== 'orders') {
            return new WP_REST_Response(array('error' => 'Order entry not found in the hub center, ' . $order_id . ''), 200);
        }

        // Delete the order and all its meta
        $deleted = wp_delete_post($order_id, true);

        if ($deleted) {
            return new WP_REST_Response(array('success' => true), 200);
        } else {
            return new WP_REST_Response(array('error' => 'Failed to delete order'), 200);
        }
    }
}
