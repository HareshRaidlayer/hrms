<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CandidateQualificaiton extends Model
{
	protected $fillable = [
        'candidate_id',
        'education_level_id',
        'institution_name',
		'from_year', 
		'to_year', 
        'language_skill_id', 
        'general_skill_id', 
        'description', 
       
    ];

    public function candidate(){
		return $this->hasOne('App\Models\JobCandidate','id','candidate_id	');
	}

	public function EducationLevel(){
		return $this->hasOne('App\Models\QualificationEducationLevel','id','education_level_id');
	}
	public function LanguageSkill(){
		return $this->hasOne('App\Models\QualificationLanguage','id','language_skill_id');
	}
	public function GeneralSkill(){
		return $this->hasOne('App\Models\QualificationSkill','id','general_skill_id');
	}

	public function setFromYearAttribute($value)
	{
		$this->attributes['from_year'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getFromYearAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}

	public function setToYearAttribute($value)
	{
		$this->attributes['to_year'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getToYearAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}
}
