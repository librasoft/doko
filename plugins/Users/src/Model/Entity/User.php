<?php

namespace Users\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
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
        'name' => true,
        'description' => true,
        'language' => true,
        'timezone' => true,
        'password' => true,
        'security_token' => true,
    ];

    /**
     * List of property names that should **not** be included in JSON or Array
     * representations of this Entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'security_token',
    ];

    protected function _setPassword($password)
    {
        return $password ? (new DefaultPasswordHasher)->hash($password) : '';
    }

    protected function _setSecurityToken($token)
    {
        return $token ? (new DefaultPasswordHasher)->hash($token) : '';
    }

    public function generateToken()
    {
        return $this->security_token = hash('sha256', php_uname() . microtime(true));
    }

    public function checkToken($token)
    {
        return $token && $this->security_token && (new DefaultPasswordHasher)->check($token, $this->security_token);
    }

}
