<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryOtherPayment extends Model
{
    //
	protected $guarded=[];

	public function employee(){
		return $this->hasOne('App\Models\Employee','id','employee_id');
	}

}
