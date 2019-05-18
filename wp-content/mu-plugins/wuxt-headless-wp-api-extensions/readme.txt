=== Plugin Name ===
Contributors: danielauener
Donate link: http://www.danielauener.com/
Tags: rest api, endpoint extension, headless
Requires at least: 4.7.0
Tested up to: 5.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extensions for the Rest API to provide endpoints that support a more convenient
use of headless WordPress as back-end CMS.

== Description ==

The plugin is coded for [WUXT](https://github.com/northosts/wuxt), a dockerized
development environment for headless WordPress combined with NuxtJs. However,
it can be used by every other application, which needs a powerful headless
WordPress back-end.

**New endpoints**

* <code>GET</code> <code>/wp-json/wuxt/v1/front-page</code>
* <code>GET</code> <code>/wp-json/wuxt/v1/front-page?_embed</code>

There is no obvious way to get the WordPress front-page via the Rest API.
To read the settings, you have to be authorized, which makes things unnecessary
complicated. So here a custom endpoint for getting the front-page if it is set.
The ten newest posts otherwise.

* <code>GET</code> <code>/wp-json/wuxt/v1/menu</code>
* <code>GET</code> <code>/wp-json/wuxt/v1/menu?location=&lt;location&gt;</code>

Right now, there is no way I know of for getting menus from the API. This
endpoint returns an entire menu as nested array. Default location is "main", but
you can request other locations with the location parameter.

* <code>GET</code> <code>/wp-json/wuxt/v1/slug/&lt;post-or-page-slug&gt;</code>
* <code>GET</code> <code>/wp-json/wuxt/v1/slug/&lt;post-or-page-slug&gt;?_embed</code>

If you are building a front-end app on top of WordPress, you have to think about
how to structure your urls. WordPress has two default post-types (posts & pages)
and in the urls is not distinguished which type you are requesting, so
http://wp-site.expl/something might lead to a page or a post, dependent on the
type of the object with the slug something. If you want to mirror that behaviour
in your app, you have to do two requests for each url, one searching pages,
one searching posts. To make that one request, use the slug end-point.

**Enpoint extensions**

* <code>GET</code> <code>/wp-json/wp/v2/posts/?categories=1,2&and=true</code>

When filtering taxonomies with an Rest API request, all queries are OR-queries,
That means you can get posts which are either in category A or B. Our adjustment
lets you switch all tax_queries to an AND-relation, so that you can select posts
which are both in category A and B.

* **WordPress SEO meta fields** are included automatically in the
<code>meta</code> object if the plugin is activated.
* **Advanced custom fields** are included automatically in the <code>meta</code>
object if the plugin is activated.

== Installation ==

1. Upload the `wuxt-headless-wp-api-extensions` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the new endpoints
5. done

== Frequently Asked Questions ==

== Screenshots ==

1. Posts request extended with meta-fields from ACF and Yoast WordPress SEO
2. New menu endpoint
3. New front-page endpoint

== Links ==

* [More detailed end-point description](https://www.danielauener.com/wordpress-rest-api-extensions-for-going-headless-wp/)
* [WUXT](https://github.com/northosts/wuxt)
* [WUXT release blog post](https://www.danielauener.com/nuxt-js-wordpress-wuxt/)
* [NuxtJs](https://nuxtjs.org/)

== Credits ==

* Michael Cox [Menu Class for returning a menu as array](https://gist.github.com/michaeland/191ce08d22fed74da05a)

== Changelog ==

= 1.0 =
* Version 1.0 done
