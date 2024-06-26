<?php

namespace HubCentral\Frontend\Shortcodes;

use HubCentral\Helper;

/**
 * The admin class
 */
class OrderTable
{

    /**
     * Private attributes
     * 
     * @var string
     */
    private $atts;

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
        add_shortcode('hub_order_list', array($this, 'hub_order_list'));
    }

    /**
     * Enqueue scripts and styles
     *
     * @since   1.0.0
     * @access  public
     * @param   none   
     * @return  void
     */
    public function enqueue_assets()
    {
        wp_enqueue_script('vue', HUBCENTRAL_ASSETS . '/js/vue.min.js', array(), null, true);
        wp_enqueue_script('axios', HUBCENTRAL_ASSETS . '/js/axios.min.js', array(), null, true);
        wp_enqueue_script('hub-order-list-script', HUBCENTRAL_ASSETS . '/js/order-list.js', array('vue'), null, true);
    }

    /**
     * Shortcode callback method
     *
     * @since   1.0.0
     * @access  public
     * @param   array
     * @return  string
     */
    public function hub_order_list($atts)
    {

        $this->atts = shortcode_atts(array(), $atts);

        return $this->output();
    }

    /**
     * Render samply button
     *
     * @since    1.0.0
     * @access  public
     * @param    none
     * @return   string
     */
    public function output()
    {
        ob_start();

        // Enqueue Vue.js and your Vue component script
        $this->enqueue_assets();

        $orders = get_posts(array(
            'post_type' => 'orders', // Your custom post type
            'posts_per_page' => -1,
        ));

        $formatted_orders = array();
        foreach ($orders as $order) {
            $order_meta = get_post_meta($order->ID);

            $order_data = array(
                'id' => isset($order_meta['order_id'][0]) ? $order_meta['order_id'][0] : '',
                'customer_name' => isset($order_meta['billing_first_name'][0]) ? $order_meta['billing_first_name'][0] : '',
                'email' => isset($order_meta['billing_email'][0]) ? $order_meta['billing_email'][0] : '',
                'status' => isset($order_meta['status'][0]) ? $order_meta['status'][0] : '',
                'order_date' => isset($order_meta['date_created'][0]) ? $order_meta['date_created'][0] : '',
                'shipping_date' => isset($order_meta['date_created'][0]) ? date('Y-m-d', strtotime('+2 weeks', strtotime($order_meta['date_created'][0]))) : '',
                'customer_note' => isset($order_meta['customer_note'][0]) ? $order_meta['customer_note'][0] : '',
                'order_notes' => isset($order_meta['order_notes'][0]) ? $order_meta['order_notes'][0] : '',
                'hub_item_id' => $order->ID,
            );

            // Combine first name and last name
            if (isset($order_meta['billing_first_name'][0]) && isset($order_meta['billing_last_name'][0])) {
                $order_data['customer_name'] = $order_meta['billing_first_name'][0] . ' ' . $order_meta['billing_last_name'][0];
            }


            $formatted_orders[] = $order_data;
        }

        // Render the Vue component
?>
        <div id="hub-order-list">
            <?php
            if (Helper::user_has_permission()) {
            ?>
                <div>
                    <input type="text" v-model="searchQuery" placeholder="Search...">
                    <button @click="search">Search</button>
                </div>
            <?php } ?>
            <!-- Dropdown menu for selecting items per page -->
            <div>
                <label for="pageSize">Items per page:</label>
                <select id="pageSize" v-model="selectedPageSize">
                    <option v-for="option in pageSizeOptions" :value="option">{{ option }}</option>
                </select>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Shipping Date</th>
                        <th>Customer Notes</th>
                        <th>Order Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($formatted_orders)) { ?>
                        <tr v-for="order in paginatedOrders">
                            <td>{{ order.id }}</td>
                            <td>{{ order.customer_name }}</td>
                            <td>{{ order.email }}</td>
                            <td>{{ order.status }}</td>
                            <td>{{ order.order_date }}</td>
                            <td>{{ order.shipping_date }}</td>
                            <td>{{ order.customer_note }}</td>
                            <td>{{ order.order_notes }}</td>
                            <td>
                                <?php
                                if (Helper::user_has_permission()) {
                                ?>
                                    <button type="button" class="action-view-order" @click="openPopup(order)">
                                        <?php echo __('View', 'hubcentral'); ?>
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="action-read-only">
                                        <?php echo __('View', 'hubcentral'); ?>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9"><?php echo __('Data not found!', 'hubcentral'); ?></td>
                        </tr>
                    <?php }  ?>

                </tbody>
            </table>
            <!-- Pagination buttons -->
            <div>
                <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
                <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
            </div>

            <!-- Popup -->
            <div class="hc-popup">
                <div class="hc-popup-content">
                    <h2>Order Details - {{ selectedOrder.id }}</h2>
                    <div>
                        <label for="status">Status:</label>
                        <select v-model="selectedOrder.status" @change="updateOrderStatus">
                            <option v-for="(label, code) in orderStatuses" :value="code.replace('wc-', '')" :selected="code.replace('wc-', '') === selectedOrder.status">{{ label }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="notes">Order Notes:</label>
                        <textarea v-model="selectedOrder.order_notes" @change="updateOrderNotes"></textarea>
                    </div>
                    <button @click="closePopup">Close</button>
                    <button id="hc-update-order" @click="updateOrder">Update</button>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var ordersData = <?php echo json_encode($formatted_orders); ?>;
        </script>
<?php

        return ob_get_clean();
    }
}
