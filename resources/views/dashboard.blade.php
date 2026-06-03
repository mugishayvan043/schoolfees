@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    @foreach([
        ['Students', $totalStudents, 'bi-people', 'primary'],
        ['Classes', $totalClasses, 'bi-grid', 'info'],
        ['Payments', $totalPayments, 'bi-receipt', 'success'],
        ['Collected', number_format($totalCollected, 2), 'bi-cash-coin', 'warning'],
        ['With Balances', $studentsWithBalances, 'bi-exclamation-circle', 'danger'],
    ] as [$label, $value, $icon, $color])
        <div class="col-12 col-sm-6 col-xl">
            <div class="metric-card">
                <div>
                    <p>{{ $label }}</p>
                    <h3>{{ $value }}</h3>
                </div>
                <span class="metric-icon text-bg-{{ $color }}"><i class="bi {{ $icon }}"></i></span>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h2>Monthly Collections</h2>
            </div>
            <canvas id="collectionsChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header">
                <h2>Financial Position</h2>
            </div>
            <canvas id="summaryChart" height="180"></canvas>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-header">
                <h2>Class Balances</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Class</th><th>Expected</th><th>Collected</th><th>Outstanding</th></tr></thead>
                    <tbody>
                    @foreach($classBalances as $row)
                        <tr>
                            <td>{{ $row['class'] }}</td>
                            <td>{{ number_format($row['expected'], 2) }}</td>
                            <td>{{ number_format($row['collected'], 2) }}</td>
                            <td><span class="badge text-bg-{{ $row['outstanding'] > 0 ? 'warning' : 'success' }}">{{ number_format($row['outstanding'], 2) }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-header">
                <h2>Recent Payments</h2>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentPayments as $payment)
                    <a href="{{ route('payments.receipt', $payment) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>{{ $payment->student->full_name }}<br><small>{{ $payment->receipt_number }}</small></span>
                        <strong>{{ number_format($payment->amount_paid, 2) }}</strong>
                    </a>
                @empty
                    <p class="text-muted mb-0">No payments recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.dashboardData = {
    collections: @json($collectionSeries),
    expected: {{ (float) $totalExpected }},
    collected: {{ (float) $totalCollected }},
};
</script>
@endpush
