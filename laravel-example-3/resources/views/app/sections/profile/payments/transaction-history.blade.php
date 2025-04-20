@php
$transactions = auth()->user()->transactions;
@endphp
@if(!$transactions->isEmpty())
<app-form-accordion
header-title="Transaction History"
:collapsible="false">
    <template slot="content">
        <table class="width-100">
            <thead>
                <tr>
                    <th class="text-left">Date:</th>
                    <th class="text-left">Transaction Type:</th>
                    <th class="text-left">Description:</th>
                    <th class="text-left">Amount:</th>
                </tr>
            </thead>

            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>
                        <timezone-date date="{{ $transaction->created_at }}"></timezone-date>
                    </td>
                    <td>{{ $transaction->label }}</td>
                    @if ($transaction->isMonthly())
                    <td>
                        <timezone-date-range
                        start-date="{{ $transaction->start_date }}"
                        end-date="{{ $transaction->end_date }}"></timezone-date-range>
                    </td>
                    @else
                    <td>{{ $transaction->description }}</td>
                    @endif
                    <td>{{ $transaction->amount_for_display }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </template>
</app-form-accordion>
@endif
