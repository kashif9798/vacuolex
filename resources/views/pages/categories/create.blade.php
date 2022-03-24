@extends('layouts/contentLayoutMaster')

@section('title', 'Create Category')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <form method="POST" action="{{ route('categories.store') }}">
          @csrf
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old("title") }}" />
            @error('title')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" rows="5">{{ old("description") }}</textarea>
            @error('description')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">
              Create Category
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
