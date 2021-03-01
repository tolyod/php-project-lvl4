<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $taskStatuses = TaskStatus::get();

        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $taskStatus = new TaskStatus();

        return view('task_status.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            ['name' => 'required|unique:task_statuses']
        );
        $taskStatus = new TaskStatus();

        $taskStatus->name = $request->input('name');
        $taskStatus->saveOrFail();
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.status_create_success'));
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $this->validate(
            $request,
            [ 'name' => 'required|unique:task_statuses']
        );
        $taskStatus->name = $request->input('name');
        $taskStatus->saveOrFail();
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.status_modify_success'));

        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if (filled($taskStatus->tasks->first())) {
            flash()->error(__('flash.status_delete_task_exists_error'));
            return back();
        }
        $taskStatus->delete();
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.status_delete_success'));

        return back();
    }
}
