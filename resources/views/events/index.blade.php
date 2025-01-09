@extends('layouts.guest')

@section('title', 'Events - UKM Space')

@section('content')
<!-- Hero Section -->
<section class="container">
    <h4>Event List</h4>
    <div class="d-flex flex-wrap gap-5">
    @foreach ($events as $e)

<div class="card col-3" style="width: 18rem;">
  <img src="{{$e['pict']}}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">{{$e["title"]}}</h5>
    <p class="card-text">{{$e["description"]}}</p>
    <a href="#" class="btn btn-primary">Reserve Your Seat Now!</a>
  </div>
</div>
@endforeach
</div>
</section>


<!-- Stats Section -->
<section class="stats py-5 bg-white container justify-content-center d-flex">
  <nav aria-label="Page navigation example">
    <ul class="pagination">
      <!-- Previous page link -->
      <li class="page-item {{ $events->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link text-black bg-body-tertiary" href="{{ $events->previousPageUrl() }}" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      
      <!-- Page numbers -->
      @foreach ($events as $pageNumber => $item)
        <li class="page-item {{ $events->currentPage() == $pageNumber + 1 ? 'active' : '' }}">
          <a class="page-link" href="{{ $events->url($pageNumber + 1) }}">{{ $pageNumber + 1 }}</a>
        </li>
      @endforeach

      <!-- Next page link -->
      <li class="page-item {{ $events->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link text-black bg-light-subtle" href="{{ $events->nextPageUrl() }}" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>
</section>
@endsection