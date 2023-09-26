@extends('layouts.app.layout')

@section('layout-content')
    <div class="row gutter-xs">
    <provider_base />
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

@section('scripts')
    <!-- <script src="/js/app/product.min.js"></script> -->
@endsection

