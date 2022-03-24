@extends('layouts/contentLayoutMaster')

@section('title', 'Admins')

@section('content')
<div class="card">
  <div class="card-body pb-1">
    <div class="row">
      <div class="col-12 col-md-6 col-lg-8">
        @if(session('admin_alert'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <div class="alert-body">
                  {!!session('admin_alert')!!}
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      </div>
      <div class="col-12 col-md-6 col-lg-4">
        <form method="POST" action="{{ route('admins.search') }}">
          @csrf
          <div class="form-group d-flex align-items-center justify-content-end">
            <input type="text" class="form-control" placeholder="search" name="search" value="{{ old("search") }}" />
            <button type="submit" class="btn btn-icon btn-primary rounded-circle ml-1">
              <i data-feather="search"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Image</th>
          <th>Role</th>
          <th>Microbes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($admins as $admin)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $admin->name }}</td>
              <td>{{ $admin->email }}</td>
              <td>
                @empty($admin->image)
                  <img class="round" src="{{asset('images/portrait/small/default-profile-small.png')}}" alt="avatar" height="40" width="40">
                @else
                  <img class="round" src="{{$admin->image}}" alt="avatar" height="40" width="40">
                @endempty
              </td>
              <td>{{ $admin->role->title }}</td>
              <td>{{ $admin->microbes_count }}</td>
              <td>
                @if (auth()->user()->id != $admin->id)
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                      <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ route("admins.edit", $admin) }}">
                        <i data-feather="edit-2" class="mr-50"></i>
                        <span>Edit</span>
                      </a>
                      @if ($admin->microbes_count == 0)
                        <a type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteAdmin{{ $admin->id }}">
                          <i data-feather="trash" class="mr-50"></i>
                          <span>Delete</span>
                        </a>
                      @endif
                    </div>
                  </div>
                  @if ($admin->microbes_count == 0)
                    @include("pages.admins.delete")
                  @endif
                @endif
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-center align-items-center mt-2">
    {{ $admins->links() }}
  </div>
</div>
@endsection
