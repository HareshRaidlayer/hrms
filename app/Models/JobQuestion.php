<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'question_type','interview_id', 'options', 'status', 'created_by'];

    protected $casts = [
        'options' => 'array',
        'status' => 'boolean'
    ];

    // public function jobQuestions()
    // {
    //     return $this->hasMany(JobQuestion::class, 'job_id', 'job_id');
    // }
    public function jobInterviw()
    {
        return $this->hasMany(JobQuestion::class, 'interview_id');
    }
    public function questionAnswer()
    {
        return $this->hasOne(JobQuestionAnswer::class, 'question_id');
    }
}
