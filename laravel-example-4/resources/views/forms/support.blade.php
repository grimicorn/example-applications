@include('alerts.validation-errors')
<form
class="support-form row"
method="POST"
action="{{ action('SupportContactController@store') }}">
    {{ csrf_field() }}

    <div class="form-group col-sm-6">
        <label class="sr-only" for="name">Name*</label>
        <input
            class="form-control"
            name="name"
            id="name"
            type="text"
            placeholder="Name*"
            value="{{ isset($user->name) ? $user->name : '' }}">
    </div>

    <div class="form-group col-sm-6">
        <label class="sr-only" for="email">Email*</label>
        <input
            class="form-control"
            name="email"
            id="email"
            type="email"
            placeholder="Email*"
            value="{{ isset($user->email) ? $user->email : '' }}">
    </div>

    <div class="form-group col-sm-12">
        <label for="message" class="sr-only">Message</label>
        <textarea class="form-control" name="message" id="message" placeholder="Message" rows="5"></textarea>
    </div>

    <div class="col-sm-12">
        <button type="submit" class="btn btn-primary col-sm-12">Submit</button>
    </div>
</form>
