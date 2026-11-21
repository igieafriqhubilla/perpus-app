<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
        protected $fillable = [ 
    'nama', 
    'nis_nim', 
    'no_hp', 
    'foto', // baris baru: mengizinkan kolom foto diisi 
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
