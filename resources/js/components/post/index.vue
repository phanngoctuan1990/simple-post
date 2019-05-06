<template>
    <div>
        <v-toolbar flat color="white">
            <v-toolbar-title>Danh sách bài viết</v-toolbar-title>
            <v-divider class="mx-2" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="search"
                label="Tìm kiếm"
                single-line
                hide-details
                @input="filterSearch"
            ></v-text-field>
            <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
                <v-btn color="primary" dark class="mb-2" v-on="on">Tạo mới</v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.title" label="Tiêu đề"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.description" label="Mô tả"></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                </v-card-text>

                <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="close">Thoát</v-btn>
                    <v-btn color="blue darken-1" flat @click="save">Lưu</v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table
            must-sort
            hide-actions
            :items="posts"
            :loading="loading"
            :headers="headers"
            class="elevation-1"
            :total-items="totalItems"
            :custom-sort="customSort"
            :pagination.sync="pagination"
        >
            <template v-slot:items="props">
            <td>{{ props.item.id }}</td>
            <td class="text-xs-right">{{ props.item.title }}</td>
            <td class="text-xs-right">{{ props.item.user.name }}</td>
            <td class="text-xs-right">{{ props.item.description }}</td>
            <td class="text-xs-right">{{ props.item.created_at }}</td>
            <td class="justify-center layout px-0">
                <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
                <v-icon small @click="deleteItem(props.item)">delete</v-icon>
            </td>
            </template>
            <template v-slot:no-data>
                <div></div>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination v-model="pagination.page" :length="pages()" :total-visible="pagination.visible" circle @input="onPageChange"></v-pagination>
        </div>
        <notify :message="message" :color="color" :show.sync="show"></notify>
    </div>
</template>

<script>
import Notify from '../Notify'
import postApi from '../../api/post'

export default {
    components: { Notify },
    data: () => ({
        show: false,
        loading: false,
        color: '',
        message: '',
        search: '',
        dialog: false,
        totalItems: 0,
        pagination: {
            page: 1,
            visible: 7,
            sortBy: 'id',
            rowsPerPage: 10,
            descending: false
        },
        selected: [],
        headers: [
            { text: '#', align: 'left', value: 'id' },
            { text: 'Tiêu đề', value: 'title' },
            { text: 'Tác giả', value: 'user.name', sortable: false },
            { text: 'Mô tả', value: 'description' },
            { text: 'ngày tạo', value: 'created_at' },
            { text: 'Tác vụ', value: 'action', sortable: false }
        ],
        posts: [],
        editedIndex: -1,
        editedItem: {
            id: 0,
            title: '',
            user: {
                name: ''
            },
            description: '',
            created_at: ''
        },
        defaultItem: {
            id: 0,
            title: '',
            user: {
                name: ''
            },
            description: '',
            created_at: ''
        }
    }),

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Tạo bài viết' : 'Cập nhật bài viết'
        }
    },

    watch: {
        pagination: {
            handler () {
                this.initialize()
            },
            deep: true
        },
        dialog (val) {
            val || this.close()
        }
    },

    mounted() {
        this.initialize()
    },

    methods: {
        initialize () {
            this.loading = true
            let data = {
                search: this.search,
                page: this.pagination.page,
                sortBy: this.pagination.sortBy,
                rowsPerPage: this.pagination.rowsPerPage,
                descending: this.pagination.descending == false ? 'ASC' : 'DESC'
            }
            postApi.fetch(data).then(res => {
                console.log(res);
                this.posts = res.data.data
                this.totalItems = res.data.total
                this.pagination.page = res.data.current_page
                this.loading = false
            })
            .catch(err => {
                console.error(err)
                this.show = true
                this.color = 'error'
                this.message = 'Lỗi khi lấy dữ liệu bài viết, làm phiền bạn làm mới lại trang.'
            })
        },

        editItem (item) {
            this.editedIndex = this.posts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem (item) {
            const index = this.posts.indexOf(item)
            confirm('Bạn có chắc chắn muốn xoá bài viết này?') && this.posts.splice(index, 1)
        },

        close () {
            this.dialog = false
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            }, 300)
        },

        save () {
            if (this.editedIndex > -1) {
                Object.assign(this.posts[this.editedIndex], this.editedItem)
            } else {
                this.posts.push(this.editedItem)
            }
            this.close()
        },

        pages () {
            if (this.pagination.rowsPerPage == null ||
                this.totalItems == null
            ) return 0

            return Math.ceil(this.totalItems / this.pagination.rowsPerPage)
        },

        onPageChange() {
            this.initialize();
        },

        customSort(items, index, isDesc) {
            items.sort((a, b) => {
                if (index === "date") {
                if (!isDesc) {
                    return dateHelp.compare(a.date, b.date);
                } else {
                    return dateHelp.compare(b.date, a.date);
                }
                } else {
                if (!isDesc) {
                    return a[index] < b[index] ? -1 : 1;
                } else {
                    return b[index] < a[index] ? -1 : 1;
                }
                }
            });
            return items;
        },

        filterSearch(val) {
            this.pagination.page = 1
            if (this.pagination.descending) {
                this.pagination.descending = false
            } else {
                this.pagination.descending = true
            }
            this.initialize()
        }
    }
}
</script>