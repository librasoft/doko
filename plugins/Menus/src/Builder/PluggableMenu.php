<?php

namespace Menus\Builder\PluggableMenu;

use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class PluggableMenu
{

    protected static $_items = [];
    protected static $_itemDefaults = [
        'acl_token' => null,
        'children' => [],
        'divider' => false,
        'options' => null,
        'title' => null,
        'url' => null,
        'weight' => 1,
    ];

    /**
     * Add an item to the menu.
     * If parents is not defined, add them to.
     *
     * @param string $path Dot separated path in the array.
     * @param array $item Item to add.
     * @param string $alias the alias of the current menu.
     * @return void
     */
    public static function add($path, $item, $alias = 'DokoBackend')
    {
        if (strpos($path, '.') !== false) {
            $parent_path = substr($path, 0, strrpos($path, '.'));

            if (!empty($parent_path) && !self::_check($parent_path, $alias)) {
                self::add($parent_path, [
                    'title' => Inflector::humanize(substr($path, strrpos($path, '.') + 1)),
                ], $alias);
            }
        }

        self::_insert($path, self::_normalizeItem($item), $alias);
    }

    /**
     * Fill an item's empty options with DokoNav defaults value.
     * Recursively setup of children items.
     *
     * @param array $item
     */
    protected static function _normalizeItem($item)
    {
        $item += self::$_itemDefaults;

        foreach ($item['children'] as $child) {
            $child = self::_normalizeItem($child);
        }

        return $item;
    }

    /**
     * Check if given path exists in the navigation.
     *
     * @param string $path Dot separated path in the array.
     * @param string $alias the alias of the current menu.
     * @return boolean
     */
    protected static function _check($path, $alias)
    {
        return Hash::check(self::$_items, $alias . '.' . str_replace('.', '.children.', $path));
    }

    /**
     * Insert an item in the given path.
     *
     * @param string $path Dot separated path in the array.
     * @param array $item Item to Insert
     * @param string $alias the alias of the current menu.
     */
    protected static function _insert($path, $item, $alias)
    {
        self::$_items = Hash::insert(self::$_items, $alias . '.' . str_replace('.', '.children.', $path), $item);
    }

    /**
     * Remove the item in the given path.
     *
     * @param string $path Dot separated path in the array.
     * @param string $alias the alias of the current menu.
     * @return void
     */
    public static function remove($path, $alias = 'DokoBackend')
    {
        self::$_items = Hash::remove(self::$_items, $alias . '.' . str_replace('.', '.children.', $path));
    }

    /**
     * Clear all items.
     *
     * @param string $alias the alias of the current menu.
     * @return void
     */
    public static function clear($alias = 'DokoBackend')
    {
        self::$_items[$alias] = [];
    }

    /**
     * Returns items in array format.
     * Returns only the links that a user has the permission to enter.
     *
     * @param string $alias the alias of the current menu.
     * @return array
     */
    public static function items($alias = 'DokoBackend')
    {
        return self::_items(self::$_items[$alias]);
    }

    /**
     * Recursively returns the items.
     * Check the Access list if a given permission is required.
     *
     * @param array $items
     * @return array $items ready to use from Helper
     */
    protected static function _items($items)
    {
        if (empty($items)) {
            return [];
        }

        $items = Hash::sort($items, '{s}.weight', 'ASC');
        $entries = [];

        foreach ($items as $item) {
            $entry = [];
            $entry['acl_token'] = $item['acl_token'];

            if ($item['divider']) {
                $entry['options'] = [
                    'class' => 'divider',
                ] + $item['options'];
            } else {
                $entry['link'] = [
                    'title' => $item['title'],
                    'url' => $item['url'],
                ];
                $entry['options'] = $item['options'];
                $entry['children'] = self::_items($item['children']);
            }

            $entries[] = $entry;
        }

        //Clean up separators
        return self::_cleanSeparators($entries);
    }

    /**
     * Remove useless separators.
     *
     * @param type $entries
     * @return type
     */
    protected static function _cleanSeparators($entries)
    {
        //Remove first and duplicate separators
        $prev_sep = true;

        foreach ($entries as $key => $entry) {
            $current_sep = empty($entry['link']);

            if ($prev_sep && $current_sep) {
                unset($entries[$key]);
            }

            $prev_sep = $current_sep;
        }

        //Remove the last element if separator
        end($entries);
        $last_key = key($entries);

        if (empty($entries[$last_key]['link'])) {
            unset($entries[$last_key]);
        }

        return $entries;
    }

}
