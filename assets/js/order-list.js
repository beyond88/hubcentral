new Vue({
    el: '#hub-order-list',
    data: {
        orders: [], // Initialize orders as an empty array
        searchQuery: '',
        currentPage: 1,
        pageSizeOptions: [5, 10, 20], // Options for items per page
        selectedPageSize: 5, // Default selected items per page
        isPopupOpen: false,
        selectedOrder: {}, // Selected order object to display in the popup
        orderStatuses: {
            'wc-pending': 'Pending',
            'wc-processing': 'Processing',
            'wc-completed': 'Completed',
            'wc-on-hold': 'On Hold',
            'wc-cancelled': 'Cancelled',
            'wc-refunded': 'Refunded',
            'wc-failed': 'Failed'
        }
    },
    computed: {
        // Filtered orders based on search query
        filteredOrders() {
            return this.orders.filter(order =>
                order.id.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.customer_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.status.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.order_date.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.shipping_date.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.customer_note.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                order.order_notes.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        // Total number of pages based on filtered orders and page size
        totalPages() {
            return Math.ceil(this.filteredOrders.length / this.selectedPageSize);
        },
        // Paginated orders based on current page and page size
        paginatedOrders() {
            const startIndex = (this.currentPage - 1) * this.selectedPageSize;
            return this.filteredOrders.slice(startIndex, startIndex + this.selectedPageSize);
        }
    },
    methods: {
        // Go to the next page
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        // Go to the previous page
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        // Reset pagination and search query
        search() {
            this.currentPage = 1;
        },
        openPopup(order) {
            // Open the popup and set the selected order
            const popup = document.querySelector('.hc-popup');
            if (popup) {
                popup.classList.add('show-popup');
            }
            this.isPopupOpen = true;
            this.selectedOrder = order;

        },
        closePopup() {
            // Close the popup
            this.isPopupOpen = false;
            const popup = document.querySelector('.hc-popup');
            if (popup) {
                popup.classList.remove('show-popup');
            }
        },
        updateOrderStatus() {
            // Implement logic to update order status in the database or send to server
            console.log('Order status updated:', this.selectedOrder.status);
        },
        updateOrderNotes() {
            // Implement logic to update order notes in the database or send to server
            console.log('Order notes updated:', this.selectedOrder.order_notes);
        },

        updateOrder() {
            // Implement logic to update order notes in the database or send to server
            console.log('order id:', this.selectedOrder.id);
            console.log('Order status:', this.selectedOrder.status);
            console.log('Order notes:', this.selectedOrder.order_notes);
        }
    },
    created() {
        // Initialize orders with the data passed from PHP
        this.orders = ordersData;
    }
});