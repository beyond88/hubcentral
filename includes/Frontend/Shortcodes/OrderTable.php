<?php

namespace HubCentral\Frontend\Shortcodes;

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
        wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js', array(), null, true);
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

        // Retrieve order data from the database
        $orders = get_posts(array(
            'post_type' => 'orders', // Your custom post type
            'posts_per_page' => -1,
        ));

        // Format order data into an array
        $formatted_orders = array();
        foreach ($orders as $order) {
            // Get order meta data
            $order_meta = get_post_meta($order->ID);

            // Format order data
            $order_data = array(
                'id' => isset($order_meta['order_id'][0]) ? $order_meta['order_id'][0] : '',
                'customer_name' => isset($order_meta['billing_first_name'][0]) ? $order_meta['billing_first_name'][0] : '',
                'email' => isset($order_meta['billing_email'][0]) ? $order_meta['billing_email'][0] : '',
                'status' => isset($order_meta['status'][0]) ? $order_meta['status'][0] : '',
                'order_date' => isset($order_meta['date_created'][0]) ? $order_meta['date_created'][0] : '',
                'shipping_date' => isset($order_meta['date_created'][0]) ? date('Y-m-d', strtotime('+2 weeks', strtotime($order_meta['date_created'][0]))) : '',
                'customer_note' => isset($order_meta['customer_note'][0]) ? $order_meta['customer_note'][0] : '',
                'order_notes' => isset($order_meta['order_notes'][0]) ? $order_meta['order_notes'][0] : '',
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
            <div>
                <input type="text" v-model="searchQuery" placeholder="Search...">
                <button @click="search">Search</button>
            </div>
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in paginatedOrders">
                        <td>{{ order.id }}</td>
                        <td>{{ order.customer_name }}</td>
                        <td>{{ order.email }}</td>
                        <td>{{ order.status }}</td>
                        <td>{{ order.order_date }}</td>
                        <td>{{ order.shipping_date }}</td>
                        <td>{{ order.customer_note }}</td>
                        <td>{{ order.order_notes }}</td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination buttons -->
            <div>
                <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
                <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
            </div>
        </div>

        <?php

        // Pass formatted order data to Vue component
        ?>
        <script type="text/javascript">
            var ordersData = <?php echo json_encode($formatted_orders); ?>;
        </script>
<?php

        return ob_get_clean();
    }
}
