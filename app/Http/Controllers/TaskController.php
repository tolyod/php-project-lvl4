<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Task, TaskStatus, User, Label};
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->with('creator', 'status', 'assignee', 'status', 'labels')
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id')
            ])
            ->simplePaginate(12)->withQueryString();
        $taskStatuses = TaskStatus::all()
            ->pluck('name', 'id');

        $users = User::all()->pluck('name', 'id');

        $creators = $users->all();
        $assigns = $users->all();
        $filter = $request->input('filter');

        return view('task.index', compact('tasks', 'taskStatuses', 'users', 'creators', 'assigns', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $task = new Task();

        $taskStatuses = TaskStatus::all()->pluck('name', 'id');
        $users = User::all()->pluck('name', 'id');
        $labels = Label::all()->pluck('name', 'id');

        $view = view('task.create', compact('task', 'taskStatuses', 'users', 'labels'));

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
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
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
        $task->labels()->attach($request->input('labels'));
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.task_create_success'));

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Task $task)
    {
        $labels = Label::all()->pluck('name', 'id');
        $taskStatuses = TaskStatus::all()->pluck('name', 'id');
        $users = User::all()->pluck('name', 'id');

        return view('task.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable|string',
            'status_id' => 'required|integer',
            'assigned_to_id' => 'nullable|integer',
            'labels.*' => 'nullable|integer'
        ]);

        $task->name = $request->input('name');
        $task->description = $request->input('description');

        $task->status()->associate($request->input('status_id'));
        $task->assignee()->associate($request->input('assigned_to_id'));
        $labels = collect($request->input('labels'))->filter()->all();
        $task->saveOrFail();

        $task->labels()->sync($labels);
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.task_modify_success'));

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroy(Request $request, Task $task)
    {
        if ($request->user()->can('delete', $task)) {
            $task->delete();
            /* @phpstan-ignore-next-line */
            flash()->success(__('flash.task_delete_success'));

            return redirect()->route('tasks.index');
        }
        abort(403);
    }
}
