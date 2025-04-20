<h1 class="text-center auth-page-title">Create Your Account</h1>

<fe-form
    action="{{ route('register') }}"
    form-id="register-form"
    class="register-form auth-form"
    :remove-submit="true"
>
    {{-- First Name --}}
    <input-textual
    name="first_name"
    value="{{ old('first_name') }}"
    validation-message="{{ $errors->first('first_name') }}"
    label="First Name"
    validation="required"
    autofocus="autofocus"></input-textual>

    {{-- Last Name --}}
    <input-textual
    name="last_name"
    value="{{ old('last_name') }}"
    validation-message="{{ $errors->first('last_name') }}"
    label="Last Name"
    validation="required"></input-textual>


    {{-- E-Mail Address --}}
    <input-textual
    type="email"
    name="email"
    value="{{ old('email') }}"
    validation-message="{{ $errors->first('email') }}"
    label="E-Mail Address"
    validation="required"></input-textual>

    {{-- Password --}}
    <input-textual
    type="password"
    name="password"
    value="{{ old('password') }}"
    validation-message="{{ $errors->first('password') }}"
    label="Password"
    validation="required"></input-textual>

    {{-- Terms of Service --}}
    <input-checkbox
    name="terms"
    value="{{ old('terms') }}"
    validation-message="{{ $errors->first('terms') }}"
    label="I accept the <a href='terms-conditions'>Terms of Service</a> and <a href='privacy-policy'> Privacy Policy"
    ></input-checkbox>

    {{-- USA Resident --}}
    <input-checkbox
    name="usa_resident"
    value="{{ old('usa_resident') }}"
    validation-message="{{ $errors->first('usa_resident') }}"
    label="I am 18 years of age or older and am a resident of the <a href='/residency'>United States of America</a>"
    ></input-checkbox>

    @include('shared.partials.recaptcha-check')
    <marketing-register-submit-tracking-button></marketing-register-submit-tracking-button>
</fe-form>

<nav class="auth-page-navigation">
    <ul class="auth-page-navigation-menu list-unstyled">
        <li>
            <a
            href='login'
            class="fc-color5">Already a User? Login Here</a>
        </li>
    </ul>
</nav>
