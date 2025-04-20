<app-profile-desired-purchase-criteria>
        <app-profile-edit-business-types
        :values="{{ json_encode($currentUser->desiredPurchaseCriteria->business_categories) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.business_categories') }}"></app-profile-edit-business-types>

        {{--Locations--}}
        <input-textual
        value="{{ old(
        'desiredPurchaseCriteria.locations',
        $currentUser->desiredPurchaseCriteria->locations
        ) }}"
        name="desiredPurchaseCriteria[locations]"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.locations') }}"
        label="Location(s)"
        :input-readonly="false"
        wrap-class="clear"
        ></input-textual>

        {{-- Asking Price Minimum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[asking_price_minimum]"
        value="{{ old(
            'desiredPurchaseCriteria.asking_price_minimum',
            $currentUser->desiredPurchaseCriteria->asking_price_minimum
        ) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.asking_price_minimum') }}"
        label="Asking Price Minimum"
        wrap-class="input-half is-left"></input-textual>

        {{-- Asking Price Maximum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[asking_price_maximum]"
        value="{{ old(
            'desiredPurchaseCriteria.asking_price_maximum',
            $currentUser->desiredPurchaseCriteria->asking_price_maximum
        ) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.asking_price_maximum') }}"
        label="Asking Price Maximum"
        wrap-class="input-half is-right"></input-textual>

        {{-- Revenue Price Minimum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[revenue_minimum]"
        value="{{ old(
            'desiredPurchaseCriteria.revenue_minimum',
            $currentUser->desiredPurchaseCriteria->revenue_minimum
        ) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.revenue_minimum') }}"
        label="Revenue Minimum"
        wrap-class="input-half is-left"></input-textual>

        {{-- Revenue Price Maximum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[revenue_maximum]"
        value="{{ old(
            'desiredPurchaseCriteria.revenue_maximum',
            $currentUser->desiredPurchaseCriteria->revenue_maximum
        ) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.revenue_maximum') }}"
        label="Revenue Maximum"
        wrap-class="input-half is-right"></input-textual>

        {{-- EBITDA Price Minimum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[ebitda_minimum]"
        value="{{ old(
            'desiredPurchaseCriteria.ebitda_minimum',
            $currentUser->desiredPurchaseCriteria->ebitda_minimum
        ) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.ebitda_minimum') }}"
        label="EBITDA Minimum"
        wrap-class="input-half is-left"></input-textual>

        {{-- EBITDA Price Maximum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[ebitda_maximum]"
        value="{{ old(
            'desiredPurchaseCriteria.ebitda_maximum',
            $currentUser->desiredPurchaseCriteria->ebitda_maximum
        ) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.ebitda_maximum') }}"
        label="EBITDA Maximum"
        wrap-class="input-half is-right"></input-textual>

        {{-- Pre-Tax Income Price Minimum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[pre_tax_income_minimum]"
        value="{{ old('desiredPurchaseCriteria.pre_tax_income_minimum', $currentUser->desiredPurchaseCriteria->pre_tax_income_minimum) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.pre_tax_income_minimum') }}"
        label="Pre-Tax Income Minimum"
        wrap-class="input-half is-left"></input-textual>

        {{-- Pre-Tax Income Price Maximum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[pre_tax_income_maximum]"
        value="{{ old('desiredPurchaseCriteria.pre_tax_income_maximum', $currentUser->desiredPurchaseCriteria->pre_tax_income_maximum) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.pre_tax_income_maximum') }}"
        label="Pre-Tax Income Maximum"
        wrap-class="input-half is-right"></input-textual>

        {{-- Discretionary Cash Flow Price Minimum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[discretionary_cash_flow_minimum]"
        value="{{ old('desiredPurchaseCriteria.discretionary_cash_flow_minimum', $currentUser->desiredPurchaseCriteria->discretionary_cash_flow_minimum) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.discretionary_cash_flow_minimum') }}"
        label="Discretionary Cash Flow Minimum"
        wrap-class="input-half is-left"></input-textual>

        {{-- Discretionary Cash Flow Price Maximum --}}
        <input-textual
        type="price"
        name="desiredPurchaseCriteria[discretionary_cash_flow_maximum]"
        value="{{ old('desiredPurchaseCriteria.discretionary_cash_flow_maximum', $currentUser->desiredPurchaseCriteria->discretionary_cash_flow_maximum) }}"
        validation-message="{{ $errors->first('desiredPurchaseCriteria.discretionary_cash_flow_maximum') }}"
        label="Discretionary Cash Flow Maximum"
        wrap-class="input-half is-right"></input-textual>
</app-profile-desired-purchase-criteria>
