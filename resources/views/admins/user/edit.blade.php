@extends('layouts.admin')

@section('title', 'Edit User')

@section('admin_content')
    <h3 class="mb-3">Edit User</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.user.update', $user) }}">
                @csrf
                @method('PUT')
                @include('admins.user.form')
            </form>
        </div>
    </div>
@endsection