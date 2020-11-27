<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Get the answer.
     *
     * @param  string  $value
     * @return string
     */
    public function getAnswersAttribute($value)
    {
        return json_decode($value, true);
    }

}
