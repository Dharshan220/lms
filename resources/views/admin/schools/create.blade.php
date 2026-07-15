@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New School</h1>
                <p class="text-muted mt-1 mb-0">Register a new school in the system</p>
            </div>
            <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Schools
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
                <form action="{{ route('admin.schools.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="city" class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="city"
                                   class="form-control @error('city') is-invalid @enderror"
                                   value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="state" class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" id="state"
                                   class="form-control @error('state') is-invalid @enderror"
                                   value="{{ old('state') }}" required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="pincode" class="form-label fw-semibold">Pincode <span class="text-danger">*</span></label>
                            <input type="text" name="pincode" id="pincode"
                                   class="form-control @error('pincode') is-invalid @enderror"
                                   value="{{ old('pincode') }}" required>
                            @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="contact_person_name" class="form-label fw-semibold">Contact Person Name</label>
                            <input type="text" name="contact_person_name" id="contact_person_name"
                                   class="form-control @error('contact_person_name') is-invalid @enderror"
                                   value="{{ old('contact_person_name') }}">
                            @error('contact_person_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="contact_person_phone" class="form-label fw-semibold">Contact Person Phone</label>
                            <input type="text" name="contact_person_phone" id="contact_person_phone"
                                   class="form-control @error('contact_person_phone') is-invalid @enderror"
                                   value="{{ old('contact_person_phone') }}">
                            @error('contact_person_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Create School
                                </button>
                                <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
