@extends('layout.master')
@section('content')

  <style>
    .text-primary {
    color: rgb(57 192 116) !important;
    }
  </style>
  <section class="dashboard-wrap py-5 bg-light">
    <div class="container">
    <div class="row mb-4">
      <div class="col-12 text-center">
      <h2 class="fw-bold text-primary mb-2">Properties</h2>
      <hr class="mx-auto" style="width: 60px; border-top: 3px solid  rgb(57 192 116);">
      </div>
    </div>

    <div class="row g-4">
      @foreach ($properties as $property)
      <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm rounded-4 h-100 border-0">
      <div class="card-body d-flex flex-column">
      <h5 class="card-title text-primary fw-semibold mb-3">
        {{ $property->type }}
        <span class="badge bg-info text-dark ms-2 fs-7">{{ $property->state }}</span>
      </h5>

      <ul class="list-unstyled text-secondary mb-4 flex-grow-1">
        <li><strong>State:</strong> {{ $property->state ?? ''}}</li>
        <li><strong>Pincode:</strong> {{ $property->pincode }}</li>
        <li><strong>Space:</strong> {{ number_format($property->space) }} Sq. ft.</li>
        <li><strong>Type:</strong> {{ $property->type  }}</li>

      </ul>

      <div class="mt-auto">
        <span class="fs-5 fw-bold text-success">
        Ask Price: ₹{{ number_format($property->ask_price) }} {{ $property->ask_price_unit }} {{$property->property_type}}
        </span>
      </div>
      </div>
      <!-- <div class="card-footer bg-transparent border-0 text-end">
      <a href="#" class="btn btn-outline-primary btn-sm">View Details</a>
      </div> -->
      </div>
      </div>



    @endforeach
    </div>
    </div>
  </section>

  <style>
    .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(13, 110, 253, 0.2);
    }

    .card-title {
    text-transform: capitalize;
    }
  </style>
@endsection