<template>
    <v-app id="inspire">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <v-card class="elevation-12">
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>Đăng Nhập</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field 
                                prepend-icon="person"
                                v-model="username"
                                :error-messages="errors.collect('username')"
                                v-validate="'required|email'"
                                data-vv-name="username"
                                label="Email"
                                type="text"
                            ></v-text-field>
                            <v-text-field
                            prepend-icon="lock"
                            v-validate="'required'"
                            :error-messages="errors.collect('password')"
                            v-model="password"
                            data-vv-name="password"
                            label="Mật khẩu"
                            type="password"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="submit">Đăng nhập</v-btn>
                    </v-card-actions>
                    </v-card>
                </v-flex>
                </v-layout>
            </v-container>
        </v-content>
        <notify :message="message" :color="color" :show.sync="show"></notify>
    </v-app>
</template>

<script>
import Notify from '../Notify'
import Api from '../../api/auth'
export default {
    components: { Notify },
    mounted () {
      this.$validator.localize('vi', this.dictionary)
    },
    data: () => ({
        username: '',
        password: '',
        show: false,
        message: '',
        color: '',
        dictionary: {
            attributes: {
                username: 'E-mail Address'
            },
            custom: {
                username: {
                    required: () => 'Email không được để trống',
                    email: 'Email không đúng định dạng'
                },
                password: {
                    required: 'Mật khẩu không được để trống'
                }
            }
        }
    }),
    methods: {
        submit() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    let data = {
                        'username': this.username,
                        'password': this.password
                    }
                    const authUser = {}
                    Api.login(data)
                        .then(res => {
                            if (res.status === 200) {
                                authUser.access_token = res.data.access_token
                                authUser.refresh_token = res.data.refresh_token
                                window.localStorage.setItem('authUser', JSON.stringify(authUser))
                                Api.getUser()
                                    .then(res => {
                                        authUser.name = res.data.name
                                        authUser.email = res.data.email
                                        window.localStorage.setItem('authUser', JSON.stringify(authUser))
                                        const user = JSON.parse(window.localStorage.getItem('authUser'))
                                        this.$store.dispatch('setUser', user)
                                        this.$router.push({name: 'Dashboard'})
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        this.show = true
                                        this.color = 'error'
                                        this.message = 'Lỗi kết nối xin vui lòng đăng nhập lại.'
                                    })

                            }
                        })
                        .catch(err => {
                            console.error(err)
                            this.show = true
                            this.color = 'error'
                            this.message = 'Email hoặc mật khẩu không đúng, xin hãy nhập lại.'
                        })
                }
            });
        }
    }
}
</script>
