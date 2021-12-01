<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* CBW - Note: bypassing use of Spatie permissions 'model_has_roles' and 'model_has_permissions' tables */
        $permissions = auth()->user()->teamsUsers()->with(
            ['roles' => function ($query) {
                return $query->select('id', 'name');
            },
            'roles.permissions' => function ($query) {
                return $query->select('name')->pluck('name')->toArray();
            }]
        )->get();

        return response()->json($permissions);
    }
}
