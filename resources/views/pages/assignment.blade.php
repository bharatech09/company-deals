@extends('layout.master')
@section('content')

<style>
  .text-primary {
    color: rgb(57, 192, 116) !important;
  }
  .badge-active {
    background-color: rgb(57, 192, 116);
    color: white;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
  }
  .badge-inactive {
    background-color: #dc3545;
    color: white;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
    border-radius: 0.25rem;
  }
  .card-custom {
    border-radius: 0.75rem;
    box-shadow: 0 8px 20px rgba(57, 192, 116, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .card-custom:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 40px rgba(57, 192, 116, 0.3);
  }
  .feature-list li {
    padding: 0.3rem 0;
    font-size: 0.95rem;
    color: #444;
  }
</style>

<section class="dashboard-wrap py-5 bg-light">
  <div class="container">
    <div class="row mb-4">
      <div class="col-12 text-center">
        <h2 class="fw-bold text-primary mb-2">Assignments</h2>
        <hr class="mx-auto" style="width: 80px; border-top: 3px solid rgb(57, 192, 116);">
      </div>
    </div>

    <div class="row g-4">
      @foreach ($assignments as $assignment)
        <div class="col-md-6 col-lg-4">
          <div class="card card-custom h-100 p-3">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-semibold mb-3 text-primary text-capitalize">
                {{ $assignment['subject'] }}
              </h5>

              <ul class="feature-list flex-grow-1">
                <li><strong>Category:</strong> {{ $assignment['category'] }}</li>
                <li><strong>Description:</strong> {{ Str::limit($assignment['description'], 90, '...') }}</li>
                <li><strong>Ask Price:</strong> ₹{{ number_format($assignment['deal_price']) }}</li>
                <li>
                  <strong>Status:</strong>
                  @if(strtolower($assignment['is_active']) == 'yes' || strtolower($assignment['is_active']) == 'active')
                    <span class="badge-active">Active</span>
                  @else
                    <span class="badge-inactive">Inactive</span>
                  @endif
                </li>
              </ul>

            
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
