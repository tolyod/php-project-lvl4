<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $labels = Label::paginate();

        return view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $label = new Label();

        return view('label.create', compact('label'));
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
            ['name' => 'required|unique:labels|max:255']
        );

        $label = new Label();
        $label->name = $request->input('name');
        $label->description = $request->input('description');
        $label->saveOrFail();
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.label_create_success'));

        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Label $label)
    {
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Label $label)
    {
        $this->validate(
            $request,
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('labels')->ignore($label->id)
                ]
            ]
        );

        $label->name = $request->input('name');
        $label->description = $request->input('description');
        $label->saveOrFail();
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.label_modify_success'));

        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Label $label)
    {
        if ($label->tasks()->exists()) {
            /** @var string $errorMessage */
            $errorMessage = __('flash.label_delete_task_exists_error');
            flash()->error($errorMessage);
            return back();
        }
        $label->delete();
        /* @phpstan-ignore-next-line */
        flash()->success(__('flash.label_delete_success'));

        return redirect()->route('labels.index');
    }
}
