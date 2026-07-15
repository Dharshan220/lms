@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
                <p class="text-muted mt-1 mb-0">Update category details</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Categories
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="icon" class="form-label fw-semibold">Icon <small class="text-muted">(Bootstrap Icon name)</small></label>
                            <input type="text" name="icon" id="icon"
                                   class="form-control @error('icon') is-invalid @enderror"
                                   value="{{ old('icon', $category->icon) }}" placeholder="e.g. code-slash, robot, cpu">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="color" class="form-label fw-semibold">Color</label>
                            <div class="input-group">
                                <input type="color" name="color" id="color"
                                       class="form-control form-control-color @error('color') is-invalid @enderror"
                                       value="{{ old('color', $category->color ?? '#667eea') }}">
                                <input type="text" class="form-control" id="colorText" value="{{ old('color', $category->color ?? '#667eea') }}">
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="parent_id" class="form-label fw-semibold">Parent Category <small class="text-muted">(optional)</small></label>
                            <select name="parent_id" id="parent_id"
                                    class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">None (Top Level)</option>
                                @foreach($categories ?? [] as $cat)
                                    @if($cat->id !== $category->id)
                                        <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Category
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});
document.getElementById('colorText').addEventListener('input', function() {
    document.getElementById('color').value = this.value;
});
</script>
@endpush
@endsection
