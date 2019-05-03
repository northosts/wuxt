import WPApi from 'wpapi'

/**
 * Routes
 */
import registerFrontPage from '~/modules/wp-api/routes/front-page'
import registerMenu from '~/modules/wp-api/routes/menu'
import registerSlug from '~/modules/wp-api/routes/slug'

const wp = new WPApi(<%= serialize(options) %>)

export default (ctx, inject) => {
  /**
   * Register routes
   */
   registerFrontPage(wp)
   registerMenu(wp)
   registerSlug(wp)

  /** 
   * Inject
   */
  inject('wp', wp)
}
