# Wuxt - nuxt.js and WordPress development environment

**_Note: This project is in its early stages, not everything works and
most things will be frequently changed._**

**Wuxt** combines **_WordPress_**, the worlds biggest CMS with **_nuxt.js_**,
the most awesome front-end application framework yet.

The goal is to provide a ready to use development environment, which makes the
full power of **_WordPress_** easily available to your front-and app. Included
in Wuxt are:

-   Fully dockerized **_WordPress_** and **_nuxt.js_** container configuration,
    `docker-compose up -d` sets up everything needed in one command and you can
    start working

-   Extended Rest API to give the front-end easy access to meta-fields,
    featured media menus or the front-page configuration.

-   The newest **_nuxt.js_** version, extended with a WordPress `$wp` object, to
    connect to the extended **_WordPress_** Rest API.

All together the **Wuxt** features get you started with your front-end with just
one command, you just need to work with the intuitive WordPress admin interface
and can skip all back-end coding. But if you know your way around
**_WordPress_** you are able to easily extend the back-end as well.

## Getting started

First clone this repository to a directory you want, then change to that
directory and simply start your containers (you need to have a running
**_Docker_** installation of course):

    docker-compose up -d

That starts the following containers:

-   **_MySql_** (`mysql.wuxt`) Database for your **_WordPress_** installation. The data-folder
    of the database-container is mirrored to the \_db-folder of your host system, to
    keep the data persistent.

-   **_WordPress_** (`wp.wuxt`) on a **_Apache_** server with the newest **_PHP_** version and
    the **Wuxt** Rest API extension theme, **_ACF_** and other good-to-have plugins
    pre-installed. The wp-content directory of the **_WordPress_** directory is
    mirrored to the wp-content directory on your host.

-   **_nuxt.js_** (`front.wuxt`) started in development mode with file-monitoring and
    browser-sync and extended by a complete **_WordPress_** Rest API wrapper and a
    starter application, mimicing base functions of a **_WordPress_** theme.

Your containers are available at

-   front-end: `http://localhost:3000`
-   back-end: `http://localhost:3080`, `http://localhost:3080/wp-admin`
-   database: `docker exec -ti mysql.wuxt bash`

### Setup **_WordPress_**

Do a common **_WordPress_** installation at
`http://localhost:3080/install.php`, then login to wp-admin and select the
**wuxt** theme to activate all the API extensions. Additionally you might want
to activate the **_ACF_** plugin to make your meta-value work easier. Last but
not least you have to set the permalink structure to "Post Name" in the
**_WordPress_** settings.

To check if everything is running, visit `http://localhost:3080` and verify
that the **wuxt** info screen is showing.

Then check that the Rest API at `http://localhost:3080/wp-json` is returning
a JSON-object you are good to go.

### Setup **_nuxt.js_**

Nuxt should have been started automatically inside the docker container. The
command we use for running the **_nuxt.js_** server is `yarn dev`. Check
if the front-end is running by opening `http://localhost:3000`. You should
be greeted by the **Wuxt** intro-screen.

Check even if **_BrowserSync_** is running, by doing a minor change to the
front-page. The change should directly be visible on the front-page as well.

## WordPress Rest API endpoints

The **_WordPress_** Rest API gives you access to a wide range of native
endpoints. Find the docs at: [https://developer.wordpress.org/rest-api/reference/](https://developer.wordpress.org/rest-api/reference/). To easily access the
endpoints from **_nuxt.js_** you can use the `$wp` extension, which integrates
the [node-wpapi](https://www.npmjs.com/package/node-wp) library. You can find the full documentation [here](https://github.com/WP-API/node-wpapi).

### Extensions to the API endpoints

To make **wuxt** even more easy to use, there are a bunch of endpoint extensions to the **_WordPress_** Rest API.

#### Front-page

```
$wp.frontPage()
$wp.frontPage().embed()
```

or

```
GET: /wp-json/wuxt/v1/front-page
GET: /wp-json/wuxt/v1/front-page?_embed
```

You can use the **_WordPress_** front-page settings to build your front-ends
first page. If you setup the front-page in **_WordPress_** as static page, the
endpoint will return the corresponing page object.

If there is no front-page configured, the query automatically returns the
result of the default posts query

`GET` `/wp-json/wp/v2/posts`

Note that the `_embed` parameter works for the front-page query, which gives you
access to featured media (post-thumbnails), author information and more.

#### Menus

```
$wp.menu()
$wp.menu().location(<location>)
```

or

```
GET: /wp-json/wuxt/v1/menu
GET: /wp-json/wuxt/v1/menu?location=<location>
```

The **_WordPress_** Rest API is not providing an endpoint for menus by default,
so we added one. We have also registered a standard menu with the location `main`,
which is returned as complete menu-tree, when you request the endpoint without
parameters.

Don't forget to create a menu and adding it to a location in `wp-admin` when you
want to use this endpoint.

If you want to use multiple menus, you can request them by providing the menu
location to the endpoint.

#### Slugs

```
$wp.slug().name('<post-or-page-slug>')
$wp.slug().name('<post-or-page-slug>').embed()
```

or

```
GET: /wp-json/wuxt/v1/slug/<post-or-page-slug>
GET: /wp-json/wuxt/v1/slug/<post-or-page-slug>?_embed
```

The **_WordPress_** Rest API is not providing an endpoint to get posts or pages
by slug. That doesn't mirror the **_WordPress_** theme default behaviour,
where the url-slug can point to both a page or a post.

With the `slug` endpoint we add that function, which is first looking for a post
with the given slug and then for a page. The `embed` parameter is working for
the `slug` endpoint.

#### Meta fields

The **_WordPress_** Rest API does not include meta fields in the post objects by
default. For two of the most common plugins, ACF and Yoast WordPress SEO, we
have automatically added the values of these fields. They are located in the
`meta` section of the response objects.

## Task Management

To help you with some of the common tasks in **wuxt**, we integrated a bunch of
***gulp*** tasks. Just install the needed packages in the root directory and you
are ready to run.

    npm install

All available tasks are listed below.

### Working with the containers

Working with ***Docker*** is awesome, but has some drawbacks. One of them is
that you have to make some changes from inside the container. Two of the most common tasks are managing ***WordPress*** and installing new packages
in the front-end.

Managing ***WordPress*** **wuxt** provides you with the full power of the
***WP-CLI*** tool. Check out all documentation at [https://developer.wordpress.org/cli/commands/](https://developer.wordpress.org/cli/commands/). To run any ***WP-CLI*** command inside the `wp.wuxt`
container, just use the following ***gulp***-task:

    gulp wuxt-wp -c "<command>"

Examples: `gulp wuxt-wp -c "plugin list"`, `gulp wuxt-wp -c "plugin install advanced-custom-fields"`, `gulp wuxt-wp -c "user create wuxt me@wuxt.io"`

The same concept we use for ***yarn*** in the front container:

    gulp wuxt-yarn -c "<command>"

Example: `gulp wuxt-yarn -c "add nuxt-webfontloader"`

The commands are checking if the containers are running and installing needed
dependencies automatically. So if ***WP-CLI*** is not installed in the container it will be installed before running a `wp` command.
