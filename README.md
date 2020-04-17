![Wuxt logo](_assets/wuxt.png?raw=true "Wuxt")

# Nuxt/WordPress development environment - Wuxt

**Wuxt** combines **_WordPress_**, the worlds biggest CMS with **_nuxt.js_**,
the most awesome front-end application framework yet.

- [Quick start](#quick)
- [Introduction](#intro)
- [Architecture](#env)  
- [Getting started](#start)
  - [Custom container configuration](#start-custom)
  - [Setup WordPress](#setup-wp)
  - [Setup Nuxt.js](#setup-nuxt)  
- [Generate and Deploy](#deploy)
- [WordPress Rest API endpoints](#ep)
  - [Extensions to the API endpoints](#epp)
    - [Front-page](#epp-front)
    - [Menus](#epp-menu)
    - [Slugs](#epp-slugs)
    - [Meta queries](#epp-meta)
    - [Taxonomy queries](#epp-tax)
    - [Geo queries](#epp-geo)
    - [Custom post types](#epp-cpt)
- [Scripts](#scripts)
  - [Working with the containers](#scripts-containers)
    - [WP-CLI and yarn](#scripts-containers-tools)
  - [Scaffolding](#scripts-scaffolding)
- [Links](#links)
- [Credits](#cred)

## Quick start
<a name="quick"/>
    git clone https://github.com/northosts/wuxt.git
    cd wuxt
    docker network create nginx.proxy
    docker-compose up -d

- [http://localhost:3080/install.php](http://localhost:3080/install.php) - Install WordPress
- [http://localhost:3080/wp-admin/options-permalink.php](http://localhost:3080/wp-admin/options-permalink.php) - Set permalinks to *Post name*
- [http://localhost:3080/wp-admin/themes.php](http://localhost:3080/wp-admin/themes.php) - Activate **wuxt**-theme
- [http://localhost:3000](http://localhost:3000) - Done

## Introduction
<a name="intro"/>

The goal of Wuxt is to provide a ready to use development environment, which makes the
full power of **_WordPress_** easily available to your front-end app. Included
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

## The WUXT architecture
<a name="env"/>

![WUXT environment](_assets/wuxt-env.png?raw=true "WUXT environment")

## Getting started
<a name="start"/>

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

### Starting with custom container configuration
<a name="start-custom"/>

**wuxt** allows you to change the above setup to run multiple projects at the
same time or to adjust to your own environment. To change ports and container
names, add an `.env` file in the same directory of your `docker-compose.yml`
file. You can adjust the following values:

    WUXT_PORT_FRONTEND=3000
    WUXT_PORT_BACKEND=3080
    WUXT_PORT_DIST=8080
    WUXT_MYSQL_CONTAINER=mysql.wuxt
    WUXT_WP_CONTAINER=wp.wuxt
    WUXT_NUXT_CONTAINER=front.wuxt

After you created the file, run

    docker-compose down
    docker-compose up -d

There is even a script that does everything to you :

    npm run env

You will be asked for projectname, front-end-port, back-end-port and dist-port.
The script creates an `.env` file like the folllowing and stops the old containers:

    WUXT_PORT_FRONTEND=4000
    WUXT_PORT_BACKEND=4080
    WUXT_PORT_DIST=9080
    WUXT_MYSQL_CONTAINER=mysql.projectname
    WUXT_WP_CONTAINER=wp.projectname
    WUXT_NUXT_CONTAINER=front.projectname

After running the script start the new containers

    docker-compose up -d

### Setup **_WordPress_**
<a name="setup-wp"/>

In short:

- Install WordPress (`http://localhost:3080/install.php`)
- Set permalinks to *Post name* (`http://localhost:3080/wp-admin/options-permalink.php`)
- Activate **wuxt**-theme (`http://localhost:3080/wp-admin/themes.php`)

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
<a name="setup-nuxt"/>

Nuxt should have been started automatically inside the docker container. The
command we use for running the **_nuxt.js_** server is `yarn dev`. Check
if the front-end is running by opening `http://localhost:3000`. You should
be greeted by the **Wuxt** intro-screen.

Check if **_BrowserSync_** is running, by doing a minor change to the
front-page. The change should directly be visible on the front-page without manually reloading the page.

## Generate and Deploy
<a name="deploy"/>

To make a complete deploy of WUXT, you have to get at least one server and solve
a lot of configuration stuff to get **_WordPress_**, **_MySql_** and **_nuxt.js_**
running (we are working on a manual for some of the big cloud services).

However, there is an easier solution, at least without on-site user generated
content, like **_WordPress_**-comments (**_disqus_** would be ok, though). We
have tweaked the **_nuxt.js_** *generate*-command, so that you can generate a
fully static site with all your content, posts and pages inside the `dist`
directory of **_nuxt_**. Then it's only a matter of getting the static html-site
uploaded to a webspace of your choice.

### Generating a fully static site

First be sure your containers are running

    docker-compose up -d

Then go to the wuxt root-directory and run *generate* with yarn

    yarn generate

Despite generating your static site, this commands runs some `docker-compose`
action, so while generating, your WUXT site will be down and started again
after.

After the generation there will be started a small local web-server, which deploys
the static site on

    http://localhost:8080

To get the files you go to the `dist` directory inside your `wuxt/nuxt` directory:

    wuxt/nuxt/dist

To shut down the local web-server you have to run the following command insie the
`wuxt` directory:

    docker-compose -f dist.yml down

### How it works

The *generate*-command simply scrapes all available urls you added to your
**_nuxt.js_** and **_WordPress_** installation, saves the html-output and caches
the JSON-requests to the **_WordPress_** Rest API.

To know which urls are available, the generate command asks the **_WordPress_**
Rest API for a list of existing endpoints and all links used in the
**_WordPress_** menus. You can view that list with the following endpoint:

    GET: /wp-json/wuxt/v1/generate

Since **_nuxt.js_** doesn't fully support 100% static sites yet, we have to get
help of the `static` plugin used on **_nuxt.org_**, which is jsut taking care
of the payload caching. Read more [here](https://github.com/nuxt/rfcs/issues/22)
and [here](https://github.com/nuxt/nuxtjs.org/tree/master/modules/static).


## WordPress Rest API endpoints
<a name="ep"/>

The **_WordPress_** Rest API gives you access to a wide range of native
endpoints. Find the docs at: [https://developer.wordpress.org/rest-api/reference/](https://developer.wordpress.org/rest-api/reference/). To easily access the
endpoints from **_nuxt.js_** you can use the `$wp` extension, which integrates
the [node-wpapi](https://www.npmjs.com/package/node-wp) library. You can find the full documentation [here](https://github.com/WP-API/node-wpapi).

### Extensions to the API endpoints
<a name="epp"/>

To make **wuxt** even more easy to use, there are a bunch of endpoint extensions to the **_WordPress_** Rest API.

#### Front-page
<a name="epp-front"/>

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
<a name="epp-menu"/>

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
<a name="epp-slugs"/>

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
<a name="epp-meta"/>

The **_WordPress_** Rest API does not include meta fields in the post objects by
default. For two of the most common plugins, ACF and Yoast WordPress SEO, we
have automatically added the values of these fields. They are located in the
`meta` section of the response objects.

#### Taxonomy queries
<a name="epp-tax"/>

Taxonomy queries are limited of the simple WordPress Rest API url structure.
Especially with filtering queries, we struggled with the missing relation
parameter in queries for posts by taxonomy. We added this feature with a new
parameter to the WordPress API:

```
GET: /wp-json/wp/v2/posts/?categories=1,2&and=true
```

***Note:*** *Setting the relation to "and" will cause all taxonomy queries to
use it. Right now you cant query one taxonomy with "and" and another with "or".*

In Nuxt you just have to use the "and" param after a post query for categories.

```
$wp.posts().categories([1,2]).param('and', true)
```

#### Geo Queries
<a name="epp-geo"/>

If your application has to get posts by geographical proximity, you can use the geo parameters.

    GET /wp-json/wp/v2/posts/?coordinates=<lat>,<lng>&distance=<distance>

The coordinates parameter has to contain lat and lng, comma-separated and each value can be prefixed with the meta-key if has to be compared with (default keys: `lat`, `lng`). The distance is calculated in kilometers, postfix the value with **m** for miles. Some example queries:

     GET /wp-json/wp/v2/posts/?coordinates=52.585,13.373&distance=10
     GET /wp-json/wp/v2/posts/?coordinates=lat_mkey:52.585,lng_mkey:13.373&distance=10
     GET /wp-json/wp/v2/posts/?coordinates=52.585,13.373&distance=10m


#### Custom post types
<a name="epp-cpt"/>

The ***WordPress*** Rest API is providing endpoints for custom post types, as
long as they are registered the right way (see the *Scaffolding* section for generating cpt-definitions).

To make querying of your custom post types as easy as everything else, we added the `cpt` method to the `$wp` object. See post type queries for a
fictional 'Movies' post type, below

```
$wp.cpt('movies')
$wp.cpt('movies').id( 7 )
```

The `cpt` function returns cpt-objects similar to the `posts()` or `pages()`
queries, meta fields are included.

## Scripts
<a name="scripts"/>

To help you with some of the common tasks in **wuxt**, we integrated a bunch of
**npm** scripts. Just install the needed packages in the root directory and you
are ready to run.

    npm install

### Working with the containers
<a name="scripts-containers"/>

Working with ***Docker*** is awesome, but has some drawbacks. One of them is
that you have to make some changes from inside the container. To
enter the **WUXT** containers, you can use the following ***npm***
scripts:

    npm run enter:mysql
    npm run enter:wp
    npm run enter:front

You exit a container with `exit`.

#### WP-CLI and yarn
<a name="scripts-containers-tools"/>

Two of the most
common tasks are managing ***WordPress*** and installing new packages
in the front-end.

**WUXT** provides you with the full power of the
***WP-CLI*** tool. Check out all documentation at [https://developer.wordpress.org/cli/commands/](https://developer.wordpress.org/cli/commands/). To run any ***WP-CLI*** command inside the `wp.wuxt`
container, just use the following ***npm***-script:

    npm run wp <wp-cli-command>

Examples: `npm run wp plugin list`, `npm run wp plugin install advanced-custom-fields`, `npm run wp user create wuxt me@wuxt.io`

The same concept we use for ***yarn*** in the front container:

    npm run yarn <yarn-command>

Example: `npm run yarn add nuxt-webfontloader`

The commands are checking if the containers are running and installing needed
dependencies automatically. So if ***WP-CLI*** is not installed in the container it will be installed before running a `wp` command.

### Scaffolding
<a name="scripts-scaffolding"/>

**WUXT** allows you to generate custom post types and taxonomies via ***npm***
scripts. You can pass needed parameters as arguments. If you don't pass
arguments, you will get prompted.

**Scaffolding a post type**

    npm run scaffold:cpt <name>

    # Examples:
    npm run scaffold:cpt
    npm run scaffold:cpt Movie

The custom post type definition is copied into the `cpts` folder of the wuxt
theme and loaded automatically by the theme.

To query the new post-type you can use the `cpt` method of the **wuxt** `$wp` object.

**Scaffolding a taxonomy**

    npm run scaffold:tax <name> <post-types>

    # Examples:
    npm run scaffold:tax
    npm run scaffold:tax Venue event,cafe

The taxonomy definition is copied into the `taxonomies` folder of the wuxt
theme and loaded automatically by the theme.

## Links
<a name="links"/>

[WUXT Headless WordPress API Extensions](https://wordpress.org/plugins/wuxt-headless-wp-api-extensions/): Plugin which includes all our API extensions.

[Nuxt + WordPress = WUXT](https://www.danielauener.com/nuxt-js-wordpress-wuxt/): Introduction post for WUXT.

## Credits
<a name="cred"/>

[@yashha](https://github.com/yashha/wp-nuxt/commits?author=yashha) for the excelent idea with the `$wp` object, first implemented in [https://github.com/yashha/wp-nuxt](https://github.com/yashha/wp-nuxt)
