=== Plugin Name ===
Contributors: danielauener
Donate link: http://www.danielauener.com/
Tags: rest api, endpoint extension, headless
Requires at least: 4.7.0
Tested up to: 5.2.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extensions for the Rest API to provide endpoints that support a more convenient
use of headless WordPress as back-end CMS.

== Description ==

This plugin adds a couple of extensions to the WordPress Rest API, which are aimed to make the use of WordPress as headless CMS easier.
It is originally coded for [WUXT](https://github.com/northosts/wuxt), a dockerized development environment for headless WordPress combined with NuxtJs. However, it can be used by every other application, which needs a powerful headless WordPress back-end.

=== WordPress API Extensions ===

* **Frontpage endpoint**: There is no obvious way to get the WordPress front-page via the Rest API. To read the settings, you have to be authorized, which makes things unnecessary complicated. The new endpoint returns the front-page object if it is set, the ten newest posts otherwise.
* **Menu endpoint**: Right now, there is no way I know of, for getting menus from the API. This endpoint returns an entire menu as nested array. Default location is "main", but you can request other locations.
* **Slug endpoint**: If you are building a front-end app on top of WordPress, you have to think about how to structure your urls. WordPress has two default post-types (posts & pages) and in the urls is not distinguished which type you are requesting, so http://wp-site.expl/something might lead to a page or a post, dependent on the type of the object with the slug something. If you want to mirror that behaviour in your app, you have to do two requests for each url, one searching pages, one searching posts. To make that one request, use the slug end-point.
* **Taxonomy filter AND extension**: When filtering taxonomies with an Rest API request, all queries are OR-queries. That means you can get posts which are either in category A or B. Our adjustment lets you switch all tax_queries to an AND-relation, so that you can select posts which are both in category A and B.
* **Geo query**: If your application has to get posts by geographical proximity, you can use a geo query.
* **WordPress SEO meta fields**: They are included automatically in the <code>meta</code> object if the Yoast WordPress SEO plugin is activated.
* **Advanced custom fields** are included automatically in the <code>meta</code> object if the plugin is activated.

=== Endpoints and parameters ===

**Frontpage**

* <code>GET</code> <code>/wp-json/wuxt/v1/front-page</code>
* <code>GET</code> <code>/wp-json/wuxt/v1/front-page?_embed</code>

**Menu**

* <code>GET</code> <code>/wp-json/wuxt/v1/menu</code>
* <code>GET</code> <code>/wp-json/wuxt/v1/menu?location=&lt;location&gt;</code>

**Slug**

* <code>GET</code> <code>/wp-json/wuxt/v1/slug/&lt;post-or-page-slug&gt;</code>
* <code>GET</code> <code>/wp-json/wuxt/v1/slug/&lt;post-or-page-slug&gt;?_embed</code>

**Taxonomy filter AND extension**

* <code>GET</code> <code>/wp-json/wp/v2/posts/?categories=1,2&and=true</code>

**GEO query**

* <code>GET</code> <code>/wp-json/wp/v2/posts/?coordinates=&lt;lat&gt;,&lt;lng&gt;&distance=&lt;distance&gt;&lt;km|m&gt;</code>
* <code>GET</code> <code>/wp-json/wp/v2/posts/?coordinates=&lt;lat_meta_field&gt;:&lt;lat&gt;,&lt;lng_meta_field&gt;:&lt;lng&gt;&distance=&lt;distance&gt;&lt;km|m&gt;</code>
* <code>GET</code> <code>/wp-json/wp/v2/posts/?coordinates=52.585,13.373&distance=10</code>
* <code>GET</code> <code>/wp-json/wp/v2/posts/?coordinates=lat_mkey:52.585,lng_mkey:13.373&distance=10</code>
* <code>GET</code> <code>/wp-json/wp/v2/posts/?coordinates=52.585,13.373&distance=10m</code>

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
