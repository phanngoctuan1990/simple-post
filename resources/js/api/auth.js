import axios from '../config/axios'
import {clientId, clientSecret} from '../config/env'

const postData = {
    grant_type: 'password',
    client_id: clientId,
    client_secret: clientSecret,
    username: '',
    password: '',
}

export default {
    login(data) {
        postData.username = data.username
        postData.password = data.password
        return axios.post('/oauth/token', postData)
    },
    logout(token) {
        return axios.delete('/oauth/tokens/' + token)
    },
    getUser() {
        const tokenData = JSON.parse(window.localStorage.getItem('authUser'))
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + tokenData.access_token
        return axios.get('/api/user')
    }
}