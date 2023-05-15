<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
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
            Tasks::firstOrCreate(
                    ['task_text' => $request->text],
                    ['user_id' => Auth::user()->id],
            );

            $tasks = Tasks::where('user_id',Auth::user()->id)->get();

            Session::put('tasks', $tasks);

            $tags = Session::get('tags');
            
            return view('task_list',compact('tasks','tags'))->render();
        }
    }

    public function addImage(Request $request)
    {
        if ($request->ajax()) 
        {         
            $validator = Validator::make($request->all(), [
                'image_file' => 'image|max:16384',
            ]);

            if($validator->fails()) {
                return response()->json(['error' => 'Invalid file format'], 422);
            }

            if($request->hasFile('image_file')) {
                $file = $request->file('image_file');

                $name = md5(uniqid()).'.'.$file->getClientOriginalExtension();
                Storage::putFileAs('/public',$file,$name);

                Tasks::where('user_id',Auth::user()->id)->where('id', (int) $request->task_id)->update(['image' => $name]);
            
                return response()->json(['image_src' => $name]);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'No file was uploaded.']);
    }

    public function deleteImage(Request $request)
    {
        if ($request->ajax()) 
        {        
                $image_name = Tasks::where('user_id',Auth::user()->id)->where('id', (int) $request->task_id)->get()[0]['image'];
                $filePath = 'public/' . $image_name;

                Storage::delete($filePath);

                Tasks::where('user_id',Auth::user()->id)->where('id', (int) $request->task_id)->update(['image' => null]);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->ajax()) 
        {         
            Tasks::where('user_id',Auth::user()->id)->where('task_text', $request->name)
                ->update(['tag_id' => json_encode($request->tags)]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) 
        {         
            $task = Tasks::find($request->task_id);
            $task->delete();

            Session::put('tasks', Tasks::all());

            $tasks = Session::get('tasks');
            $tags = Session::get('tags');

            return view('task_list',compact('tasks','tags'))->render();
        }
    }
}
