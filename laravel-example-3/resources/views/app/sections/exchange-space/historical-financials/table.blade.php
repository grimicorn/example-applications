<table class="financial-table mb2">
    <thead>
        <tr>
            <td class="historical-financial-table-label">
                <strong>{{ $rows['income_statement']['label'] }}</strong>
            </td>
            @foreach($rows['income_statement']['values'] as $value)
                <td class="historical-financial-table-label year">{{ $value }}</td>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($rows['revenues'] as $revenue)
            <tr>
                <td>{{ $revenue['label'] }}</td>
                @foreach($revenue['values'] as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="table-section-title">
            <td>{{ $rows['total_revenue']['label'] }}</td>
            @foreach($rows['total_revenue']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['total_cost_of_goods_sold']['label'] }}</td>
            @foreach($rows['total_cost_of_goods_sold']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['employee_wage_and_benefits']['label'] }}</td>
            @foreach($rows['employee_wage_and_benefits']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['transportation']['label'] }}</td>
            @foreach($rows['transportation']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['contractors']['label'] }}</td>
            @foreach($rows['contractors']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['education_and_training']['label'] }}</td>
            @foreach($rows['education_and_training']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['meals_and_entertainment']['label'] }}</td>
            @foreach($rows['meals_and_entertainment']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['travel_expenses']['label'] }}</td>
            @foreach($rows['travel_expenses']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['office_expenses']['label'] }}</td>
            @foreach($rows['office_expenses']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['professional_services']['label'] }}</td>
            @foreach($rows['professional_services']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['utilities']['label'] }}</td>
            @foreach($rows['utilities']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['rent_or_lease_expense']['label'] }}</td>
            @foreach($rows['rent_or_lease_expense']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['depreciation']['label'] }}</td>
            @foreach($rows['depreciation']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['amortization']['label'] }}</td>
            @foreach($rows['amortization']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['interest_expense']['label'] }}</td>
            @foreach($rows['interest_expense']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_general_operating_expenses']['label'] }}</td>
            @foreach($rows['other_general_operating_expenses']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title">
            <td>{{ $rows['total_expenses']['label'] }}</td>
            @foreach($rows['total_expenses']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['earnings_before_income_taxes']['label'] }}</td>
            @foreach($rows['earnings_before_income_taxes']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['income_tax']['label'] }}</td>
            @foreach($rows['income_tax']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['net_earnings']['label'] }}</td>
            @foreach($rows['net_earnings']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<table class="financial-table mb2">
    <thead>
        <tr>
            <td class="historical-financial-table-label">
                <strong>{{ $rows['balance_sheet']['label'] }}</strong>
            </td>
            @foreach($rows['balance_sheet']['values'] as $value)
                <td class="historical-financial-table-label year">{{ $value }}</td>
            @endforeach
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>{{ $rows['cash_and_cash_equivalents']['label'] }}</td>
            @foreach($rows['cash_and_cash_equivalents']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['investments']['label'] }}</td>
            @foreach($rows['investments']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['accounts_receivable']['label'] }}</td>
            @foreach($rows['accounts_receivable']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['inventory']['label'] }}</td>
            @foreach($rows['inventory']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['prepaid_expenses']['label'] }}</td>
            @foreach($rows['prepaid_expenses']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_current_assets']['label'] }}</td>
            @foreach($rows['other_current_assets']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title">
            <td>{{ $rows['total_current_assets']['label'] }}</td>
            @foreach($rows['total_current_assets']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['goodwill']['label'] }}</td>
            @foreach($rows['goodwill']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_intangible_assets']['label'] }}</td>
            @foreach($rows['other_intangible_assets']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['property_plant_and_equipment']['label'] }}</td>
            @foreach($rows['property_plant_and_equipment']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_assets']['label'] }}</td>
            @foreach($rows['other_assets']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['total_assets']['label'] }}</td>
            @foreach($rows['total_assets']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['accounts_payable']['label'] }}</td>
            @foreach($rows['accounts_payable']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['short_term_and_current_debt']['label'] }}</td>
            @foreach($rows['short_term_and_current_debt']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['accrued_liabilities']['label'] }}</td>
            @foreach($rows['accrued_liabilities']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['unearned_revenues']['label'] }}</td>
            @foreach($rows['unearned_revenues']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_current_liabilities']['label'] }}</td>
            @foreach($rows['other_current_liabilities']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title">
            <td>{{ $rows['total_current_liabilities']['label'] }}</td>
            @foreach($rows['total_current_liabilities']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['long_term_debt_less_current_portion']['label'] }}</td>
            @foreach($rows['long_term_debt_less_current_portion']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['deferred_income_taxes']['label'] }}</td>
            @foreach($rows['deferred_income_taxes']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['deferred_rent_or_lease_expense']['label'] }}</td>
            @foreach($rows['deferred_rent_or_lease_expense']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_liabilities']['label'] }}</td>
            @foreach($rows['other_liabilities']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['total_liabilities']['label'] }}</td>
            @foreach($rows['total_liabilities']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['paid_in_capital']['label'] }}</td>
            @foreach($rows['paid_in_capital']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['retained_earnings']['label'] }}</td>
            @foreach($rows['retained_earnings']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['other_equity_accounts']['label'] }}</td>
            @foreach($rows['other_equity_accounts']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['total_shareholders_equity']['label'] }}</td>
            @foreach($rows['total_shareholders_equity']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['total_liabilities_and_shareholders_equity']['label'] }}</td>
            @foreach($rows['total_liabilities_and_shareholders_equity']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

    </tbody>
</table>