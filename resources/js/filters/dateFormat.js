import moment from 'moment'

export default function dateFormat (date) {
    return moment(date).format('DD-MM-YYYY')
}