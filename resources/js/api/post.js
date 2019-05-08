import axios from '../config/axios'

const authUser = JSON.parse(window.localStorage.getItem('authUser'))

if (authUser && authUser.access_token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + authUser.access_token
}

export default {
    fetch(data) {
        return axios.get('/api/v1/posts', {params: data})
    },
    store(data) {
        return axios.post('/api/v1/posts', data)
    }
}