<?php

namespace App\Http\Controllers;

use App\Http\Requests\InfoCreateFormRequest;
use App\Http\Requests\InfoUpdateFormRequest;
use App\Http\Resources\InfoResource;
use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Info model instance
     *
     * @var \App\Models\Info
     */
    protected $info;

    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Info $info [the info model instance]
     *
     * @return void
     */
    public function __construct(Info $info)
    {
        $this->info = $info;
        //$this->authorizeResource(Info::class, 'info');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infos = $this->info->with(['category', 'user']);

        return InfoResource::collection($infos->paginate(50));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\InfoCreateFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(InfoCreateFormRequest $request)
    {
        $info = $this->info->create(array_merge(['user_id' => auth()->user()->id, 'category_id' => $request['category'], 'published' => $request['published']], $request->safe()->except('category')));

        return response()->json($info);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Info $info [the info model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Info $info)
    {
        return (new InfoResource($info->loadMissing(['category', 'user'])));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request [the current request instance]
     * @param \App\Models\Info          $info    [the info model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Info $info)
    {
        $definition = (new HtmlHelper())->purifyHTML($request->definition);

        $info->update(['user_id' => auth()->user()->id, 'category_id' => $request->category, 'term' => $request->term, 'definition' => $definition, 'published' => $request->published]);

        //$info->teams()->sync($request->selectedTeams);

        return response('Update Successful', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Info $info [the info model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Info $info)
    {
        $info->delete();
    }

    /**
     * AJAX controller method for imfo search
     *
     * @param \Illuminate\Http\Request $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function infoSearch(Request $request)
    {
        $searchquery = $request->searchquery;

        /* perform search */
        $result = $this->info->multiMatchSearch()
        ->fields(['term', 'definition'])
        ->query($searchquery)
        ->fuzziness('AUTO')
        //->postFilter('term', ['user_id' => auth()->user()->id])
        ->execute();

        $data = $result->models();

        $total = $data->count();

        return response()->json(['data' => $data, 'total' => $total]);
    }
}
