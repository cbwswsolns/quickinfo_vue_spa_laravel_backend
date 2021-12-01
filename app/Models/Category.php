<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id', 'name'
    ];


    // MODEL RELATIONSHIPS

    /**
     * Get the info items associated with the category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function infos()
    {
        return $this->hasMany(Info::class);
    }

    /**
     * Get the team to which this category belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
