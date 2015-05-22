<?php

namespace Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * UsersSavedLogin Entity.
 */
class UsersSavedLogin extends Entity
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

    protected function _setToken($token)
    {
        return (new DefaultPasswordHasher)->hash($token);
    }

}
