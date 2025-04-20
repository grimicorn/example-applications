<h3>4. Set a Default Task Due Date</h3>
<default-task-due-date
crsf-token="{{ csrf_token() }}"
action="{{ action('DefaultTaskDueDateController@update') }}"
:enabled="{{ $user->default_due_date_enabled ? 'true' : 'false' }}"
:offset="{{ $user->default_due_date_offset }}"
:hour="{{ date('h', strtotime($user->default_due_date_time)) }}"
:minute="{{ date('i', strtotime($user->default_due_date_time)) }}"
period="{{ date('a', strtotime($user->default_due_date_time)) }}"></default-task-due-date>
