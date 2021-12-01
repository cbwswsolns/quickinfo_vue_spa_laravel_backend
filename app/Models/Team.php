<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    // MODEL METHODS

    /**
     * Create new category for the team
     *
     * @param string $name [the category name]
     *
     * @return void
     */
    public function createCategory($name)
    {
        $this->categories()->create(['name' => $name]);
    }


    // MODEL RELATIONSHIPS

    /**
     * Get the categories that belong to the team
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }


    /**
     * Get the users that belong to the team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }
}
