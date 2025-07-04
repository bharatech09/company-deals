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
        <h2 class="fw-bold text-primary mb-2">No Objection Certificate of Your Trade Mark</h2>
        <hr class="mx-auto" style="width: 80px; border-top: 3px solid #0d6efd;">
        <p class="text-muted fs-6">View details of your registered trademarks</p>
      </div>
    </div>

    <div class="row g-4">
      @foreach ($nocTrademarks as $trademark)
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm rounded-4 h-100 border-0">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-semibold text-primary mb-3 " title="{{ $trademark->wordmark }}">
              {{ $trademark->wordmark }}
              <span class="badge bg-info text-dark ms-2 fs-7">
                Class {{ $trademark->class_no ? $trademark->class_no : 'N/A' }}
              </span>
            </h5>


            <ul class="list-unstyled text-secondary mb-4 flex-grow-1 small">
              <li><strong>Application No:</strong> {{ $trademark->application_no }}</li>
              <li><strong>Proprietor:</strong> {{ $trademark->proprietor }}</li>
              <li><strong>Status:</strong>
                @if(strtolower($trademark->status) == 'active')
                <span class="badge bg-success text-uppercase">{{ $trademark->status }}</span>
                @else
                <span class="badge bg-warning text-uppercase">{{ $trademark->status }}</span>
                @endif
              </li>
              <li><strong>Valid Upto:</strong> {{ date('j F, Y', strtotime($trademark->valid_upto)) }}</li>
              <li><strong>Description:</strong> {{ Str::limit($trademark->description, 80, '...') }}</li>
            </ul>

            <div class="mt-auto">
              <span class="fs-5 fw-bold text-success">
                Ask Price: ₹{{ number_format($trademark->ask_price) }} {{ $trademark->ask_price_unit }} / month
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