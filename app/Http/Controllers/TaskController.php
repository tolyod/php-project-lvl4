<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\User;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        $taskStatuses = TaskStatus::all()
            ->mapWithKeys(fn(TaskStatus $taskStatus) => [
                $taskStatus->id => $taskStatus->name
            ])
            ->prepend(__('layout.task.form.choose_status'), '');

        $users = User::all()
            ->mapWithKeys(fn(User $user) => [
                $user->id => $user->name
            ]);

        $creators = $users->prepend(__('layout.task.creator'), '');
        $assigns = $users->prepend(__('layout.task.assignee'), '');

        return view('task.index', compact('tasks', 'taskStatuses', 'users', 'creators', 'assigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task();

        $taskStatuses = TaskStatus::all()
            ->mapWithKeys(fn(TaskStatus $taskStatus) => [
                $taskStatus->id => $taskStatus->name
            ]);
        $users = User::all()
            ->mapWithKeys(fn(User $user) => [
                $user->id => $user->name
            ]);

        $view = view('task.create', compact('task', 'taskStatuses', 'users'));

        if ($taskStatuses->isEmpty()) {
            return $view->withErrors([
                'empty_statuses' => __('layout.empty_statuses')
            ]);
        }

        return $view;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable|string',
            'status_id' => 'required|integer',
            'assigned_to_id' => 'nullable|integer'
        ]);

        $task = new Task();

        $task->name = $request->input('name');
        $task->description = $request->input('description');

        $task->status()->associate($request->input('status_id'));
        $task->creator()->associate(auth()->user());
        $task->assignee()->associate($request->input('assigned_to_id'));

        $task->saveOrFail();

        flash()->success(__('layout.flash.success'));

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $taskStatuses = TaskStatus::all()
            ->mapWithKeys(fn(TaskStatus $taskStatus) => [
                $taskStatus->id => $taskStatus->name
            ]);
        $users = User::all()
            ->mapWithKeys(fn(User $user) => [
                $user->id => $user->name
            ]);

        return view('task.edit', compact('task', 'taskStatuses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable|string',
            'status_id' => 'required|integer',
            'assigned_to_id' => 'nullable|integer'
        ]);

        $task->name = $request->input('name');
        $task->description = $request->input('description');

        $task->status()->associate($request->input('status_id'));
        $task->assignee()->associate($request->input('assigned_to_id'));

        $task->saveOrFail();

        flash()->success(__('layout.flash.success'));

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
    {
        if ($request->user()->can('delete', $task)) {
            $task->delete();
            flash()->success(__('layout.flash.success'));

            return redirect()->route('tasks.index');
        }
        abort('403');
    }
}
