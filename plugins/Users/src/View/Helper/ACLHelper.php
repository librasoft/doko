<?php
namespace Users\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Users\Auth\ACL;

/**
 * ACL helper
 */
class ACLHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    protected $_acl;

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
        $this->_acl = new ACL($View->get('authUser')['role']);
    }

    public function can($operation, $user_role = null)
    {
        return $this->_acl->can($operation, $user_role);
    }

    public function requiresAuth($operation, $is_login_restored = true)
    {
        return $this->_acl->requiresAuth($operation, $is_login_restored);
    }

}
