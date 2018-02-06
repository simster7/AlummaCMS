<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SessionsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Patients')
        	 ->setForeignKey('PatientID')
             ->bindingKey('PatientID');

        $this->hasOne('Sessionstats')
             ->setForeignKey('ID');

        $this->hasOne('Users')
             ->setForeignKey('id');

        $this->hasOne('Offices')
             ->setForeignKey('ID');
    }
}
