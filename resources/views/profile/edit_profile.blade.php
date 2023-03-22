@extends('profile.profile-layout')
@section('profile')
<div class="card">
    <div class="card-header">Update Profile</div>
    <div class="card-body">
        <form action="{{route('update_profile')}}" id="edit_profile_form" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" value="{{(old('name'))?old('name'):$user->name}}" id="name"
                    name="name" placeholder="Enter your name">
                @if($errors->any('name'))
                <span class="text-danger">{{$errors->first('name')}}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" value="{{(old('email'))?old('email'):$user->email}}" id="email"
                    name="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                    else.</small>
                @if($errors->any('email'))
                <span class="text-danger">{{$errors->first('email')}}</span>
                @endif
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="tel" class="form-control"
                    value="{{(old('phone_number'))?old('phone_number'):$user->phone_number}}" id="phone_number"
                    name="phone_number" placeholder="Enter your phone_number">
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control"
                    value="{{(old('department'))?old('department'):$user->department}}" id="department"
                    name="department" placeholder="Enter your department name">
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" value="{{(old('gender'))?old('gender'):$user->gender}}"
                    name="gender">
                    <option value="">Choose...</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>

    </div>

</div>

@endsection