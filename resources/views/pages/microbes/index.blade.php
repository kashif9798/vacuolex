@extends('layouts/contentLayoutMaster')

@section('title', 'Microbes')

@section('content')
<div class="card">
  <div class="card-body pb-1">
    <div class="row">
      <div class="col-12 col-md-6 col-lg-8">
        @if(session('microbe_alert'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <div class="alert-body">
                  {!!session('microbe_alert')!!}
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      </div>
      <div class="col-12 col-md-6 col-lg-4">
        <form method="POST" action="{{ route('microbes.search') }}">
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
          <th>Title</th>
          <th>Image</th>
          <th>Author</th>
          <th>Category</th>
          <th>Subcategory</th>
          <th>Comments</th>
          <th>Ratings</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($microbes as $microbe)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $microbe->title }}</td>
              <td>
                @if($microbe->image)
                  <img src="{{$microbe->image}}" alt="avatar" height="40" width="40">
                @endempty
              </td>
              <td>{{ $microbe->author->name }}</td>
              <td>{{ $microbe->subcategory->title }}</td>
              <td>{{ $microbe->subcategory->category->title }}</td>
              <td>{{ $microbe->comments_count }}</td>
              <td>
                @if ($microbe->rating > 0)
                  @for ($i = 1; $i <= 5; $i++)
                    @if ($microbe->rating >= $i)
                      <i data-feather='star' class="text-warning"></i>
                    @else
                      <i data-feather='star'></i>
                    @endif
                  @endfor
                @else
                  -
                @endif

              </td>
              <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                      <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ route("admins.edit", $microbe) }}">
                        <i data-feather="edit-2" class="mr-50"></i>
                        <span>Edit</span>
                      </a>
                        <a type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteAdmin{{ $microbe->id }}">
                          <i data-feather="trash" class="mr-50"></i>
                          <span>Delete</span>
                        </a>
                    </div>
                  </div>
                  {{-- @include("pages.admins.delete") --}}
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-center align-items-center mt-2">
    {{ $microbes->links() }}
  </div>
</div>
@endsection
