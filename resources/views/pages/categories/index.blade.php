@extends('layouts/contentLayoutMaster')

@section('title', 'Categories')

@section('content')
<div class="card">
  <div class="card-body pb-1">
    <div class="row">
      <div class="col-12 col-md-6 col-lg-8">
        @if(session('category_alert'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <div class="alert-body">
                  {!!session('category_alert')!!}
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      </div>
      <div class="col-12 col-md-6 col-lg-4">
        <form method="POST" action="{{ route('categories.search') }}">
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
          <th>Description</th>
          <th>SubCategories</th>
          <th>Microbes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($categories as $category)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $category->title }}</td>
              <td>{{ $category->description }}</td>
              <td>{{ $category->sub_categories_count }}</td>
              <td>{{ $category->microbes_count }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                    <i data-feather="more-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route("categories.edit", $category) }}">
                      <i data-feather="edit-2" class="mr-50"></i>
                      <span>Edit</span>
                    </a>
                    @if ($category->sub_categories_count == 0 && $category->microbes_count == 0)
                      <a type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteCategory{{ $category->id }}">
                        <i data-feather="trash" class="mr-50"></i>
                        <span>Delete</span>
                      </a>
                    @endif
                  </div>
                </div>
                @if ($category->sub_categories_count == 0 && $category->microbes_count == 0)
                  @include("pages.categories.delete")
                @endif
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-center align-items-center mt-2">
    {{ $categories->links() }}
  </div>
</div>
@endsection
