<?php
namespace Users\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Users\Model\Entity\UsersLogin;

/**
 * UsersLogins Model
 */
class UsersLoginsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users_logins');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'Users.Users'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('token', 'create')
            ->notEmpty('token')
            ->requirePresence('user_agent', 'create')
            ->notEmpty('user_agent');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

    /**
     * Prune all the records with the given conditions.
     *
     * @param integer $user_id	the owner id of the saved sessions
     * @param string $time_limit for purging only the records before the given date
     * @return boolean
     */
    public function prune($user_id = null, $time_limit = null)
    {
        $conditions = array();

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
