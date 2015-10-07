<?php

namespace Users\Model\Table;

use Cake\ORM\Table;

/**
 * UsersSavedLogins Model
 */
class UsersSavedLoginsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users_saved_logins');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

    /**
     * Prune all the records with the given conditions.
     *
     * @param integer $user_id the owner id of the saved sessions
     * @param string $time_limit for purging only the records before the given date
     * @return boolean
     */
    public function prune($user_id = null, $time_limit = null)
    {
        $conditions = [];

        if (!empty($user_id)) {
            $conditions += [
                'user_id' => $user_id,
            ];
        }
        if (!empty($time_limit)) {
            $conditions += [
                'modified <' => new \DateTime($time_limit),
            ];
        }

        return $this->deleteAll($conditions);
    }

}
