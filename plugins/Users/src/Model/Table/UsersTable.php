<?php

namespace Users\Model\Table;

use App\I18n\LanguageRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Users\Model\Entity\User;

/**
 * Users Model
 */
class UsersTable extends Table
{

    public function implementedEvents()
    {
        return [
            'Model.beforeSave' => 'beforeSave',
        ];
    }

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

    /**
     * Default validation rules.
     *
     * @param Validator $validator
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->add('role', [
                'valid' => [
                    'rule' => function ($value, $context) {
                        if (!Configure::read('ACL')) {
                            Configure::load('settings/acl', 'default', false);
                        }
                        return empty($value) || Configure::check('ACL.Roles.' . $value);
                    },
                ],
            ])
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('status')
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->requirePresence('password', 'create')
            ->notEmpty('password')
            ->allowEmpty('password', 'update')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
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
                if (!Configure::read('ACL')) {
                    Configure::load('settings/acl', 'default', false);
                }
                $entity->role = Configure::read('ACL.Defaults.register');
            }
            if (!$entity->has('language')) {
                $entity->language = LanguageRegistry::$current;
            }
        }

        if (!$entity->isNew() && $entity->has('password') && empty($entity->password)) {
            $entity->unsetProperty('password');
        }
    }

}
