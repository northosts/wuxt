import WPApi from 'wpapi'

/**
 * Routes
 */
import registerFrontPage from '~/modules/wp-api/routes/front-page'

const wp = new WPApi(<%= serialize(options) %>)

export default (ctx, inject) => {
  /**
   * Register routes
   */
   registerFrontPage(wp)

  /** 
   * Inject
   */
  inject('wp', wp)
}
