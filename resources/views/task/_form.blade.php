        <div class="form-group">
            {!! Form::label('name', __('layout.common.label.name')) !!}
            {!! Form::text('name', $task->name ?? null, ['class' => ["form-control", $errors->has('name') ? 'is-invalid' : '']]) !!}
            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
        <div class="form-group">
            {!! Form::label('description', __('layout.common.label.description')) !!}
            {!! Form::textarea('description', $task->description ?? null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('assigned_to_id', __('layout.task.assignee')) !!}
            {!! Form::select('assigned_to_id', $users, $task->assigned_to_id ?? null, ['class' => 'form-control', 'placeholder' => __('layout.selected_default')]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('status_id', __('layout.task_status')) !!}
            {!! Form::select(
                'status_id',
                $taskStatuses,
                $task->status_id ?? null,
                ['class' => ["form-control", $errors->has('status_id') ? 'is-invalid' : ''], 'placeholder' => __('layout.selected_default')])
            !!}
            @if ($errors->has('status_id'))
                <div class="invalid-feedback">{{ $errors->first('status_id') }}</div>
            @endif
        </div>
        <div class="form-group">
            {!! Form::label('labels', __('layout.labels')) !!}
            {!! Form::select('labels[]', $labels, null, ['class' => 'form-control', 'multiple' => true, 'placeholder' => '']) !!}
        </div>
        {!! Form::token() !!}

