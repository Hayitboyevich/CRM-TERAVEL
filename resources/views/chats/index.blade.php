@extends('layouts.app')

@push('styles')
    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
@endpush

@section('content')
    <div class="px-4 py-0 py-lg-4  border-top-0 admin-dashboard">
        <div class="col-xl-12  col mb-4">
            <div class="row">
                <iframe src="https://www.tyntec.com/docs/conversations-inbox-channels-instagram-direct-messages">

                </iframe>
            </div>
        </div>
    </div>
@endsection