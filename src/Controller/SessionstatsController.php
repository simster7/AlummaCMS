<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class SessionstatsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        
        if ($this->Auth->User('role') > 0) {
            $this->Flash->error(__('You do not have permission to access this page.'));
            return $this->redirect(['controller' =>'patients']);
        }
        $this->set('sessionstats', $this->paginate($this->Sessionstats));
        $this->set('_serialize', ['sessionstats']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        // $session = $this->Sessions->get($id, [
        //     'contain' => ['Sessionstats']
        // ]);
        // $this->set('session', $session);
        // $this->set('_serialize', ['session']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // $session = $this->Sessions->newEntity();
        // if ($this->request->is('post')) {
        //     $session = $this->Sessions->patchEntity($session, $this->request->data);
        //     if ($this->Sessions->save($session)) {
        //         $this->Flash->success(__('The session has been saved.'));
        //         return $this->redirect(['action' => 'index']);
        //     } else {
        //         $this->Flash->error(__('The session could not be saved. Please, try again.'));
        //     }
        // }
        // $this->set(compact('session'));
        // $this->set('_serialize', ['session']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // $session = $this->Sessions->get($id, [
        //     // 'contain' => ['Sessions']
        // ]);
        // if ($this->request->is(['patch', 'post', 'put'])) {
        //     $session = $this->Sessions->patchEntity($session, $this->request->data);
        //     if ($this->Sessions->save($session)) {
        //         $this->Flash->success(__('The session has been saved.'));
        //         return $this->redirect(['action' => 'index']);
        //     } else {
        //         $this->Flash->error(__('The session could not be saved. Please, try again.'));
        //     }
        // }
        // $this->set(compact('session'));
        // $this->set('_serialize', ['session']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        // $this->request->allowMethod(['post', 'delete']);
        // $user = $this->Users->get($id);
        // if ($this->Users->delete($user)) {
        //     $this->Flash->success(__('The user has been deleted.'));
        // } else {
        //     $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        // }
        // return $this->redirect(['action' => 'index']);
    }
}