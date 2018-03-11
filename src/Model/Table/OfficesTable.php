<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class OfficesTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsToMany('Sessions')
        	 ->setForeignKey('Offce');
        $this->belongsToMany('Patients')
        	 ->setForeignKey('Offce');
    }
}
