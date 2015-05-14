<?php
namespace Users\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity.
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'status' => true,
        'role' => true,
        'email' => true,
        'password' => true,
        'name' => true,
        'description' => true,
        'language' => true,
        'timezone' => true,
        'security_token' => true,
        'password_updated' => true,
    ];
}
