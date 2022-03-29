@extends('layouts/contentLayoutMaster')

@section('title', 'Profile')

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
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      @method("PUT")
      <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="position-relative" id="profile-container">
          @empty(auth()->user()->image)
            <img class="rounded-circle profile-picture" id="profile-picture" src="{{asset('images/avatars/default-profile.png')}}" alt="avatar">
          @else
            <img class="rounded-circle profile-picture" id="profile-picture" src="{{auth()->user()->image}}" alt="avatar">
          @endempty
          <button type="button" class="rounded-circle profile-edit-btn btn btn-icon btn-primary">
            <i data-feather="edit"></i>
          </button>
        </div>


        <p class="lead mt-2">{{ auth()->user()->name }}</p>
      </div>
      
      <input type="file" name="image" id="image" class="d-none" accept="image/*">
      @error('image')
        <strong class="text-danger mt-2">{{ $message }}</strong>
      @enderror

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label" for="name">Name</label>
            <input type="text" class="form-control @error("name") is-invalid @enderror" id="name" name="name" value="{{ empty(old("name")) ? auth()->user()->name : old("name") }}" />
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
            <input type="email" class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ empty(old("email")) ? auth()->user()->email : old("email") }}" />
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
            <small class="text-muted">Leave the field empty if you do not wish to update the password</small>
            @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <label class="form-label" for="password-confirmation">Confirm Password</label>
            <div class="input-group input-group-merge form-password-toggle @error("password_confirmation") is-invalid @enderror">
              <input type="password" class="form-control @error("password_confirmation") is-invalid @enderror" id="password-confirmation" name="password_confirmation" />
              <div class="input-group-append">
                  <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
              </div>
            </div>
            @error('password_confirmation')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="col-12 mt-2 text-center">
          <button type="submit" class="btn btn-primary" tabindex="3">Update Profile</button>
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
