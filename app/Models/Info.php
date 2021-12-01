<?php

namespace App\Models;

use ElasticScoutDriverPlus\QueryDsl;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Info extends Model
{
    use Searchable;
    use QueryDsl;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'term', 'definition', 'published'
    ];


    // MODEL RELATIONSHIPS

    /**
     * Get the category to which the info item belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that owns this info item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
