<?php
namespace Menus\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Menus\Model\Entity\MenusLink;

/**
 * MenusLinks Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Menus
 * @property \Cake\ORM\Association\BelongsTo $ParentMenusLinks
 * @property \Cake\ORM\Association\HasMany $ChildMenusLinks
 */
class MenusLinksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('menus_links');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('Menus', [
            'foreignKey' => 'menu_id',
            'joinType' => 'INNER',
            'className' => 'Menus.Menus'
        ]);
        $this->belongsTo('ParentMenusLinks', [
            'className' => 'Menus.MenusLinks',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildMenusLinks', [
            'className' => 'Menus.MenusLinks',
            'foreignKey' => 'parent_id'
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
            ->allowEmpty('id', 'create');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('url', 'create')
            ->notEmpty('url');

        $validator
            ->allowEmpty('css_class');

        $validator
            ->allowEmpty('rel');

        $validator
            ->add('target_blank', 'valid', ['rule' => 'boolean'])
            ->requirePresence('target_blank', 'create')
            ->notEmpty('target_blank');

        $validator
            ->allowEmpty('icon');

        $validator
            ->allowEmpty('element');

        $validator
            ->allowEmpty('element_options');

        $validator
            ->allowEmpty('acl_token');

        $validator
            ->add('level', 'valid', ['rule' => 'numeric'])
            ->requirePresence('level', 'create')
            ->notEmpty('level');

        $validator
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lft', 'create')
            ->notEmpty('lft');

        $validator
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rght', 'create')
            ->notEmpty('rght');

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
        $rules->add($rules->existsIn(['menu_id'], 'Menus'));
        $rules->add($rules->existsIn(['parent_id'], 'ParentMenusLinks'));
        return $rules;
    }
}
