@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Admin')

@push("styles")
  <style>
    .profile-picture{
      width: 10rem;
      height: 10rem;
      object-fit: cover;
    }
    .profile-edit-btn{
      position: absolute;
      top: 0px;
      right: 0px;
    }
    #profile-container{
      cursor: pointer;
    }
  </style>
@endpush

@section('content')
<!-- Kick start -->
<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('admins.update', $admin) }}" enctype="multipart/form-data">
      @csrf
      @method("PUT")
      <div class="d-flex flex-column justify-content-center align-items-center mb-2">
        <div class="position-relative" id="profile-container">
          @empty($admin->image)
            <img class="rounded-circle profile-picture" id="profile-picture" src="{{asset('images/avatars/default-profile.png')}}" alt="avatar">
          @else
            <img class="rounded-circle profile-picture" id="profile-picture" src="{{$admin->image}}" alt="avatar">
          @endempty
          <button type="button" class="rounded-circle profile-edit-btn btn btn-icon btn-primary">
            <i data-feather="edit"></i>
          </button>
        </div>
      </div>
      
      <input type="file" name="image" id="image" class="d-none">
      @error('image')
        <strong class="text-danger mt-2">{{ $message }}</strong>
      @enderror

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label" for="name">Name</label>
            <input type="text" class="form-control @error("name") is-invalid @enderror" id="name" name="name" value="{{ empty(old("name")) ? $admin->name : old("name") }}" />
            @error('name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ empty(old("email")) ? $admin->email : old("email") }}" />
            @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge form-password-toggle @error("password") is-invalid @enderror">
              <input type="password" class="form-control @error("password") is-invalid @enderror" id="password" name="password" />
              <div class="input-group-append">
                  <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
              </div>
            </div>
            <small>Leave the password field empty, if you do not wish to update it.</small>
            @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="role-id">Role</label>
            <select class="form-control @error("role_id") is-invalid @enderror" id="role-id" name="role_id">
              <option selected disabled>Select a role for the user</option>
              @foreach ($roles as $role)
                @if(old("role_id") == $role->id)
                  <option value="{{ $role->id }}" selected>{{ $role->title }}</option>
                @else
                  @if ($admin->role_id == $role->id)
                    <option value="{{ $role->id }}" selected>{{ $role->title }}</option>
                  @else
                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                  @endif
                @endif
              @endforeach
            </select>
            @error('role_id')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <button type="submit" class="btn btn-primary" tabindex="3">Update User</button>
          </div>
        </div>

      </div>
    </form>

  </div>
</div>
<!--/ Kick start -->

@endsection

@push("scripts")
  <script>
    $( document ).ready(function() {
      
      $("#profile-container").on("click", function (event) {
        event.stopPropagation();
        event.preventDefault();
        $("#image").trigger("click");
      });
      
      $("#image").on("change", function (event) {
        let reader = new FileReader();
        reader.onload = () => {
          $("#profile-picture").attr("src",reader.result);
        };
        reader.readAsDataURL(event.target.files[0]);
      });
    });
  </script>
@endpush