<?php

/**
 * Helper class to display menu items in a hierarchical array
 * Thanks for this awesome peace of code to Michael Cox
 * https://gist.github.com/michaeland
 */
class Menu
{
    protected $menu;
    protected $tree;

    /**
     * Initialises $this->menu
     */
    public function __construct($menuName = '', $args = array(), $filter = null)
    {
        $filter = is_callable($filter) ? $filter : null;
        if (empty($menuName)) {
            throw new Exception('No menu location name provided.');
            return;
        }
        // $menuLocations = get_nav_menu_locations();
        // if (empty($menuLocations[$menuName])) return;
        $this->menu = $this->retrieveMenu(wp_get_nav_menu_object($menuName), $args, $filter);
    }

    /**
     * Retrieves menu from wordpress
     * @uses  private core function _wp_menu_item_classes_by_context()
     * @return Array|null $this->menu
     */
    protected function retrieveMenu($navMenuObject = null, $args = array(), $filter = null)
    {
        global $wp_query;
        global $post;
        $queriedPostType = get_post_type();
        $this->queriedPostType = $queriedPostType;
        $isTax = (is_tax() || is_tag());
        if (!$navMenuObject) return null;
        $menu_items = wp_get_nav_menu_items($navMenuObject->term_id, $args);
        /* the following was taken from wp_nav_menu core function
               * line 154–169: https://developer.wordpress.org/reference/functions/wp_nav_menu/
               */
        // set up menu item classes
        _wp_menu_item_classes_by_context($menu_items);
        $sorted_menu_items = $menu_items_with_children = array();
        foreach ((array)$menu_items as $menu_item) {
            $post = get_post($menu_item->object_id);

            if ($post) $menu_item->slug = $post->post_name === 'hem' ? '' : $post->post_name;

            $sorted_menu_items[$menu_item->menu_order] = $menu_item;
            if ($menu_item->menu_item_parent)
                $menu_items_with_children[$menu_item->menu_item_parent] = true;
        }

        // Add the menu-item-has-children class where applicable
        if ($menu_items_with_children) {
            foreach ($sorted_menu_items as &$menu_item) {
                if (isset($menu_items_with_children[$menu_item->ID]))
                    $menu_item->classes[] = 'menu-item-has-children';
            }
        }
        /*
               * end taken wp_nav_menu from core function
               */
        foreach ($menu_items as $key => &$menu_item) {
            // Add isCurrent class to parents or ancestors
            $menu_item->isCurrent = false;
            if ($menu_item->current_item_ancestor) $menu_item->isCurrent = 'ancestor';
            if ($menu_item->current_item_parent && ($menu_item->type !== 'taxonomy')) {
                $menu_item->isCurrent = 'parent ancestor';
            }
            if ($menu_item->current) $menu_item->isCurrent = 'current';
            // Add isCurrent class, and ancestor classes to custom post type archive menu items
            // $isCurrentCustomPostType = static::menuItemIsCustomPostTypeArchive($menu_item, $queriedPostType);
            // // if is current
            // if ($isCurrentCustomPostType) {
            //     $menu_item->classes[] = "current-{$queriedPostType}-ancestor";
            //     $menu_item->isCurrent = 'ancestor';
            // }
            // if is current and !hierarchical
            if ($isCurrentCustomPostType && !is_post_type_hierarchical($queriedPostType)) {
                $menu_item->classes[] = "current-{$queriedPostType}-parent";
                $menu_item->isCurrent = 'parent ancestor';
            }
            // if is current and hierarchical and top level
            if (
                $post
                && $isCurrentCustomPostType
                && is_post_type_hierarchical($queriedPostType)
                && (count(get_post_ancestors($post->ID)) === 0)
            ) {
                $menu_item->classes[] = "current-{$queriedPostType}-parent";
                $menu_item->isCurrent = 'parent ancestor';
            }
            // if a filter exists, run it
            if ($filter && is_callable($filter)) {
                $filtered = $filter($menu_item);
                $menu_item = $filtered ? $filtered : null;
                if (!$menu_item) unset($menu_items[$key]);
            }
        }
        return $this->menu = $menu_items;
    }
    /**
     * Returns $this->menu
     */
    public function getMenuItems()
    {
        return $this->menu;
    }
    /**
     * Returns $this->tree
     *
     * @return Array|null $tree
     */
    public function getTree()
    {
        if ($this->tree !== null) return $this->tree;
        $tree = static::buildTree($this->menu, 0, 1);
        return $tree ? $tree : null;
    }
    /**
     * Transform a navigational menu to a tree structure
     *
     * @return Array $branch
     */
    public static function buildTree(array &$elements, $parentId = 0, $level = 1)
    {
        $branch = array();
        foreach ($elements as &$element) {
            if ($element->menu_item_parent == $parentId) {
                $subLevel = $level + 1;
                $element->level = $level;
                $children = static::buildTree($elements, $element->ID, $subLevel);
                if ($children) {
                    $element->children = $children;
                } else {
                    $element->children = array();
                }
                $branch[$element->ID] = $element;
                unset($element);
            }
        }
        return $branch;
    }
    /**
     * Checks if menu item is a custom post type archive
     *
     * @return Boolean
     */
    protected static function menuItemIsCustomPostTypeArchive($menuItem, $type = null)
    {
        $isCustomPostType = (isset($menuItem->type)
            && ($menuItem->type === 'post_type_archive')
            && (is_post_type_archive($this->queriedPostType) || is_singular($this->queriedPostType)));
        if (!$type) {
            return $isCustomPostType;
        }
        $isOfType = ($isCustomPostType
            && isset($menuItem->object)
            && ($menuItem->object === $type));
        return $isOfType;
    }
    /**
     * Returns maximum depth of menu tree
     *
     * @return Integer
     */
    public static function menuItemDepth($menuItem = null)
    {
        $maxDepth = 0;
        foreach ($menuItem->children as $child) {
            if (is_array($child->children)) {
                $depth = static::menuItemDepth($child) + 1;
                if ($depth > $maxDepth) $maxDepth = $depth;
            }
        }
        return $maxDepth;
    }
}


/**
 * Adds a menu endpoint
 */

add_action('init', 'wuxt_register_menu');
function wuxt_register_menu()
{
    register_nav_menu('main', __('Main meny'));
    register_nav_menu('social', __('Social meny'));
}


add_action('rest_api_init', 'wuxt_route_menu');
function wuxt_route_menu()
{
    register_rest_route('wuxt', '/v1/menu', array(
        'methods' => 'GET',
        'callback' => 'wuxt_get_menu',
    ));
}


function wuxt_get_menu($params)
{
    $params = $params->get_params();
    $theme_locations = get_nav_menu_locations();

    if (!isset($params['location'])) {
        $params['location'] = 'main';
    }

    if (!isset($theme_locations[$params['location']])) {
        return '{"error": "Menu loacation does not exist."}';
    }

    $menu_obj = get_term($theme_locations[$params['location']], 'nav_menu');

    $menu_name = $menu_obj->name;

    $menu = new Menu($menu_name);

    return $menu->getTree();
}
