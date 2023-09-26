@extends('layouts.app.layout')
    <meta name="company" content="{{ Auth::user()->company }}">
@section('layout-content')
    <div class="row gutter-xs">
        <Customer-new />
    </div><!-- /.row .gutter-xs -->
@endsection

@section('layout-footer')
    <div class="layout-footer">
        <div class="layout-footer-body">
            <small class="version">{{config('app_data.version')}}</small>
            <small class="copyright">© DAB </small>
        </div>
    </div>
    <accounting_account_modal />
@endsection

