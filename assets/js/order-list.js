new Vue({
    el: '#hub-order-list',
    data: {
        orders: [], // Initialize orders as an empty array
        searchQuery: '',
        currentPage: 1,
        pageSizeOptions: [5, 10, 20], // Options for items per page
        selectedPageSize: 5, // Default selected items per page
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
        }
    },
    created() {
        // Initialize orders with the data passed from PHP
        this.orders = ordersData;
    }
});