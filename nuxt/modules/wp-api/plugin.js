import WPApi from 'wpapi'

/**
 * Routes
 */
import registerFrontPage from '~/modules/wp-api/routes/front-page'
import registerMenu from '~/modules/wp-api/routes/menu'
import registerSlug from '~/modules/wp-api/routes/slug'
import registerCPT from '~/modules/wp-api/routes/cpt'

const wp = new WPApi(<%= serialize(options) %>)

export default (ctx, inject) => {
  /**
   * Register routes
   */
   registerFrontPage(wp)
   registerMenu(wp)
   registerSlug(wp)
   registerCPT(wp)

  /**
   * Inject
   */
  inject('wp', wp)
}
