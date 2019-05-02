# Wuxt - nuxt.js and WordPress development environment

***Note: This project is in its early stages, not everything works and
most things will be frequently changed.***

**Wuxt** combines ***WordPress***, the worlds biggest CMS with ***nuxt.js***,
the most awesome front-end application framework yet.

The goal is to provide a ready to use development environment, which makes the
full power of ***WordPress*** easily available to your front-and app. Included
in Wuxt are:

 - Fully dockerized ***WordPress*** and ***nuxt.js*** container configuration,
 `docker-compose up -d` sets up everything needed in one command and you can
 start working

 - Extended  Rest API to give the front-end easy access to meta-fields,
 featured media menus or the front-page configuration.

 - The newest ***nuxt.js*** version, extended with a WordPress `$wp` object, to
 connect to the extended ***WordPress*** Rest API.

All together the **Wuxt** features get you started with your front-end with just
one command, you just need to work with the intuitive WordPress admin interface
and can skip all back-end coding. But if you know your way around
***WordPress*** you are able to easily extend the back-end as well.

## Getting started

First clone this repository to a directory you want, then change to that
directory and simply start your containers (you need to have a running
***Docker*** installation of course):

    docker-compose up -d

That starts the following containers:

- ***MySql*** (`mysql.wuxt`) Database for your ***WordPress*** installation. The data-folder
of the database-container is mirrored to the \_db-folder of your host system, to
keep the data persistent.

- ***WordPress*** (`wp.wuxt`) on a ***Apache*** server with the newest ***PHP*** version and
the **Wuxt** Rest API extension theme, ***ACF*** and other good-to-have plugins
pre-installed. The wp-content directory of the ***WordPress*** directory is
mirrored to the wp-content directory on your host.

- ***nuxt.js*** (`front.wuxt`) started in development mode with file-monitoring and
browser-sync and extended by a complete ***WordPress*** Rest API wrapper and a
starter application, mimicing base functions of a ***WordPress*** theme.

Your containers are available at

- front-end: `http://localhost:3000`
- back-end: `http://localhost:3080`, `http://localhost:3080/wp-admin`
- database: `docker exec -ti mysql.wuxt bash`

### Setup ***WordPress***

Do a common ***WordPress*** installation at
`http://localhost:3080/install.php`, then login to wp-admin and select the
**wuxt** theme to activate all the API extensions. Additionally you might want
to activate the ***ACF*** plugin to make your meta-value work easier. Last but
not least you have to set the permalink structure to "Post Name" in the
***WordPress*** settings.

To check if everything is running, visit `http://localhost:3080` and verify
that the **wuxt** info screen is showing.

Then check that the Rest API at `http://localhost:3080/wp-json` is returning
a JSON-object you are good to go.

### Setup ***nuxt.js***

Nuxt should have been started automatically inside the docker container. The
command we use for running the ***nuxt.js*** server is `yarn dev`. Check
if the front-end is running by opening `http://localhost:3000`. You should
be greeted by the **Wuxt** intro-screen.

Check even if ***BrowserSync*** is running, by doing a minor change to the
front-page. The change should directly be visible on the front-page as well.

## WordPress Rest API endpoints

The ***WordPress*** Rest API gives you access to a wide range of native
endpoints. Find the docs at:  [https://developer.wordpress.org/rest-api/reference/](https://developer.wordpress.org/rest-api/reference/). To easily access the
endpoints from ***nuxt.js*** you can use the `$wp` extension, which integrates
the [node-wpapi](https://www.npmjs.com/package/node-wp) library. You can find the full documentation [here](https://github.com/WP-API/node-wpapi).

### Extensions to the API endpoints

To make **wuxt** even more easy to use, there are a bunch of endpoint extensions to the ***WordPress*** Rest API.

#### Front-page

`GET` `/wp-json/wuxt/v1/front-page`

`GET` `/wp-json/wuxt/v1/front-page?_embed`

You can use the ***WordPress*** front-page settings to build your front-ends
first page. If you setup the front-page in ***WordPress*** as static page, the
endpoint will return the corresponing page object.

If there is no front-page configured, the query automatically returns the
result of the default posts query

`GET` `/wp-json/wp/v2/posts`

Note that the `_embed` parameter works for the front-page query, which gives you
access to featured media (post-thumbnails), author information and more.

#### Menus

`GET` `/wp-json/wuxt/v1/menu`

`GET` `/wp-json/wuxt/v1/menu?menu=main`

The ***WordPress*** Rest API is not providing an endpoint for menus by default,
so we added one. We have also registered a standard menu with the location `main`,
which is returned as complete menu-tree, when you request the endpoint without
parameters.

Don't forget to create a menu and adding it to a location in `wp-admin` when you
want to use this endpoint.

If you want to use multiple menus, you can request them by providing the menu
location to the endpoint.

#### Slugs

`GET` `/wp-json/wuxt/v1/slug/<post-or-page-slug>`

`GET` `/wp-json/wuxt/v1/slug/<post-or-page-slug>?_embed`

The ***WordPress*** Rest API is not providing an endpoint to get posts or pages
by slug. That doesn't mirror the ***WordPress*** theme default behaviour,
where the url-slug can point to both a page or a post.

With the `slug` endpoint we add that function, which is first looking for a post
with the given slug and then for a page. The `embed` parameter is working for
the `slug` endpoint.
