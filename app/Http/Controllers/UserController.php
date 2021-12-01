<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * User model instance
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\User $user [the user model instance]
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        //$this->authorizeResource(Info::class, 'info');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->with(['infos', 'teams']);

        return UserResource::collection($users->paginate(50));
    }


    public function create()
    {
        return response([
            'meta' => [
                'roles' => Role::get(['id', 'name']),
                'teams' => Team::get(['id', 'name'])
            ],
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = (new CreateNewUser())->create($request->all());

        $userTeamsRoles = [];
        foreach ($request['teamsRoles'] as $teamRoles) {
            $userTeamsRoles['team_ids'][] = $teamRoles['team']['id'];
            $userTeamsRoles['teamRoles'][] = $teamRoles['roles'];
        }

        $user->teams()->sync($userTeamsRoles['team_ids']);

        foreach ($user->teamsUsers()->get() as $teamUser) {
            $teamUser->syncRoles($userTeamsRoles['teamRoles']);
        }

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response(
            [
                'data' => new UserResource(
                    $user->load(
                        ['teamsUsers.team' => function ($query) {
                            return $query->select('id', 'name');
                        },
                        'teamsUsers.roles' => function ($query) {
                            return $query->select('id', 'name');
                        }]
                    )
                ),
                'meta' => [
                    'roles' => Role::get(['id', 'name']),
                    'teams' => Team::get(['id', 'name'])
                ]
            ]
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request [the current request instance]
     * @param \App\Models\User         $user    [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->has('current_password')) {
            (new UpdateUserPassword())->update($user, $request->all());

            return response('Password update Successful', 200);
        }

        (new UpdateUserProfileInformation())->update($user, $request->only(['name', 'email']));


        $userTeamsRoles = [];
        foreach ($request['teamsRoles'] as $teamRoles) {
            $userTeamsRoles['team_ids'][] = $teamRoles['team']['id'];
            $userTeamsRoles['teamRoles'][] = $teamRoles['roles'];
        }

        $user->teams()->sync($userTeamsRoles['team_ids']);

        foreach ($user->teamsUsers()->get() as $teamUser) {
            $teamUser->syncRoles($userTeamsRoles['teamRoles']);
        }

        return response('Profile update Successful', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user [the user model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
