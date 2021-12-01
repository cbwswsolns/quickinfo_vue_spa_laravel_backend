<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamCreateFormRequest;
use App\Http\Requests\TeamUpdateFormRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Team model instance
     *
     * @var \App\Models\Team
     */
    protected $team;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Team $team [the team model instance]
     *
     * @return void
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
        //$this->authorizeResource(Info::class, 'info');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = $this->team->with(['categories', 'users']);

        return TeamResource::collection($teams->paginate(50));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\TeamCreateFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TeamCreateFormRequest $request)
    {
        $team = $this->team->create($request->validated());

        return response()->json($team);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Team $team [the team model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return new TeamResource($team->load(
            [
                'users.teamsUsers' => function ($query) use ($team) {
                    /* CBW - Note: constrain nested eager load of user roles
                             to current team */
                    $query->where('team_id', $team->id);
                }
            ]
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\TeamUpdateFormRequest $request [the current request instance]
     * @param \App\Models\Team                         $team    [the team model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TeamUpdateFormRequest $request, Team $team)
    {
        $team->update($request->validated());

        return response($request->validated(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Team $team [the team model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
    }
}
