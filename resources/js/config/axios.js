import axios from 'axios'

let token = document.head.querySelector('meta[name="csrf-token"]')

export default axios.create({
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token.content,
    }
})