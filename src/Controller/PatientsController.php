<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class PatientsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('therapist', $this->Patients->Users);
        $this->set('patientstats', $this->Patients->Patientstats);
        if ($this->Auth->User('role') < 2) {
            $patients = $this->Patients->find('all');
        } else {
            $patients = $this->Patients->find('all', ['conditions' => ['PrimaryTherapist =' => $this->Auth->User('id')]]);
        }
        $ather = $this->Patients->Users->find('list', [
            'conditions' => ['id >=' => 1],
            'keyField' => 'id',
            'valueField' => 'LastName'
        ])->toArray();
        $astat = $this->Patients->Patientstats->find('list', [
            'keyField' => 'id',
            'valueField' => 'Status'
        ])->toArray();
        $this->set('astat', $astat);
        $this->set('ather', $ather);
        $this->set('patients', $this->paginate($patients));
        $this->set('_serialize', ['patients']);
    }

    public function search($query = null, $ther = null)
    {
        if ($query == null && $ther == null) {
            //$this->search($this->request->data['query'], null, false);
            $query = '$ANY';
            $ther = null;
            if (array_key_exists('ther', $this->request->data)) {
                $ther = $this->request->data['ther'];
            }
            if (array_key_exists('query', $this->request->data)) {
                $query = $this->request->data['query'];
                if ($query == '') $query = '$ANY';
            }
            return $this->redirect(['action' => 'search', $query, $ther]);
        } else {
            if ($query == '$ANY') $query = '';
            $this->set('therapist', $this->Patients->Users);
            if ($this->Auth->User('role') < 2) {
                $patients = $this->Patients->find('all', ['conditions' => ['OR' => ['PatientID LIKE' => $query, 'FirstName LIKE' => '%'.$query.'%', 'LastName LIKE' => '%'.$query.'%']]]);
                if ($ther != null) {
                    $patients = $patients->find('all', ['conditions' => ['PrimaryTherapist =' => $ther]]); 
                }
            } else {
                $patients = $this->Patients->find('all', ['conditions' => ['AND' => ['PrimaryTherapist =' => $this->Auth->User('id'), ['OR' => ['PatientID LIKE' => $query, 'FirstName LIKE' => '%'.$query.'%', 'LastName LIKE' => '%'.$query.'%']]]]]);
            }
            $this->set('patients', $this->paginate($patients));
            $this->set('_serialize', ['patients']);
        }
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
        $patient = $this->Patients->get($id, [
            'contain' => ['Sessions']
        ]);
        $this->set('patient', $patient);
        // debug($this->Patients->Sessions->find('all')->count());
        $this->set('session', $this->Patients->Sessions);
        $this->set('sessionstat', $this->Patients->Sessions->Sessionstats);
        $this->set('therapist', $this->Patients->Users);
        $this->set('patientstats', $this->Patients->Patientstats);
        $this->set('office', $this->Patients->Sessions->Offices);
        $this->set('_serialize', ['patient']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        if ($this->Auth->User('role') > 0) {
            $this->Flash->error(__('You do not have permission to access this page.'));
            return $this->redirect(['controller' =>'patients']);
        }

        $ather = $this->Patients->Users->find('list', [
            'conditions' => ['id >=' => 1],
            'keyField' => 'id',
            'valueField' => 'LastName'
        ])->toArray();
        $this->set('ather', $ather);

        $aoff = $this->Patients->Offices->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Name'
        ])->toArray();
        $this->set('aoff', $aoff);

        $astat = $this->Patients->Patientstats->find('list', [
            'keyField' => 'id',
            'valueField' => 'Status'
        ])->toArray();
        $this->set('astat', $astat);
        
        $patient = $this->Patients->newEntity();
        if ($this->request->is('post')) {
            $patient = $this->Patients->patchEntity($patient, $this->request->data);
            if ($this->Patients->save($patient)) {
                if ($this->request->data['Add_a_diagnostic_session']) {
                    $sessionTable = $this->loadModel('Sessions');
                    $new_session = $sessionTable->newEntity(['PatientID' => $patient->PatientID,
                                    'Therapist' => $patient->PrimaryTherapist,
                                    'SessionDate' => Null,
                                    'AuthorizedDate' => Null,
                                    'Status' => 0,
                                    'Office' => $patient->Office,
                                    'FileID' => Null,
                                    'ClaimID' => Null,
                                    'MasterVendor' => Null,
                                    'AuthorizationNumber' => Null]);
                    //debug($new_session);
                    if ($sessionTable->save($new_session)) {
                        $this->Flash->success(__('The patient has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The patient could not be saved. Please, try again.'));
                    }
                }
                $this->Flash->success(__('The patient has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The patient could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('patient'));
        $this->set('_serialize', ['patient']);
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
        if ($this->Auth->User('role') > 0) {
            $this->Flash->error(__('You do not have permission to access this page.'));
            return $this->redirect(['controller' =>'patients']);
        }

        $patient = $this->Patients->get($id, [
            'contain' => ['Sessions']
        ]);
        $ather = $this->Patients->Users->find('list', [
            'conditions' => ['id >=' => 1],
            'keyField' => 'id',
            'valueField' => 'LastName'
        ])->toArray();
        $this->set('ather', $ather);

        $aoff = $this->Patients->Offices->find('list', [
            'keyField' => 'ID',
            'valueField' => 'Name'
        ])->toArray();
        $this->set('aoff', $aoff);

        $astat = $this->Patients->Patientstats->find('list', [
            'keyField' => 'id',
            'valueField' => 'Status'
        ])->toArray();
        $this->set('astat', $astat);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $patient = $this->Patients->patchEntity($patient, $this->request->data);
            if ($this->Patients->save($patient)) {
                $this->Flash->success(__('The patient has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The patient could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('patient'));
        $this->set('_serialize', ['patient']);
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
