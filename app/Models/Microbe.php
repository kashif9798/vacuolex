<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Microbe extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    /**
     * The users_collected_microbes that belong to the Microbe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users_collected_microbes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'collections', 'microbe_id', 'user_id');
    }
}
