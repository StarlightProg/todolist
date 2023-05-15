<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) 
        {         
            $newTag = Tags::firstOrCreate(
                    ['name' => $request->name],
                    ['user_id' => Auth::user()->id],
            );

            $tags = Tags::where('user_id',Auth::user()->id)->get();

            Session::put('tags', $tags);

            $tasks = Session::get('tasks');
            
            return response()->json(['tag' => $newTag]);
        }
    }

    public function filterTags(Request $request)
    {
        if ($request->ajax()) 
        {   
            if(is_null($request->tags)){
                Session::put('tasks', Tasks::all());

                $tasks = Session::get('tasks');
                $tags = Session::get('tags');
                return view('task_list',compact('tasks','tags'))->render();
            }

            $tags = $request->tags;   

            $tasks = Tasks::where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhereJsonContains('tag_id', $tag);
                }
            })->get();

            Session::put('tasks', $tasks);

            $tags = Session::get('tags');

            return view('task_list',compact('tasks','tags'))->render();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) 
        {   
            $tag = $request->tag_id;

            $tag_delete = Tags::find($tag);
            $tag_delete->delete();

            $task_json = Tasks::whereJsonContains('tag_id', $tag)->get();

            Session::put('tags', Tags::all());

            foreach ($task_json as $value) {
                $value_unjson = json_decode($value->tag_id);

                $key = array_search($tag, $value_unjson); 

                if($key !== false) {
                    unset($value_unjson[$key]);
                }

                $value_json = json_encode($value_unjson);

                Tasks::where('user_id',Auth::user()->id)->where('id', $value->id)
                    ->update(['tag_id' => $value_json]);
            }

            $tasks = Session::get('tasks');
            $tags = Session::get('tags');

            return view('task_list',compact('tasks','tags'))->render();
        }
    }
}
