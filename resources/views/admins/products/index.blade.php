@extends('layouts.admin')

@section('title', 'Products')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Products</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-dark">+ New Product</a>
    </div>

   
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control"
                   placeholder="Search by name" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                           <td>
    @if ($product->image)
        <img src="{{ asset('storage/' . $product->image) }}"
             alt="{{ $product->name }}"
             width="60" height="60"
             class="rounded object-fit-cover">
    @else
        <span class="text-muted">No image</span>
    @endif
</td>
                            <td>{{ $product->description }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $product->stock == 0 ? 'bg-danger' : ($product->stock <= 10 ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="btn btn-outline-dark btn-sm">Edit</a>

                                <form method="POST"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links() }}</div>
@endsection