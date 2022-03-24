@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Category')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <form method="POST" action="{{ route('categories.update', $category) }}">
          @csrf
          @method("PUT")
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ empty(old("title")) ? $category->title : old("title") }}" />
            @error('title')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" rows="5">{{ empty(old("description")) ? $category->description : old("description") }}</textarea>
            @error('description')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">
              Update Category
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
