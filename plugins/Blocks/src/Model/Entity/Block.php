<?php
namespace Blocks\Model\Entity;

use Cake\ORM\Entity;

/**
 * Block Entity.
 *
 * @property int $id
 * @property int $status
 * @property string $language
 * @property string $region
 * @property string $title
 * @property string $body
 * @property string $element
 * @property string $element_options
 * @property string $css_class
 * @property bool $show_title
 * @property string $acl_token
 * @property int $parent_id
 * @property \Blocks\Model\Entity\Block $parent_block
 * @property int $level
 * @property int $lft
 * @property int $rght
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $created
 * @property \Blocks\Model\Entity\Block[] $child_blocks
 */
class Block extends Entity
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
