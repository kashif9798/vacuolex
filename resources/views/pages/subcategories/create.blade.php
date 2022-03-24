@extends('layouts/contentLayoutMaster')

@section('title', 'Create Subcategory')
@push('styles')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

  <style>
    .select2-container--default .select2-selection--single .select2-selection__arrow b{
      display: none !important;
    }
  </style>
@endpush

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <form method="POST" action="{{ route('subcategories.store') }}">
          @csrf
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control @error("title") is-invalid @enderror" name="title" id="title" value="{{ old("title") }}" />
            @error('title')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="category-id">Category</label>
            <select class="select2 form-control form-control-lg" id="category-id" name="category_id">
              <option selected disabled>Select a category that the subcategory belongs to</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->title }}</option>
              @endforeach
            </select>
            @error('category_id')
              <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">
              Create Subcategory
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endpush
