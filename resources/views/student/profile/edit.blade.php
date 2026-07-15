@extends('layouts.app')

@push('styles')
<style>
    .avatar-upload { position: relative; width: 120px; height: 120px; }
    .avatar-upload input[type="file"] { display: none; }
    .avatar-upload .avatar-overlay { position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; border-radius: 50%; background: #0d6efd; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; }
</style>
@endpush

@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('student.profile.show') }}" class="text-decoration-none">Profile</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profile</h4>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="text-center mb-4">
                            <label for="avatarInput" class="avatar-upload d-inline-block">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:120px;height:120px;overflow:hidden;">
                                    @if($student->avatar)
                                        <img id="avatarPreview" src="{{ asset('storage/' . $student->avatar) }}" class="rounded-circle" style="width:120px;height:120px;object-fit:cover;">
                                    @else
                                        <span id="avatarPreview" class="display-4 text-primary fw-bold">{{ substr($student->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="avatar-overlay"><i class="bi bi-camera"></i></div>
                                <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewAvatar(this)">
                            </label>
                            <div class="mt-2"><small class="text-muted">Click to change avatar (max 2MB)</small></div>
                            @error('avatar')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mb-3">Personal Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Phone</label>
                                <input type="text" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control rounded-3 @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Gender</label>
                                <select name="gender" class="form-select rounded-3 @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mb-3">Address</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold small">Address</label>
                                <input type="text" name="address" class="form-control rounded-3 @error('address') is-invalid @enderror" value="{{ old('address', $student->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">City</label>
                                <input type="text" name="city" class="form-control rounded-3 @error('city') is-invalid @enderror" value="{{ old('city', $student->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">State</label>
                                <input type="text" name="state" class="form-control rounded-3 @error('state') is-invalid @enderror" value="{{ old('state', $student->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Pincode</label>
                                <input type="text" name="pincode" class="form-control rounded-3 @error('pincode') is-invalid @enderror" value="{{ old('pincode', $student->pincode) }}">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="bi bi-shield-lock me-1"></i>Change Password</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Current Password</label>
                                <input type="password" name="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">New Password</label>
                                <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('student.profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-check-circle me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('avatarPreview');
                if (preview.tagName === 'SPAN') {
                    var newImg = document.createElement('img');
                    newImg.id = 'avatarPreview';
                    newImg.src = e.target.result;
                    newImg.className = 'rounded-circle';
                    newImg.style.cssText = 'width:120px;height:120px;object-fit:cover;';
                    preview.parentNode.replaceChild(newImg, preview);
                } else {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
