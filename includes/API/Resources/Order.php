<?php
namespace HubCentral\API\Resources;
use WP_REST_Request;
use WP_REST_Response;

class Order {

    public function __construct() {}

    /**
     * Handle POST request from HubCentral.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response The REST response object.
     */
    public function create_order(WP_REST_Request $request) {
        $data = $request->get_params();

        // error_log('Order data sent to Hub successfully: ' . print_r($data, true));
    
        if (! $data) {
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

    public function get_order(WP_REST_Request $request) {
        echo "Api calling!";
        return new WP_REST_Response(array('success' => true), 200);
    }

    /**
	 * Delete order
	 * 
	 * @since  	1.0.0
	 * @access	public
	 * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response The REST response object.
	 */
    public function delete_order(WP_REST_Request $request) {
        $data = $request->get_params();
        
        // Check if order_id is provided
        if (empty($data['order_id'])) {
            return new WP_REST_Response(array('error' => 'Order ID is required'), 400);
        }
    
        // Get the order ID
        $order_id = $data['order_id'];
        
        // Check if the order exists
        $order = get_post($order_id);
        if (!$order || $order->post_type !== 'order') {
            return new WP_REST_Response(array('error' => 'Order not found'), 404);
        }
    
        // Delete the order and all its meta
        $deleted = wp_delete_post($order_id, true);
    
        if ($deleted) {
            return new WP_REST_Response(array('success' => true), 200);
        } else {
            return new WP_REST_Response(array('error' => 'Failed to delete order'), 500);
        }
    }
    
}