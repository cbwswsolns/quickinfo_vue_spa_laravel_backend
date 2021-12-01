<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Permission\Traits\HasRoles;

class TeamUser extends Pivot
{
    use HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_user';


    /**
     * The guard name required for the role manager.
     *
     * @var string
     */
    protected $guard_name = 'sanctum';


    // MODEL RELATIONSHIPS

    /**
     * Get the user to which the pivot entry item belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team to which the pivot entry item belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
