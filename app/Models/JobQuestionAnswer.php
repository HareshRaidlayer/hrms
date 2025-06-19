<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['candidate_id','question_id', 'answer'];

    public function question()
    {
        return $this->belongsTo(JobQuestion::class, 'question_id');
    }

}
