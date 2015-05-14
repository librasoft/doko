<?php
namespace Users\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Users\Model\Entity\User;

/**
 * Users Model
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function implementedEvents()
    {
        return [
            'Model.beforeSave' => 'beforeSave',
        ];
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
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status')
            ->requirePresence('role', 'create')
            ->notEmpty('role')
            ->add('role', [
                'valid' => [
                    'rule' => function ($value, $context) {
                        if (!Configure::read('ACL')) {
                            Configure::load('acl', 'default', false);
                        }
                        return empty($value) || Configure::check('ACL.roles.' . $value);
                    },
                ],
            ])
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->requirePresence('password', 'create')
            ->notEmpty('password')
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->requirePresence('language', 'create')
            ->notEmpty('language')
            ->requirePresence('timezone', 'create')
            ->notEmpty('timezone');

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
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }

    public function beforeSave(Event $event, User $entity)
    {
        if ($entity->isNew()) {
            if (!$entity->has('role')) {
                $entity->role = Configure::read('ACL.default-role');
            }
        }

        if (!$entity->isNew() && $entity->has('password') && empty($entity->password)) {
            $entity->unsetProperty('password');
        }
    }
}
