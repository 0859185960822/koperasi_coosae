<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'last_followup_at' => 'datetime',
    ];

    public function marketing()
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function followups()
    {
        return $this->hasMany(Followup::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
