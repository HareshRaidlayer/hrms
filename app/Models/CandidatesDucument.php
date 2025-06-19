<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CandidatesDucument extends Model
{
    
    protected $fillable = [
		'document_title','document_type_id','candidate_id','expiry_date','document_file','description',
		'is_notify'
	];

	public function candidate(){
		return $this->hasOne('App\Models\JobCandidate','id','candidate_id');
	}

	public function DocumentType(){
		return $this->hasOne('App\Models\DocumentType','id','document_type_id');
	}

	public function setExpiryDateAttribute($value)
	{
		$this->attributes['expiry_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getExpiryDateAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}
}
