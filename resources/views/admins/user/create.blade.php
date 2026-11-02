@extends('layouts.admin')

@section('title', 'New User')

@section('admin_content')
    <h3 class="mb-3">New User</h3>

    <div class="card shadow-sm border-0" style="max-width: 600px;">
        <div class="card-body">
           <form method="POST" action="{{ route('admin.user.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admins.user.form')
</form>
        </div>
    </div>
@endsection