<table class="financial-table mb2">
    <thead>
        <tr>
            <th class="historical-financial-table-label">
                <strong>{{ $rows['adjusted_historical_financial_summary']['label'] }}</strong>
            </th>

            @foreach($rows['adjusted_historical_financial_summary']['values'] as $value)
                <th class="historical-financial-table-label year">
                    {{ $value }}
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        <tr class="table-section-title">
            <td>{{ $rows['total_revenue']['label'] }}</td>
            @foreach($rows['total_revenue']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>
        <tr class="text-italic">
            <td class="label-indent">{{ $rows['revenues_sum_of_growth']['label'] }}</td>
            @foreach($rows['revenues_sum_of_growth']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['cost_of_goods_sold_1']['label'] }}</td>
            @foreach($rows['cost_of_goods_sold_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['s_g_a_expenses_1']['label'] }}</td>
            @foreach($rows['s_g_a_expenses_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['employee_related_expenses_1']['label'] }}</td>
            @foreach($rows['employee_related_expenses_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['office_related_expenses_1']['label'] }}</td>
            @foreach($rows['office_related_expenses_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title">
            <td>{{ $rows['total_operating_expenses']['label'] }}</td>
            @foreach($rows['total_operating_expenses']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['non_recurring_expense_adjustments']['label'] }}</td>
            @foreach($rows['non_recurring_expense_adjustments']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['adjusted_ebitda_1']['label'] }}</td>

            @foreach($rows['adjusted_ebitda_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="text-italic">
            <td class="label-indent">{{ $rows['adjusted_ebitda_of_margin']['label'] }}</td>
            @foreach($rows['adjusted_ebitda_of_margin']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['interest']['label'] }}</td>
            @foreach($rows['interest']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['amortization_1']['label'] }}</td>
            @foreach($rows['amortization_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['depreciation_1']['label'] }}</td>
            @foreach($rows['depreciation_1']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['adjusted_pre_tax_earnings']['label'] }}</td>
            @foreach($rows['adjusted_pre_tax_earnings']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="text-italic">
            <td class="label-indent">{{ $rows['adjusted_pre_tax_earnings_of_margin']['label'] }}</td>
            @foreach($rows['adjusted_pre_tax_earnings_of_margin']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>
        <tr>
            <td>
                <strong class="text-underline">{{ $rows['free_cash_flow_reconciliation']['label'] }}</strong>
            </td>
        </tr>

        <tr>
            <td>{{ $rows['adjusted_ebitda_2']['label'] }}</td>
            @foreach($rows['adjusted_ebitda_2']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['less_capital_expenditures']['label'] }}</td>
            @foreach($rows['less_capital_expenditures']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['less_change_in_working_capital']['label'] }}</td>
            @foreach($rows['less_change_in_working_capital']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['plus_stock_based_compensation']['label'] }}</td>
            @foreach($rows['plus_stock_based_compensation']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['discretionary_cash_flow']['label'] }}</td>
            @foreach($rows['discretionary_cash_flow']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="text-italic">
            <td class="label-indent">{{ $rows['discretionary_cash_flow_of_revenue']['label'] }}</td>
            @foreach($rows['discretionary_cash_flow_of_revenue']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>
        <tr class="text-italic">
            <td class="label-indent">{{ $rows['discretionary_cash_flow_of_ebitda']['label'] }}</td>
            @foreach($rows['discretionary_cash_flow_of_ebitda']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['less_interest']['label'] }}</td>
            @foreach($rows['less_interest']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr>
            <td>{{ $rows['less_cash_taxes']['label'] }}</td>
            @foreach($rows['less_cash_taxes']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="table-section-title bg-table-highlight-row">
            <td>{{ $rows['net_cash_flow']['label'] }}</td>
            @foreach($rows['net_cash_flow']['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="text-italic">
            <td class="label-indent">{{ $rows['net_cash_flow_of_revenue']['label'] }}</td>
            @foreach($rows['net_cash_flow_of_revenue']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>

        <tr class="text-italic">
            <td class="label-indent">{{ $rows['net_cash_flow_of_ebitda']['label'] }}</td>
            @foreach($rows['net_cash_flow_of_ebitda']['values'] as $value)
                <td class="text-right">{{ $value }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<table  class="financial-table mb2">
    <thead>
        <tr>
            <th class="historical-financial-table-label">
                <strong>{{ $rows['historical_revenue_trends']['label'] }}</strong>
            </th>
            @foreach($rows['historical_revenue_trends']['values'] as $value)
                <th class="historical-financial-table-label year">{{ $value }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($rows['revenues'] as $revenue)
            <tr>
                <td>{{ $revenue['line']['label'] }}</td>
                @foreach($revenue['line']['values'] as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
            <tr class="text-italic">
                <td class="label-indent">{{ $revenue['of_growth']['label'] }}</td>
                @foreach($revenue['of_growth']['values'] as $value)
                    <td class="text-right">{{ $value }}</td>
                @endforeach
            </tr>
            <tr class="text-italic">
                <td class="label-indent">{{ $revenue['of_total_revenue']['label'] }}</td>
                @foreach($revenue['of_total_revenue']['values'] as $value)
                    <td class="text-right">{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<table class="financial-table mb2">
    <thead>
        <tr>
            <th class="historical-financial-table-label">
                <strong>{{ $rows['historical_expense_trends']['label'] }}</strong>
            </th>
            @foreach($rows['historical_expense_trends']['values'] as $value)
                <th class="historical-financial-table-label year">{{ $value }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
    <tr class="bg-table-highlight-row">
        <td>{{ $rows['cost_of_goods_sold_2']['label'] }}</td>
        @foreach($rows['cost_of_goods_sold_2']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>
    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['cost_goods_sold_of_revenue']['label'] }}</td>
        @foreach($rows['cost_goods_sold_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['cost_goods_sold_of_growth']['label'] }}</td>
        @foreach($rows['cost_goods_sold_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['transportation']['label'] }}</td>
        @foreach($rows['transportation']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['transportation_of_revenue']['label'] }}</td>
        @foreach($rows['transportation_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['transportation_of_growth']['label'] }}</td>
        @foreach($rows['transportation_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['meals_and_entertainment']['label'] }}</td>
        @foreach($rows['meals_and_entertainment']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['meals_entertainment_of_revenue']['label'] }}</td>
        @foreach($rows['meals_entertainment_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['meals_entertainment_of_growth']['label'] }}</td>
        @foreach($rows['meals_entertainment_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['travel_expenses']['label'] }}</td>
        @foreach($rows['travel_expenses']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['travel_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['travel_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['travel_expenses_of_growth']['label'] }}</td>
        @foreach($rows['travel_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['professional_services']['label'] }}</td>
        @foreach($rows['professional_services']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['professional_services_of_revenue']['label'] }}</td>
        @foreach($rows['professional_services_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['professional_services_of_growth']['label'] }}</td>
        @foreach($rows['professional_services_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['other_general_operating_expenses']['label'] }}</td>
        @foreach($rows['other_general_operating_expenses']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['general_operational_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['general_operational_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['general_operational_expenses_of_growth']['label'] }}</td>
        @foreach($rows['general_operational_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row">
        <td>{{ $rows['s_g_a_expenses_2']['label'] }}</td>
        @foreach($rows['s_g_a_expenses_2']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['s_g_a_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['s_g_a_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['s_g_a_expenses_of_growth']['label'] }}</td>
        @foreach($rows['s_g_a_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['employee_wages_benefits']['label'] }}</td>
        @foreach($rows['employee_wages_benefits']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['employee_wages_benefits_of_revenue']['label'] }}</td>
        @foreach($rows['employee_wages_benefits_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['employee_wages_benefits_of_growth']['label'] }}</td>
        @foreach($rows['employee_wages_benefits_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['contractors']['label'] }}</td>
        @foreach($rows['contractors']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['contractor_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['contractor_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['contractor_expenses_of_growth']['label'] }}</td>
        @foreach($rows['contractor_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['education_training']['label'] }}</td>
        @foreach($rows['education_training']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['employee_education_training_of_revenue']['label'] }}</td>
        @foreach($rows['employee_education_training_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['employee_education_training_of_growth']['label'] }}</td>
        @foreach($rows['employee_education_training_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row">
        <td>{{ $rows['employee_related_expenses_2']['label'] }}</td>
        @foreach($rows['employee_related_expenses_2']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['employee_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['employee_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['employee_expenses_of_growth']['label'] }}</td>
        @foreach($rows['employee_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['office_expenses_posting']['label'] }}</td>
        @foreach($rows['office_expenses_posting']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['office_supplies_of_revenue']['label'] }}</td>
        @foreach($rows['office_supplies_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['office_supplies_of_growth']['label'] }}</td>
        @foreach($rows['office_supplies_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['utilities']['label'] }}</td>
        @foreach($rows['utilities']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['utilities_of_revenue']['label'] }}</td>
        @foreach($rows['utilities_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['utilities_of_growth']['label'] }}</td>
        @foreach($rows['utilities_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['rent_or_lease_expenses']['label'] }}</td>
        @foreach($rows['rent_or_lease_expenses']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['rent_lease_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['rent_lease_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['rent_lease_expenses_of_growth']['label'] }}</td>
        @foreach($rows['rent_lease_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row">
        <td>{{ $rows['office_related_expenses_2']['label'] }}</td>
        @foreach($rows['office_related_expenses_2']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['office_expenses_of_revenue']['label'] }}</td>
        @foreach($rows['office_expenses_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="bg-table-highlight-row text-italic">
        <td class="label-indent">{{ $rows['office_expenses_of_growth']['label'] }}</td>
        @foreach($rows['office_expenses_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['depreciation_2']['label'] }}</td>
        @foreach($rows['depreciation_2']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['depreciation_of_revenue']['label'] }}</td>
        @foreach($rows['depreciation_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['depreciation_of_growth']['label'] }}</td>
        @foreach($rows['depreciation_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['amortization_2']['label'] }}</td>
        @foreach($rows['amortization_2']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['amortization_of_revenue']['label'] }}</td>
        @foreach($rows['amortization_of_revenue']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['amortization_of_growth']['label'] }}</td>
        @foreach($rows['amortization_of_growth']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="fw-bold">
        <td>{{ $rows['taxes']['label'] }}</td>
        @foreach($rows['taxes']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    <tr class="text-italic">
        <td class="label-indent">{{ $rows['business_taxes_of_pre_tax_income']['label'] }}</td>
        @foreach($rows['business_taxes_of_pre_tax_income']['values'] as $value)
            <td class="text-right">{{ $value }}</td>
        @endforeach
    </tr>
    </tbody>
</table>

<table class="financial-table mb2">
    <thead>
        <tr>
            <th class="historical-financial-table-label">
                <strong>{{ $rows['summary_of_non_recurring_expense_adjustments']['label'] }}</strong>
            </th>
            @forelse($rows['summary_of_non_recurring_expense_adjustments']['values'] as $value)
                <th class="historical-financial-table-label year">{{ $value }}</th>
                @empty
                    <th class="historical-financial-table-label year"></th>
                    <th class="historical-financial-table-label year"></th>
                    <th class="historical-financial-table-label year"></th>
                    <th class="historical-financial-table-label year"></th>
            @endforelse
        </tr>
    </thead>

    <tbody>
    @foreach($rows['expenses'] as $expense)
        <tr>
            <td>{{ $expense['label'] }}</td>
            @foreach($expense['values'] as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>
    @endforeach

    <tr class="bg-table-highlight-row">
        <td>{{ $rows['total_expense_adjustments']['label'] }}</td>
        @foreach($rows['total_expense_adjustments']['values'] as $value)
            <td>{{ $value }}</td>
        @endforeach
    </tr>

    </tbody>
</table>
