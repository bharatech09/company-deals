@extends('layout.master')
@section('content')
<style>
  .premium-section {
    background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
    padding: 60px 0;
  }

  .premium-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
  }

  .premium-section .card {
    border: none;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
  }

  .premium-section .card:hover {
    transform: translateY(-8px);
  }

  .feature-icon {
    font-size: 2.5rem;
    color: #0d9488;
    margin-bottom: 20px;
  }

  .premium-section h5 {
    font-weight: 600;
    font-size: 1.25rem;
    margin-bottom: 15px;
  }

  .premium-section p {
    color: #555;
  }

  .premium-section .img-fluid {
    border-radius: 12px;
    margin-top: 20px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
  }

  .premium-section .btn-primary {
    background: #0d9488;
    border: none;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  .premium-section .btn-primary:hover {
    background: #0f766e;
  }
</style>

<section class="premium-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2>Why Choose <span class="text-success">Companydeals</span>?</h2>
      <p class="text-muted fs-5">The premium platform to buy & sell businesses with trust, data, and performance</p>
    </div>

    <div class="row g-4">
      <!-- Feature Cards -->
      @php
        $features = [
          [
            'icon' => 'bi-list-check',
            'title' => 'Customized Company List',
            'desc' => 'Tailored business matches based on your buying/selling preferences, eliminating irrelevant clutter.',
            'img'  => 'why_us1.png'
          ],
          [
            'icon' => 'bi-speedometer2',
            'title' => 'Stay Focused with a Dashboard',
            'desc' => 'No missed leads. Track, follow-up, and manage deals in a centralized, easy-to-use dashboard.',
            'img'  => 'why_us2.png'
          ],
          [
            'icon' => 'bi-people-fill',
            'title' => 'More Deals, More Opportunities',
            'desc' => 'Access a wide pool of verified buyers and sellers from various industries and locations.',
            'img'  => null
          ],
          [
            'icon' => 'bi-shield-check',
            'title' => 'Verified Buyer Credentials',
            'desc' => 'Know who you’re dealing with. View buyer history and completed deals to ensure credibility.',
            'img'  => null
          ],
        ];
      @endphp

      @foreach ($features as $feature)
        <div class="col-md-6">
          <div class="card p-4 h-100">
            <div class="card-body">
              <div class="feature-icon text-success">
                <i class="bi {{ $feature['icon'] }}"></i>
              </div>
              <h5>{{ $feature['title'] }}</h5>
              <p>{{ $feature['desc'] }}</p>
              @if($feature['img'])
                <img src="{{ asset('images/' . $feature['img']) }}" alt="{{ $feature['title'] }}" class="img-fluid">
              @endif
            </div>
          </div>
        </div>
      @endforeach

      <!-- Final Call-to-Action -->
      <div class="col-12">
        <div class="card text-center p-5 mt-4">
          <div class="card-body">
            <h4 class="fw-bold mb-3 text-success">Only Real, Motivated Buyers & Sellers</h4>
            <p class="mb-4">We filter out the noise. Join a marketplace built for serious business with zero distractions.</p>
            <a href="{{ route('user.login') }}" class="btn btn-primary">Get Started Today</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
