<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class SessionsController extends AppController
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
        $astat = $this->Sessions->Sessionstats->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Status'
        ])->toArray();
        $this->set('astat', $astat);
        $this->set('sessionstat', $this->Sessions->Sessionstats);
        $this->set('therapist', $this->Sessions->Users);
        $this->set('sessions', $this->paginate($this->Sessions));
        $this->set('office', $this->Sessions->Offices);
        $this->set('patient', $this->Sessions->Patients);
        $this->set('_serialize', ['sessions']);
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

        if ($this->Auth->User('role') > 0) {
            $this->Flash->error(__('You do not have permission to access this page.'));
            return $this->redirect(['controller' =>'patients']);
        }
        $session = $this->Sessions->get($id, []);
        $this->set('sessionstat', $this->Sessions->Sessionstats);
        $this->set('patient', $this->Sessions->Patients);
        $this->set('therapist', $this->Sessions->Users);
        $this->set('office', $this->Sessions->Offices);
        $this->set('session', $session);
        $this->set('_serialize', ['session']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */

    public function add($PatientID = null, $NumberOfSessions = 1) {
        if ($this->Auth->User('role') > 0) {
            $this->Flash->error(__('You do not have permission to access this page.'));
            return $this->redirect(['controller' =>'patients']);
        }
        $this->set('PatientID', $PatientID);
        $astat = $this->Sessions->Sessionstats->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Status'
        ])->toArray();
        $this->set('astat', $astat);

        $ather = $this->Sessions->Users->find('list', [
            'conditions' => ['id >=' => 1],
            'keyField' => 'id',
            'valueField' => 'LastName'
        ])->toArray();
        $this->set('ather', $ather);

        $aoff = $this->Sessions->Offices->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Name'
        ])->toArray();
        $this->set('aoff', $aoff);
        if ($PatientID != null) {
            $pat_prof = $this->Sessions->Patients->find('all', ['conditions' => ['PatientID =' => $PatientID]])->first();
            $this->set('def_ther', $pat_prof['PrimaryTherapist']);
        } else {
            $this->set('def_ther', '-1');
        }

        $data = $this->request->data; 
        $data = array_fill(0, $NumberOfSessions, $data);
        $entities = $this->Sessions->newEntities($data);
        $this->set('NumberOfSessions', $NumberOfSessions);

        if ($this->request->is('post')) {
            if ($this->Sessions->saveMany($entities)) {
                $this->Flash->success(__('The session(s) has been saved.'));
                if ($PatientID == null) {
                    $pat_prof = $this->Sessions->Patients->find('all', ['conditions' => ['PatientID =' => $session->PatientID]])->first();
                }
                return $this->redirect(['controller' => 'Patients', 'action' => 'view', $pat_prof['id']]);
            } else {
                $this->Flash->error(__('The session(s) could not be saved. Please, try again.'));
            }
        }

        //if ($this->request->is('post')) {
        //    $session = $this->Sessions->patchEntity($session, $this->request->data);
        //    if ($this->Sessions->save($session)) {
        //        $this->Flash->success(__('The session has been saved.'));
        //        if ($PatientID == null) {
        //            $pat_prof = $this->Sessions->Patients->find('all', ['conditions' => ['PatientID =' => $session->PatientID]])->first();
        //        }
        //        return $this->redirect(['controller' => 'Patients', 'action' => 'view', $pat_prof['id']]);
        //    } else {
        //        $this->Flash->error(__('The session could not be saved. Please, try again.'));
        //    }
        //}

        $this->set(compact('session'));
        $this->set('_serialize', ['session']);
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

        $astat = $this->Sessions->Sessionstats->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Status'
        ])->toArray();
        $this->set('astat', $astat);

        $ather = $this->Sessions->Users->find('list', [
            'conditions' => ['id >=' => 1],
            'keyField' => 'id',
            'valueField' => 'LastName'
        ])->toArray();
        $this->set('ather', $ather);
        $aoff = $this->Sessions->Offices->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Name'
        ])->toArray();
        $this->set('aoff', $aoff);
        $session = $this->Sessions->get($id, [
            // 'contain' => ['Sessions']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $session = $this->Sessions->patchEntity($session, $this->request->data);
            if ($this->Sessions->save($session)) {
                $this->Flash->success(__('The session has been saved.'));
                $pat_id = $this->Sessions->Patients->find('all', ['conditions' => ['PatientID =' => $session->PatientID]])->first()['id'];
                return $this->redirect(['controller' => 'Patients', 'action' => 'view', $pat_id]);
            } else {
                $this->Flash->error(__('The session could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('session'));
        $this->set('_serialize', ['session']);
    }

    public function search($query = null)
    {
        if ($query == null) {
            $query = $this->request->data['status'];
            return $this->redirect(['action' => 'search', $query]);
        } else {
            $this->set('sessionstat', $this->Sessions->Sessionstats);
            $this->set('patient', $this->Sessions->Patients);
            $this->set('therapist', $this->Sessions->Users);
            $this->set('office', $this->Sessions->Offices);
            $sessions = $this->Sessions->find('all', ['conditions' => ['Status =' => $query]]);
            $this->set('sessions', $this->paginate($sessions));
            $this->set('_serialize', ['patients']);
        }
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
        if ($this->Auth->User('role') > 0) {
            $this->Flash->error(__('You do not have permission to access this page.'));
            return $this->redirect(['controller' =>'patients']);
        }
        $this->request->allowMethod(['post', 'delete']);
        $session = $this->Sessions->get($id);
        if ($this->Sessions->delete($session)) {
                $pat_id = $this->Sessions->Patients->find('all', ['conditions' => ['PatientID =' => $session->PatientID]])->first()['id'];
                return $this->redirect(['controller' => 'Patients', 'action' => 'view', $pat_id]);
        } else {
            $this->Flash->error(__('The session could not be deleted. Please, try again.'));
        }
        return $this->redirect(['controller' => 'Patients', 'action' => 'index']);
    }

}
