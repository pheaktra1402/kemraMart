@extends('layouts.admin')

@section('title', 'Categories')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Categories</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">+ New Category</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Name</th>
                        <th>Products</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="btn btn-outline-dark btn-sm">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.categories.destroy', $category) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $categories->links() }}</div>
@endsection