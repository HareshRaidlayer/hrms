<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'question_type', 'options', 'status', 'created_by'];

    protected $casts = [
        'options' => 'array',
        'status' => 'boolean'
    ];

    public function assignments()
    {
        return $this->hasMany(JobQuestionAssignment::class, 'question_id');
    }
}
