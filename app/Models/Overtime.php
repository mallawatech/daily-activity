<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'day', 'start_time', 'end_time', 'total_overtime', 'activity_log', 'photos'
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}