<template>
    <div class="posts">
        <div class="tableFilters">
            <input type="text" class="input" v-model="tableData.search" placeholder="search Table" @input="fetchPosts()">
            <div class="control">
                <div class="select">
                    <select v-model="tableData.length" @change="fetchPosts()">
                        <option value="10" selected="selected">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                </div>
            </div>
        </div>
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
            <tbody>
                <tr v-for="post in posts" :key="post.id">
                    <td>{{ post.id }}</td>
                    <td>{{ post.title }}</td>
                    <td>{{ post.description }}</td>
                </tr>
            </tbody>
        </datatable>
        <pagination :pagination="pagination"
            @prev="fetchPosts(pagination.prevPageUrl)"
            @next="fetchPosts(pagination.nextPageUrl)"
        ></pagination>
    </div>
</template>

<script>
import Datatable from "./Datatable.vue";
import Pagination from "./Pagination.vue";
export default {
    components: { datatable: Datatable, pagination: Pagination},
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
            tableData: {
                draw: 0,
                column: 0,
                length: 10,
                search: '',
                dir: 'desc'
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
    },
    methods: {
        fetchPosts(url = '/api/v1/posts') {
            this.tableData.draw++;
            axios.get(url, {params: this.tableData})
                .then(res => {
                    let data = res.data;
                    this.posts = data.data;
                    this.configPagination(data);
                })
                .catch(err => {
                    console.error(err);
                })
        },
        configPagination(data) {
            this.pagination.to = data.meta.to;
            this.pagination.from = data.meta.from;
            this.pagination.total = data.meta.total;
            this.pagination.lastPage = data.meta.last_page;
            this.pagination.currentPage = data.meta.current_page;
            this.pagination.lastPageUrl = data.links.last;
            this.pagination.nextPageUrl = data.links.next;
            this.pagination.prevPageUrl = data.links.prev;
        },
        sortBy(key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            this.tableData.column = this.getIndex(this.columns, 'name', key);
            this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
            this.fetchPosts();
        },
        getIndex(arr, key, val) {
            return arr.findIndex(i => i[key] == val);
        }
    }
}
</script>

<style lang="scss">
.posts {
    width: 660px;
    margin: 0 auto;

    .tableFilters {
        margin-bottom: 10px;
        input{
            width: 175px;
        }
        .control {
            float: right;
        }
    }

    .table {
        width: 100%;

        .sorting {
            background-image: url('/images/sort_both.png');
            background-repeat: no-repeat;
            background-position: center right;
        }

        .sorting_asc {
            background-image: url('/images/sort_asc.png');
            background-repeat: no-repeat;
            background-position: center right;
        }

        .sorting_desc {
            background-image: url('/images/sort_desc.png');
            background-repeat: no-repeat;
            background-position: center right;
        }
    }
}

h1 {
    text-align: center;
    font-size: 30px;    
}
</style>
