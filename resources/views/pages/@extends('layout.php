@extends('layout.master')
@section('content')
<style>
  .text-primary {
    color: rgb(57 192 116) !important;
  }

  .hover-scale:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
  }

  button.btn-link {
    text-decoration: none;
    font-weight: 600;
  }

  button.btn-link:hover {
    text-decoration: underline;
  }
</style>

<section class="dashboard-wrap py-3 bg-light">
  <div class="container">
    <div class="row mb-4 text-center">
      <div class="col-12">
        <h2 class="fw-bold text-primary display-5">Companies</h2>
        <hr class="w-25 mx-auto border-3 border-primary">
      </div>
    </div>

    <div class="row g-4">
      @foreach ($companys as $company)

      <div class="col-md-6 col-lg-4" id="card-share-{{ $company->id }}">
        <div class="card shadow-lg border-0 rounded-4 h-100 hover-scale">
          <div class="card-header bg-primary bg-opacity-10 rounded-top-4">
            <h5 class="card-title fw-bold text-primary mb-0">
              {{ $company->name }} {{ $company->name_prefix }}
            </h5>
          </div>
          <div class="card-body d-flex flex-column">
            <ul class="list-unstyled mb-3 flex-grow-1">
              <li class="mb-2"><strong>Type Of Entity:</strong> <span class="text-secondary">{{ $company->type_of_entity }}</span></li>
              <li class="mb-2"><strong>ROC:</strong> <span class="text-secondary">{{ $company->roc }}</span></li>
              <li class="mb-2"><strong>Year of Incorporation:</strong> <span class="text-secondary">{{ $company->year_of_incorporation }}</span></li>
              <li class="mb-2"><strong>Industry:</strong> <span class="text-secondary">{{ $company->industry }}</span></li>
              <li class="mb-2"><strong>Ask Price:</strong> <span class="text-success fw-semibold">₹{{ number_format($company->ask_price_amount ?? 0) }}
                 <!-- {{ $company->ask_price_unit ?? 'Rupees' }}/month -->
                </span></li>
              <li class="mb-2">
                <strong>Status:</strong>
                @if(strtolower($company->status ?? '') === 'active')
                <span class="badge bg-success text-uppercase">Active</span>
                @elseif(strtolower($company->status ?? '') === 'inactive')
                <span class="badge bg-danger text-uppercase">Inactive</span>
                @else
                <span class="badge bg-secondary text-uppercase">{{ $company->status ?? 'N/A' }}</span>
                @endif
              </li>

              @if(isset($company->buyer_status) && $company->buyer_status == 'inactive')
              <li class="mt-3">
                <a href="{{ route('user.buyer.company.removefrominterested', $company->id) }}" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                  Not Interested
                </a>
              </li>
              @endif
            </ul>

            <!-- Social Share Section -->
            <div class="mb-3">
              <p class="fw-semibold mb-1">Share This Company:</p>
              @php
              $shareUrl = urlencode(route('company.view', $company->id)); // You can change the route to actual company detail page
              $shareText = urlencode($company->name . ' is available on our platform.');
              @endphp
              <div class="d-flex gap-2 flex-wrap">
                <!-- <button onclick="shareCardImage({{ $company->id }})" class="btn btn-sm btn-secondary">
                  Share as Image
                </button> -->

                <a href="#" onclick="event.preventDefault();shareCardImage(this,'{{ $company->id }}','wp' )" target="_blank" class="btn btn-sm btn-success">WhatsApp</a>
                <a href="#" onclick="event.preventDefault();shareCardImage(this,'{{ $company->id }}','lk' )" target="_blank" class="btn btn-sm btn-primary">LinkedIn</a>
                <a href="#" onclick="event.preventDefault();shareCardImage(this,'{{ $company->id }}','fb' )" target="_blank" class="btn btn-sm btn-primary">Facebook</a>
                <a href="" onclick="event.preventDefault();shareCardImage(this,'{{ $company->id }}','tt' )" target="_blank" class="btn btn-sm btn-info">Twitter</a>
              </div>
            </div>

            <!-- More Details Collapsible -->

           <div id="moreDetails{{ $company->id }}" class="collapse border-top pt-3 mt-auto">
  <ul class="list-unstyled small text-muted">
    <li><strong>Have GST?:</strong> {{ $company->have_gst ?? 'N/A' }}</li>
    <li><strong>No. of Directors:</strong> {{ $company->no_of_directors }}</li>
    <li><strong>No. of Promoters:</strong> {{ $company->no_of_promoters }}</li>
    <li><strong>Activity Code:</strong> {{ $company->activity_code ?? 'N/A' }}</li>
    <li><strong>Authorised Capital:</strong> ₹{{ number_format($company->authorized_capital_amount ?? $company->authorised_capital_amount ?? 0) }}</li>
    <li><strong>Paid-up Capital:</strong> ₹{{ number_format($company->paid_up_capital_amount ?? $company->paidup_capital_amount ?? 0) }}</li>
    <li><strong>Demat Shareholding:</strong> {{ $company->demat_shareholding ?? 'N/A' }}%</li>
    <li><strong>Physical Shareholding:</strong> {{ $company->physical_shareholding ?? 'N/A' }}%</li>
    <li><strong>Promoters Shareholding:</strong> {{ $company->promoters_holding ?? 'N/A' }}%</li>
    <li><strong>Transferable Shareholding:</strong> {{ $company->transferable_holding ?? 'N/A' }}%</li>
    <li><strong>Public Shareholding:</strong> {{ $company->public_holding ?? 'N/A' }}%</li>
    <li><strong>Current Market Price:</strong> ₹{{ $company->current_market_price ?? 'N/A' }}</li>
    <li><strong>52 Weeks High:</strong> ₹{{ $company->high_52_weeks ?? 'N/A' }}</li>
    <li><strong>52 Weeks Low:</strong> ₹{{ $company->low_52_weeks ?? 'N/A' }}</li>
    <li><strong>Market Capitalization:</strong> ₹{{ number_format($company->market_capitalization_amount ?? 0) }}</li>
    <li><strong>Trading Conditions:</strong> {{ $company->trading_conditions ?? 'N/A' }}</li>
    <li><strong>Acquisition Method:</strong> {{ $company->acquisition_method ?? 'N/A' }}</li>
    <li><strong>Face Value:</strong> ₹{{ $company->face_value ?? 'N/A' }}</li>
    <li><strong>Type of NBFC:</strong> {{ $company->type_of_NBFC ?? 'N/A' }}</li>
    <li><strong>Size of NBFC:</strong> {{ $company->size_of_NBFC ?? 'N/A' }}</li>
    <li><strong>Turnover (2025):</strong> ₹{{ number_format($company->turnover_amount1 ?? 0) }}</li>
    <li><strong>Turnover (2024):</strong> ₹{{ number_format($company->turnover_amount2 ?? 0) }}</li>
    <li><strong>Profit (2025):</strong> ₹{{ number_format($company->profit_amount1 ?? 0) }}</li>
    <li><strong>Profit (2024):</strong> ₹{{ number_format($company->profit_amount2 ?? 0) }}</li>
    <li><strong>Net Worth:</strong> ₹{{ number_format($company->net_worth_amount ?? 0) }}</li>
    <li><strong>Reserve:</strong> ₹{{ number_format($company->reserve_amount ?? 0) }}</li>
    <li><strong>Secured Creditors:</strong> ₹{{ number_format($company->secured_creditors_amount ?? 0) }}</li>
    <li><strong>Unsecured Creditors:</strong> ₹{{ number_format($company->unsecured_creditors_amount ?? 0) }}</li>
    <li><strong>Land & Building:</strong> ₹{{ number_format($company->land_building_amount ?? 0) }}</li>
    <li><strong>Plant & Machinery:</strong> ₹{{ number_format($company->plant_machinery_amount ?? 0) }}</li>
    <li><strong>Investment:</strong> ₹{{ number_format($company->investment_amount ?? 0) }}</li>
    <li><strong>Debtors:</strong> ₹{{ number_format($company->debtors_amount ?? 0) }}</li>
    <li><strong>Cash & Bank:</strong> ₹{{ number_format($company->cash_bank_amount ?? 0) }}</li>
    <li><strong>ROC Status:</strong> {{ $company->roc_status ?? 'N/A' }} ({{ $company->roc_year ?? 'N/A' }})</li>
    <li><strong>Income Tax Status:</strong> {{ $company->income_tax_status ?? 'N/A' }} ({{ $company->income_tax_year ?? 'N/A' }})</li>
    <li><strong>GST Status:</strong> {{ $company->gst_status ?? 'N/A' }}</li>
    <li><strong>RBI Status:</strong> {{ $company->rbi_status ?? 'N/A' }}</li>
    <li><strong>FEMA Status:</strong> {{ $company->fema_status ?? 'N/A' }}</li>
    <li><strong>80G/12A Certificate :</strong> {{ $company->year_of_incorporation ?? 'N/A' }}</li>
    {{-- <li><strong>Auditor's Report:</strong> {{ $company->auditor_report ?? 'N/A' }}</li> --}}
  </ul>
</div>


            <!-- Show More / Less Toggle -->
            <button class="btn btn-link text-primary mt-auto d-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#moreDetails{{ $company->id }}" aria-expanded="false" aria-controls="moreDetails{{ $company->id }}">
              <span class="toggle-text">Show More</span>
              <svg class="bi bi-chevron-down toggle-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
              </svg>
            </button>

          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
  function shareCardImage(anchorElement, companyId, link) {
    const href = $(anchorElement).attr('href'); // Now this works
    // make url #
    // alert(link);

    const cardElement = document.getElementById('card-share-' + companyId);

    html2canvas(cardElement, {
      scale: 2,
      useCORS: true
    }).then(canvas => {
      const imageData = canvas.toDataURL('image/png');

      fetch("{{ route('share.card.upload') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          body: JSON.stringify({
            image: imageData,
            company_id: companyId
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success && data.image_url) {
            let shareUrl;
            if (link == 'wp') {
              shareUrl = `https://wa.me/?text=Check out this company! ${encodeURIComponent(data.image_url)}`;
            }
            if (link == 'lk') {
              shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(data.image_url)}`
            }
            if (link == 'fb') {
              shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(data.image_url)}`
            }

            if (link == 'tt') {
              shareUrl = `https://twitter.com/intent/tweet?text=Check out this company! ${encodeURIComponent(data.image_url)}`;

            }
            window.open(shareUrl, '_blank');
          } else {
            alert("Failed to upload image.");
          }
        })
        .catch(err => {
          console.error(err);
          alert("Something went wrong.");
        });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
      const targetId = button.getAttribute('data-bs-target');
      const target = document.querySelector(targetId);
      const toggleText = button.querySelector('.toggle-text');
      const toggleIcon = button.querySelector('.toggle-icon');

      if (target) {
        // Create Bootstrap collapse instance
        const bsCollapse = new bootstrap.Collapse(target, {
          toggle: false
        });

        // Update text and icon when shown
        target.addEventListener('shown.bs.collapse', function () {
          toggleText.textContent = 'Show Less';
          toggleIcon.style.transform = 'rotate(180deg)';
          button.setAttribute('aria-expanded', 'true');
        });

        // Update text and icon when hidden
        target.addEventListener('hidden.bs.collapse', function () {
          toggleText.textContent = 'Show More';
          toggleIcon.style.transform = 'rotate(0deg)';
          button.setAttribute('aria-expanded', 'false');
        });

        // Handle click events
        button.addEventListener('click', function (e) {
          e.preventDefault();
          bsCollapse.toggle();
        });
      }
    });
  });
</script>
@endsection