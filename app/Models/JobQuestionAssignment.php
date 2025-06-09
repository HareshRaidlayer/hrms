<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQuestionAssignment extends Model
{
    use HasFactory;
    
    protected $fillable = ['job_id', 'question_id'];

    public function question()
    {
        return $this->belongsTo(JobQuestion::class, 'question_id');
    }
}
