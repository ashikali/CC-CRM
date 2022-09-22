<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Question;


class CSATAnswer extends Model {

    use HasFactory;

    public $table = 'answers';


    public function entry(){

           return $this->belongsTo(Entry::class);

    }
    public function question(){

           return $this->belongsTo(Question::class);

    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }



}

