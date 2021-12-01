<?php

namespace App\Models;

use App\Models\Info;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**§
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // MODEL METHODS

    /**
     * Attach an info record created by the user
     *
     * @param \App\Models\Info $info [the info model instance]
     *åå
     * @return bool
     */
    public function attachInfoRecord(Info $info)
    {
        return $this->infos()->save($info);
    }


    // MODEL RELATIONSHIPS

    /**
     * Get the info items belonging to the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function infos()
    {
        return $this->hasMany(Info::class);
    }


    /**
     * Get the teams that the user belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user');
    }


    public function teamsUsers()
    {
        return $this->hasMany(TeamUser::class)->orderBy('team_id');
    }
}
