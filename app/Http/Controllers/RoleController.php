<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleCreateFormRequest;
use App\Http\Requests\RoleUpdateFormRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Role model instance
     *
     * @var \App\Models\Role
     */
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Role $role [the role model instance]
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
        //$this->authorizeResource(Info::class, 'info');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->role->select('id', 'name')->with(['permissions'  => function ($query) {
                return $query->select('id', 'name');
        }]);

        return RoleResource::collection($roles->paginate(50));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\RoleCreateFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateFormRequest $request)
    {
        $role = $this->role->create($request->validated());

        $role->givePermissionTo(array_column($request['permissions'], 'name'));

        return response()->json($role);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Role $role [the role model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return (new RoleResource($role->loadMissing([
            'permissions'  => function ($query) {
                return $query->select('id', 'name');
            }
        ])));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\RoleUpdateFormRequest $request [the current request instance]
     * @param \App\Models\Role                       $role    [the role model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateFormRequest $request, Role $role)
    {
        $role->update($request->validated());

        $role->permissions()->sync(array_column($request['permissions'],
            'id'));

        $role->teams()->sync($request['teams']);

        return response($request->validated(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role [the role model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
    }
}
