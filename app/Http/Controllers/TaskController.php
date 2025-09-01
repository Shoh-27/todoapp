<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::where('user_id' , auth()->id())->latest()->get();
        return view('tasks.index', ['tasks' => $tasks]);
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
        $request->validate([
            'title' => 'required|max:255',
            'deadline' => 'nullable|date',
        ]);

        $request->user()->tasks()->create([
            'title' => $request->title,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('tasks.index');
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
    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if ($request->user()->id !== $task->user_id){
            abort(403);
        }
        if ($request->has('title') || $request->has('deadline')) {
           $request->validate([
               'title' => 'required|max:255',
               'deadline' => 'nullable|date',
           ]) ;
           $task->update([
               'title' => $request->title,
               'deadline' => $request->deadline ,
           ]);
        }else {
            $task->update([
                'is_completed' => !$task->is_completed,
            ]);
        }


        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }
}
