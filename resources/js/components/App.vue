<template>
    <v-app>
        <div v-if="(user.authUser !== null && user.authUser.access_token) || this.$route.fullPath !== '/login' ">
            <v-navigation-drawer v-model="drawer" clipped fixed app>
                <v-list dense>
                    <v-list-tile v-for="link in links" :to="{path: link.path}" :key="link.name">
                        <v-list-tile-action>
                            <v-icon>{{ link.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ link.name }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>

            <v-toolbar app fixed clipped-left color="#ffce54">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <v-toolbar-title>Post System</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-menu bottom left>
                    <v-btn slot="activator" icon>
                        <v-icon large>account_circle</v-icon>
                    </v-btn>

                    <v-list>
                        <v-list-tile @click="logout">
                            <v-list-tile-action>
                                <v-icon color="pink">logout</v-icon>
                            </v-list-tile-action>

                            <v-list-tile-content>
                                <v-list-tile-title>Đăng xuất</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-toolbar>
        </div>
        <v-content>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
import { mapState } from 'vuex'
import links from '../sidebarLinks'
import axios from '../config/axios'
export default {
    data: () => ({
        drawer: null,
        links: links
    }),
    computed: mapState ([
        'user'
    ]),
    methods: {
        logout() {
            window.localStorage.removeItem('authUser')
            this.$store.dispatch('setUser', null)
            this.$router.push({name: 'Login'})
        }
    }
}
</script>
