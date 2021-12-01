<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateFormRequest;
use App\Http\Requests\CategoryUpdateFormRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Team;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Category model instance
     *
     * @var \App\Models\Category
     */
    protected $category;


    /**
     * Create a new controller instance.
     *
     * @param \App\Models\Info $info [the info model instance]
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
        //$this->authorizeResource(Category::class, 'category');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->with(['infos', 'team']);

        return CategoryResource::collection($categories->paginate(50));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryCreateFormRequest $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCreateFormRequest $request)
    {
        $category = $this->category->create(array_merge($request->validated(), ['team_id' => $request->team]));

        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return (new CategoryResource($category->load(['infos', 'team'])));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryCreateFormRequest $request  [the current request instance]
     * @param  \App\Models\Category                         $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateFormRequest $request, Category $category)
    {
        $category->update(array_merge($request->validated(), ['team_id' => $request->team]));

        return response($request->validated(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category [the category model instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
    }
}
