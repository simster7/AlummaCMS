<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SessionstatsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsToMany('Sessions')
        	 ->setForeignKey('Status');
    }
}