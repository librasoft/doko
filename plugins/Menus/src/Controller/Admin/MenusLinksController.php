<?php
namespace Menus\Controller\Admin;

use Menus\Controller\AppController;

/**
 * MenusLinks Controller
 *
 * @property \Menus\Model\Table\MenusLinksTable $MenusLinks
 */
class MenusLinksController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Menus', 'ParentMenusLinks']
        ];
        $this->set('menusLinks', $this->paginate($this->MenusLinks));
        $this->set('_serialize', ['menusLinks']);
    }

    /**
     * View method
     *
     * @param string|null $id Menus Link id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $menusLink = $this->MenusLinks->get($id, [
            'contain' => ['Menus', 'ParentMenusLinks', 'ChildMenusLinks']
        ]);
        $this->set('menusLink', $menusLink);
        $this->set('_serialize', ['menusLink']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $menusLink = $this->MenusLinks->newEntity();
        if ($this->request->is('post')) {
            $menusLink = $this->MenusLinks->patchEntity($menusLink, $this->request->data);
            if ($this->MenusLinks->save($menusLink)) {
                $this->Flash->success(__('The menus link has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The menus link could not be saved. Please, try again.'));
            }
        }
        $menus = $this->MenusLinks->Menus->find('list', ['limit' => 200]);
        $parentMenusLinks = $this->MenusLinks->ParentMenusLinks->find('list', ['limit' => 200]);
        $this->set(compact('menusLink', 'menus', 'parentMenusLinks'));
        $this->set('_serialize', ['menusLink']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Menus Link id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $menusLink = $this->MenusLinks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $menusLink = $this->MenusLinks->patchEntity($menusLink, $this->request->data);
            if ($this->MenusLinks->save($menusLink)) {
                $this->Flash->success(__('The menus link has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The menus link could not be saved. Please, try again.'));
            }
        }
        $menus = $this->MenusLinks->Menus->find('list', ['limit' => 200]);
        $parentMenusLinks = $this->MenusLinks->ParentMenusLinks->find('list', ['limit' => 200]);
        $this->set(compact('menusLink', 'menus', 'parentMenusLinks'));
        $this->set('_serialize', ['menusLink']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Menus Link id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $menusLink = $this->MenusLinks->get($id);
        if ($this->MenusLinks->delete($menusLink)) {
            $this->Flash->success(__('The menus link has been deleted.'));
        } else {
            $this->Flash->error(__('The menus link could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
