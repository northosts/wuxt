<?php
     /*
     Plugin Name: WUXT Headless WordPress API Extensions
     Plugin URI: http://www.danielauener.com/wordpress-rest-api-extensions-for-going-headless-wp/
     Description: Handy rest api extensions for front-page, menus, slugs, meta-fields and tax-queries, used by WUXT, dockerized WordPress/NuxtJs development environment. However, ready to use for any headless WordPress application.
     Version: 1.0
     Author: @danielauener
     Author URI: http://www.danielauener.com
     */

     // adding a front-page endpoint
     require_once(dirname(__FILE__) . '/extensions/front-page.php');

     // adding a menu endpoint
     require_once(dirname(__FILE__) . '/extensions/menu.php');

     // adding a slug endpoint
     require_once(dirname(__FILE__) . '/extensions/slug.php');

     // integrating common meta-fields
     require_once(dirname(__FILE__) . '/extensions/meta.php');

     // activating AND-relations for tax-queries
     require_once(dirname(__FILE__) . '/extensions/relation.php');

     // activating geo queries
     require_once(dirname(__FILE__) . '/extensions/geo.php');
