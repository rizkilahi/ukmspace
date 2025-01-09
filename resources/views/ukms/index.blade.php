@extends('layouts.guest')

@section('title', 'UKM - UKM Space')

@section('content')
<!-- Hero Section -->
<section class="container">
    <h4>UKM List</h4>
    <div class="d-flex flex-wrap gap-5">
    @foreach ($ukms as $k)

<div class="card col-3" style="width: 18rem;">
  <img src="/{{$k['logo']}}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">{{$k["name"]}}</h5>
    <p class="card-text">{{$k["description"]}}</p>
    <a href="#" class="btn btn-primary">Check Availability!</a>
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
      <li class="page-item {{ $ukms->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link text-black bg-body-tertiary" href="{{ $ukms->previousPageUrl() }}" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      
      <!-- Page numbers -->
      @foreach ($ukms as $pageNumber => $item)
        <li class="page-item {{ $ukms->currentPage() == $pageNumber + 1 ? 'active' : '' }}">
          <a class="page-link" href="{{ $ukms->url($pageNumber + 1) }}">{{ $pageNumber + 1 }}</a>
        </li>
      @endforeach

      <!-- Next page link -->
      <li class="page-item {{ $ukms->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link text-black bg-light-subtle" href="{{ $ukms->nextPageUrl() }}" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>
</section>
@endsection