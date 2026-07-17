@extends('admin.layouts.app')

@section('title', 'Edit Product Categories')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.product-categories.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Edit Product Category</h2>
                <p class="text-secondary mb-0">Update information for this category.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.product-categories.update', $category->id) }}" method="POST"
                class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12 col-md-8">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Name"
                                required>
                            <label for="name">Category Name</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('is_active') is-invalid @enderror"
                                id="is_active" name="is_active" required>
                                <option value="1"
                                    {{ old('is_active', $category->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0"
                                    {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="is_active">Status</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control rounded-3 shadow-none border bg-transparent @error('description') is-invalid @enderror"
                                id="description" name="description" placeholder="Description" style="height: 120px">{{ old('description', $category->description) }}</textarea>
                            <label for="description">Description</label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.product-categories.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
