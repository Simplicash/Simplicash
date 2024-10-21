@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="d-flex flex-wrap justify-content-end align-content-center mb-4 gap-2">
        <a class="btn h-45 btn--base" href="{{ route('user.deposit.index') }}">
            <i class="las la-plus"></i>
            @lang('Deposit Now')
        </a>
        <x-search-form placeholder="TRX No." btn="btn--base" />
    </div>

    <div class="table-responsive--md table-responsive">
        <table class="custom--table table">
            <thead>
                <tr>
                    <th>@lang('TRX No.')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Charge')</th>
                    <th>@lang('After Charge')</th>
                    <th>@lang('Initiated At')</th>
                    <th>@lang('Method')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Details')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $deposit)
                    <tr>
                        <td>#{{ $deposit->trx }}</td>

                        <td>{{ showAmount($deposit->amount) }}</td>

                        <td>{{ showAmount($deposit->charge) }}</td>

                        <td>{{ showAmount($deposit->amount + $deposit->charge) }}</td>

                        <td><em>{{ showDateTime($deposit->created_at) }}</em></td>

                        <td>
                            @if ($deposit->branch)
                                <span class="text-primary" title="@lang('Branch Name')">{{ __(@$deposit->branch->name) }}</span>
                            @else
                                <span class="text-primary" title="@lang('Gateway Name')">{{ __(@$deposit->gateway->name) }}</span>
                            @endif
                        </td>

                        <td>@php echo $deposit->statusBadge @endphp</td>

                        @php
                            $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                        @endphp

                        <td>
                            <a href="{{ route('user.deposit.details', $deposit->trx) }}" class="btn btn-sm btn-outline--base"><i class="la la-desktop"></i> @lang('Details')</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($deposits->hasPages())
        <div class="mt-3">
            {{ $deposits->links() }}
        </div>
    @endif
@endsection
