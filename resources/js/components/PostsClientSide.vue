<script>
import Datatable from './Datatable.vue';
import Pagination from './Pagination.vue';

export default {
    components: { datatable: Datatable, pagination: Pagination },
    created() {
        this.fetchPosts();
    },
    data() {
        let sortOrders = {};

        let columns = [
            {width: '33%', label: 'ID', name: 'id'},
            {width: '33%', label: 'Title', name: 'title'},
            {width: '33%', label: 'Description', name: 'description'},
        ];

        columns.forEach((column) => {
            sortOrders[column.name] = -1;
        });

        return {
            posts: [],
            sortKey: 'id',
            columns: columns,
            sortOrders: sortOrders,
            length: 10,
            search: '',
            tableData: {
                client: true,
            },
            pagination: {
                to: '',
                from: '',
                total: '',
                lastPage: '',
                currentPage: '',
                lastPageUrl: '',
                nextPageUrl: '',
                prevPageUrl: ''
            }
        }
    }
}
</script>
