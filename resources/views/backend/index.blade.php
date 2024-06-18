@extends('backend.layouts.master')
@section('title','Dashboard')
@section('main-content')
<div class="container-fluid">
    @include('backend.layouts.notification')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  
@endpush