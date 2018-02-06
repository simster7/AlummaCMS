<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table {

    public function initialize(array $config)
    {
        $this->belongsToMany('Sessions')
        	 ->setForeignKey('Therapist');


        $this->belongsToMany('Patients')
        	 ->setForeignKey('PrimaryTherapist');
    }

	public function validationDefault(Validator $validator) {
		return $validator
			->notEmpty('username', 'Please enter a username')
			->notEmpty('password', 'Please enter a password')
			->notEmpty('role', 'Please enter a role')
			->add('role', 'inList', [
				'rule' => ['inList', ['0', '1', '2']],
				'message' => 'Please enter a valid role'
				]);
	}

    protected function _getFullName() {
		return $this->FirstName . ' ' . $this->LastName;
	}
}