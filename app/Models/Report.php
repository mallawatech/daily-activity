<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Report extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi penamaan
    protected $table = 'reports';

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'activity_log',
        'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function dataovertime()
    {
        return $this->belongsTo(Overtime::class,'id', 'report_id');
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    
}
