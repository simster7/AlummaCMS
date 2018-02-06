<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PatientstatsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsToMany('Patients')
        	 ->setForeignKey('Status');
    }
}
