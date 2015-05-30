<?php

namespace Users\Auth;

use Cake\Core\Configure;

class ACL
{

    protected $_roles;
    protected $_userRole;
    protected $_requireAuth;

    public function __construct($user_role = null)
    {
        if (!Configure::read('ACL')) {
            Configure::load('acl', 'default', false);
        }

        $this->_roles = Configure::read('ACL.Roles');

        if (!$this->_roles[Configure::read('ACL.Defaults.anonymous')] || !$this->_roles[Configure::read('ACL.Defaults.register')]) {
            throw new \RuntimeException('Bad ACL configuration');
        }

        $this->_userRole = $user_role ?: Configure::read('ACL.Defaults.register');
        $this->_requireAuth = Configure::read('ACL.Auth');

        foreach ($this->_roles as $key => $values) {
            $this->_roles[$key]['can'] = array_combine($values['can'], array_fill(0, count($values['can']), true));
        }
    }

    /**
     * Checks if a role has the permission to do the given operation.
     * If the role is not defined, use the role of the current session.
     *
     * @param string $operation
     * @param mixed $user_role
     * @return boolean
     */
    public function can($operation, $user_role = null)
    {
        if (!$user_role) {
            $user_role = $this->_userRole;
        }

        return isset($this->_roles[$user_role]['can'][$operation]);
    }

    /**
     * Checks if the given operation needs the user to re-insert his password
     * if the session has been restored.
     *
     * @param string $operation
     * @param boolean $is_login_restored
     * @return boolean
     */
    public function requiresAuth($operation, $is_login_restored = true)
    {
        if (in_array($operation, $this->_requireAuth['everysession'])) {
            return $is_login_restored;
        }
        if (in_array($operation, $this->_requireAuth['everytime'])) {
            return true;
        }
        return false;
    }

}
