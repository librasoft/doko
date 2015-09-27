<?php
namespace Menus\Model\Entity;

use Cake\ORM\Entity;

/**
 * MenusLink Entity.
 *
 * @property int $id
 * @property int $status
 * @property int $menu_id
 * @property \Menus\Model\Entity\Menu $menu
 * @property int $parent_id
 * @property \Menus\Model\Entity\MenusLink $parent_menus_link
 * @property string $title
 * @property string $url
 * @property string $css_class
 * @property string $rel
 * @property bool $target_blank
 * @property string $icon
 * @property string $element
 * @property string $element_options
 * @property string $acl_token
 * @property int $level
 * @property int $lft
 * @property int $rght
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $created
 * @property \Menus\Model\Entity\MenusLink[] $child_menus_links
 */
class MenusLink extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
