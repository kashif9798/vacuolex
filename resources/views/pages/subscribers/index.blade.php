@extends('layouts/contentLayoutMaster')

@section('title', 'Subscribers')

@section('content')
<div class="card">
  <div class="card-body pb-1">
    <div class="row">
      <div class="col-12 col-md-6 col-lg-8">
        @if(session('subscriber_alert'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <div class="alert-body">
                  {!!session('subscriber_alert')!!}
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      </div>
      <div class="col-12 col-md-6 col-lg-4">
        <form method="POST" action="{{ route('subscribers.search') }}">
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
          <th>Microbes Collected</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($subscribers as $subscriber)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $subscriber->name }}</td>
              <td>{{ $subscriber->email }}</td>
              <td>
                @empty($subscriber->image)
                  <img class="round" src="{{asset('images/portrait/small/default-profile-small.png')}}" alt="avatar" height="40" width="40">
                @else
                  <img class="round" src="{{$subscriber->image}}" alt="avatar" height="40" width="40">
                @endempty
              </td>
              <td>{{ $subscriber->microbes_collected_count }}</td>
              <td>
                @if (auth()->user()->role->level == 1)
                  <div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                      <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteSubscribers{{ $subscriber->id }}">
                          <i data-feather="trash" class="mr-50"></i>
                          <span>Delete</span>
                        </a>
                    </div>
                  </div>
                  @include("pages.subscribers.delete")
                @else
                  -
                @endif
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-center align-items-center mt-2">
    {{ $subscribers->links() }}
  </div>
</div>
@endsection
