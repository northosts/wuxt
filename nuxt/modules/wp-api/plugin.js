// import WPApi from 'wpapi'
const WPApi = require('wpapi')

const wp = new WPApi(<%= serialize(options) %>)

export default function(ctx, inject) {
  inject('wp', wp)
}
