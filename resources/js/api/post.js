import axios from '../config/axios'

const tokenData = JSON.parse(window.localStorage.getItem('authUser'))
axios.defaults.headers.common['Authorization'] = 'Bearer ' + tokenData.access_token

export default {
    fetch(data) {
        return axios.get('/api/v1/posts', {params: data})
    }
}