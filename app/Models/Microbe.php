<?php

namespace App\Models;

use App\Models\User;
use App\Models\Admin;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Microbe extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $appends = ['rating'];

    /**
     * Get the avg value of rating column in ratings rable for the Microbe.
     */
    public function getRatingAttribute()
    {
        return $this->ratings()->avg('rating') ? round($this->ratings()->avg('rating')) : 0;
    }

    /**
     * Get all of the comments for the Microbe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, "microbe_id", "id");
    }

    /**
     * Get all of the ratings for the Microbe
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, "microbe_id", "id");
    }

    /**
     * Get the subcategory that owns the Microbe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, "sub_category_id", "id");
    }

    /**
     * The users_collected_microbes that belong to the Microbe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users_collected_microbes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'collections', 'microbe_id', 'user_id');
    }

    /**
     * Get the author that owns the Microbe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, "admin_id", "id");
    }
}
