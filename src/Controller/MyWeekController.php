<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 * test
 * @property \App\Model\Table\UsersTable $Users
 */
class MyWeekController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index() {
        $cache = [];
        $patients = $this->loadModel('Patients');
        $res = $this->generate_contents(null, $this->Auth->User('id'), $cache, $patients);
        $this->set('events', $res[0]);
        $this->set('patientList', $res[1]);
    }

    private function generate_contents($office, $ther, $cache, $patients) {
        $sessionTable = $this->loadModel('Sessions');
        $sessions = $sessionTable->find('all', ['conditions' => ['Therapist =' => $ther]])->toArray();

        $selectOut = '<select id="selectPatient" class="js-example-basic-single">';
        $out = '[';
        foreach ($sessions as $sess) {
            $pat_id = $sess['PatientID'];
            if (array_key_exists($pat_id, $cache)) {
                $name = $cache[$pat_id][0];
                $pat_db_id = $cache[$pat_id][1];
                $sessions_left = $cache[$pat_id][2];
            } else {
                $pat_prof = $patients->find('all', ['conditions' => ['PatientID =' => $sess['PatientID']]])->first();
                $name = $pat_prof['FirstName'].' '.$pat_prof['LastName'];
                $sessions_left = $sessionTable->find('all', ['conditions' => ['PatientID =' => $pat_id, 'Status =' => 0]])->count();
                $cache[$pat_id] = [$name, $pat_prof['id'], $sessions_left];
                $pat_db_id = $pat_prof['id'];

                $selectOut .= '<option value="'.$pat_id.'">'.$name.' '.$pat_prof['DateOfBirth'].' '.$pat_id.'</option>';
            }
            if ($sess['SessionDate'] == null || $sess['Status'] == 6) {
                continue;
            }
            $out .= '{';
            if ($sess['Status'] == 8) {
                $out .= 'title: "[TENTATIVE] '.$name.'\n'.$sessions_left.' approved sessions left",';
                $out .= 'color: "#c4c5c6",';
            } else {
                $out .= 'title: "'.$name.'\n'.$sessions_left.' approved sessions left",';
            }
            $out .= 'start: "'.$sess['SessionDate']->format('Y-m-d\TH:i:s').'",';
            $end_time = $sess['SessionDate']->modify('+45 minutes');
            $out .= 'end: "'.$end_time->format('Y-m-d\TH:i:s').'",';
            //$out .= 'startEditable: "'.($sessions_left > 0).'",';
            // $out .= 'url: "../patients/view/'.$cache[$pat_id][1].'/",';
            $out .= 'PatientID: "'.$pat_id.'",';
            $out .= 'PatientDBID: "'.$pat_db_id.'",';
            $out .= 'name: "'.$name.'",';
            $out .= 'SessionID: "'.$sess['SessionID'].'",';
            $out .= 'sessionsLeft: "'.$sessions_left.'",';
            $out .= 'status: "'.$sess['Status'].'",';
            $out .= '},';
        }
        $selectOut .= '</select>';
        $out .= ']';
        return [$out, $selectOut];
    }

    public function schedule() {
        $pat_id = $this->request->data['PatientID'];
        $schedule_datetime = $this->request->data['datetime'];
        $sessionTable = $this->loadModel('Sessions');
        $open_session = $sessionTable->find('all', ['conditions' => ['PatientID =' => $pat_id, 'Status =' => '0']])->first();
        if ($open_session == null) {
            $datum = $sessionTable->find('all', ['conditions' => ['PatientID =' => $pat_id]])->first();
            $new_session = $sessionTable->newEntity(['PatientID' => $pat_id,
                            'Therapist' => $datum->Therapist,
                            'SessionDate' => $schedule_datetime,
                            'AuthorizedDate' => Null,
                            'Status' => 8,
                            'Office' => $datum->Office,
                            'FileID' => Null,
                            'ClaimID' => Null,
                            'MasterVendor' => Null,
                            'AuthorizationNumber' => Null]);
            //debug($new_session);
            if($sessionTable->save($new_session)) {
                echo "%No open session for this patient was found, created a tentative session!";
            } else {
                echo "Error in creating a tentative session.";
            }
            die(); 
        }
        $session = $sessionTable->get($open_session->SessionID);
        if ($session->Status == 0) {
            $session->SessionDate = $schedule_datetime;
            $session->Status = 1;
            $sessionTable->save($session);
            echo 'success';
            die();
        } else {
            echo 'Error: session being scheduled is not approved';
            die();
        }
    }

    public function showOrNoShow() {
        // $this->autoRender = false;
        $sess_id = $this->request->data['SessionID'];
        $show = $this->request->data['show'];
        $sessionTable = $this->loadModel('Sessions');
        $session = $sessionTable->get($sess_id);
        // $session = $this->Sessions->get($sess_id);
        if ($session != null) {
            if ($session->Status != 1) {
                echo "Only sessions that are marked as \"Scheduled\" can be updated, if there has been a mistake please contact an administrator.";
                die();
            }
            if ($show == 'true') {
                $session->Status = 2;
                $sessionTable->save($session);
                echo "Session marked as Completed";
                die();
            } else {
                $session->Status = 0;
                $session->SessionDate = null;
                $sessionTable->save($session);
                echo "Session reset to approved";
                die();
            }
        } else {
            echo "There was an error with your request, the session you are trying to change does not exist.";
        }
        die();
    }
}
