<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PatientsTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Sessions')
             ->setForeignKey('PatientID')
             ->bindingKey('PatientID');

        $this->hasOne('Users')
        	 ->setForeignKey('id');

        $this->hasOne('Patientstats')
        	 ->setForeignKey('ID');

        $this->hasOne('Offices')
             ->setForeignKey('ID');
    }

    protected function _getFullName() {
		return $this->FirstName . ' ' . $this->LastName;
	}

}
