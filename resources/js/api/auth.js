import axios from '../config/axios'
import {clientId, clientSecret} from '../config/env'

const postData = {
    username: '',
    password: '',
    client_id: clientId,
    grant_type: 'password',
    client_secret: clientSecret,
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
        return axios.get('/api/v1/users')
    }
}