<?php
namespace Users\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersLogin Entity.
 */
class UsersLogin extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'token' => true,
        'user_agent' => true,
        'user' => true,
    ];
}
