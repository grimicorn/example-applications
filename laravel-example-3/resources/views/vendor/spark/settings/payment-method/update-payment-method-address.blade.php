<!-- Name on Card -->
<input-textual
name="name"
label="Name on Card"
v-model="cardForm.name"
:value="cardForm.name"></input-textual>

<!-- Address -->
<input-textual
name="address"
label="Address"
v-model="form.address"
:value="form.address"
validation="required"
:validation-message="cardForm.errors.get('address')"></input-textual>

<!-- Address Line 2 -->
<input-textual
name="address_line_2"
label="Address Line 2"
v-model="form.address_line_2"
:value="form.address_line_2"
:validation-message="cardForm.errors.get('address_line_2')"></input-textual>

<!-- City -->
<input-textual
name="city"
label="City"
v-model="form.city"
:value="form.city"
validation="required"
:validation-message="cardForm.errors.get('city')"></input-textual>

<!-- State & ZIP Code -->
<input-select
name="state"
placeholder="State"
:allow-placeholder-select="true"
:options="{{ json_encode($statesForSelect) }}"
label="State"
v-model="form.state"
:value="form.state"
validation="required"
:validation-message="cardForm.errors.get('state')"></input-select>

<!-- Zip Code -->
<input-textual
name="zip"
label="Zip Code"
v-model="form.zip"
:value="form.zip"
validation="required"
:validation-message="cardForm.errors.get('zip')"></input-textual>

<!-- Country -->
<input
type="hidden"
value="US"
v-model="form.country">
