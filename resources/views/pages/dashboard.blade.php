@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('content')
<!-- Line Chart Card -->
<div class="row">
  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-primary p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="grid" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $categories }}</h2>
        <p class="card-text">Categories</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-info p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="layers" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $subcategories }}</h2>
        <p class="card-text">Subcategories</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-success p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="message-square" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $microbes }}</h2>
        <p class="card-text">Microbes</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-warning p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="user-check" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $subscribers }}</h2>
        <p class="card-text">Subscribers</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-warning p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="users" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $admins }}</h2>
        <p class="card-text">Admins</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-primary p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="refresh-cw" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $collaborators }}</h2>
        <p class="card-text">Collaborators</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-info p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="message-circle" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $comments }}</h2>
        <p class="card-text">Total Comments</p>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card text-center">
      <div class="card-body">
        <div class="avatar bg-light-success p-50 mb-1">
          <div class="avatar-content">
            <i data-feather="star" class="font-medium-5"></i>
          </div>
        </div>
        <h2 class="font-weight-bolder">{{ $ratings }}</h2>
        <p class="card-text">Total Ratings</p>
      </div>
    </div>
  </div>

</div>
<!--/ Page layout -->
@endsection