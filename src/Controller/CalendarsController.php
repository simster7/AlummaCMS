<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class CalendarsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index() {
        return $this->redirect(['action' => 'offices']);
    }

    public function schedule() {
        $cache = [];
        $patients = $this->loadModel('Patients');
        $users = $this->loadModel('Users');
        $this->set('events', $this->generate_contents(null, $this->Auth->User('id'), $cache, $patients, $users));
    }

    public function offices()
    {
        $cache = [];
        $patients = $this->loadModel('Patients');
        $users = $this->loadModel('Users');
        $this->set('mv_events', $this->generate_contents(1, null, $cache, $patients, $users));
        $this->set('vi_events', $this->generate_contents(2, null, $cache, $patients, $users));

    }

    private function generate_contents($office, $ther, $cache, $patients, $users) {
        if ($ther == null) {
            $sessions = $this->loadModel('Sessions')->find('all', ['conditions' => ['Office =' => $office]])->toArray();
        } else {
            $sessions = $this->loadModel('Sessions')->find('all', ['conditions' => ['Therapist =' => $ther]])->toArray();
        }
        $out = '[';
        foreach ($sessions as $sess) {
            if ($sess['SessionDate'] == null) {
                continue;
            }
            $pat_id = $sess['PatientID'];
            if (array_key_exists($pat_id, $cache)) {
                $name = $cache[$pat_id][0];
                $color = $cache[$pat_id][2];
                $short_name = $cache[$pat_id][3];
            } else {
                $pat_prof = $patients->find('all', ['conditions' => ['PatientID =' => $sess['PatientID']]])->first();
                $name = $pat_prof['FirstName'].' '.$pat_prof['LastName'];
                $user_prof = $users->get($pat_prof['PrimaryTherapist']);
                $color = $user_prof->Color;
                $short_name = $user_prof->ShortName;
                $cache[$pat_id] = [$name, $pat_prof['id'], $color, $short_name];
            }
            $out .= '{';
            $out .= 'title: "'.$name.' ['.$short_name.']",';
            $out .= 'start: "'.$sess['SessionDate']->format('Y-m-d\TH:i:s').'",';
            $end_time = $sess['SessionDate']->modify('+45 minutes');
            $out .= 'end: "'.$end_time->format('Y-m-d\TH:i:s').'",';
            $out .= 'color: "'.$color.'",';
            $out .= 'url: "../patients/view/'.$cache[$pat_id][1].'/",';
            $out .= '},';
        }
        $out .= ']';
        return $out;
    }

}
