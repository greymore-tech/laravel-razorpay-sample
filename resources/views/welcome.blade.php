@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div class="content">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{!! \Session::get('success') !!}</p>
                </div>
            @endif

            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    <p>{!! \Session::get('error') !!}</p>
                </div>
            @endif
            <welcome></welcome>
        </div>
    </div>
@endsection
