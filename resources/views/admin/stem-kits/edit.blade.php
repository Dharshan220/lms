@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit STEM Kit</h1>
                <p class="text-muted mt-1 mb-0">Update kit details</p>
            </div>
            <a href="{{ route('admin.stem-kits.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to STEM Kits
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

        <form action="{{ route('admin.stem-kits.update', $stemKit) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">Kit Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $stemKit->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="description" rows="4"
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $stemKit->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Components</label>
                                <div id="components-container">
                                    @php $components = old('components', $stemKit->components ?? ['']); @endphp
                                    @foreach($components as $comp)
                                        <div class="input-group mb-2 component-row">
                                            <input type="text" name="components[]" class="form-control" placeholder="Component name" value="{{ $comp }}">
                                            <button type="button" class="btn btn-outline-danger component-remove"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm" id="addComponent">
                                    <i class="bi bi-plus-circle me-1"></i> Add Component
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="category" class="form-label fw-semibold">Category</label>
                                <input type="text" name="category" id="category"
                                       class="form-control @error('category') is-invalid @enderror"
                                       value="{{ old('category', $stemKit->category) }}">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="difficulty_level" class="form-label fw-semibold">Difficulty Level</label>
                                <select name="difficulty_level" id="difficulty_level" class="form-select">
                                    @foreach(['easy', 'medium', 'hard'] as $level)
                                        <option value="{{ $level }}" {{ old('difficulty_level', $stemKit->difficulty_level) == $level ? 'selected' : '' }}>{{ ucfirst($level) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label fw-semibold">Price ($)</label>
                                <input type="number" name="price" id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $stemKit->price) }}" step="0.01" min="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" name="stock_quantity" id="stock_quantity"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       value="{{ old('stock_quantity', $stemKit->stock_quantity) }}" min="0">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Image</label>
                                @if($stemKit->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $stemKit->image) }}" alt="" class="rounded" style="max-width: 100%; max-height: 120px;">
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1"
                                       {{ old('is_available', $stemKit->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_available">Available</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-circle me-1"></i> Update Kit
                        </button>
                        <a href="{{ route('admin.stem-kits.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('addComponent').addEventListener('click', function() {
    var container = document.getElementById('components-container');
    var row = document.createElement('div');
    row.className = 'input-group mb-2 component-row';
    row.innerHTML = '<input type="text" name="components[]" class="form-control" placeholder="Component name"><button type="button" class="btn btn-outline-danger component-remove"><i class="bi bi-x-lg"></i></button>';
    container.appendChild(row);
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.component-remove')) {
        var rows = document.querySelectorAll('.component-row');
        if (rows.length > 1) {
            e.target.closest('.component-row').remove();
        }
    }
});
</script>
@endpush
@endsection
