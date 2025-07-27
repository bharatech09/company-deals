<header class="gbl-header dashboard-header">
  <div class="container">
    <a class="brand-logo" href="{{ route('frontend.home') }}">
      <img class="" src="{{asset('images/logo.png')}}" alt="Logo">
    </a>

    <div class="menu-grid">
      <nav class="site-nav">
        <ul class="site-menu">
          <li class="site-menu"><a href="{{ route('frontend.home') }}">Home</a></li>
          <li class="sub-menu">
            <a href="javascript:void(0)">Service</a>

            <div class="dropdow-menu">
              <div class="inner">
                <ul>
                  <li><a href="{{ route('frontend.companies') }}">Buy or Sell a Company</a></li>
                  <li><a href="{{ route('frontend.properties') }}">Property renting for GST & Incorporation</a></li>
                  <li><a href="{{ route('frontend.treademark') }}">Trademark NOC Issuance</a></li>
                  <li><a href="{{ route('frontend.assignments') }}">Assignment Outsourcing</a></li>
                </ul>
              </div>
            </div>
          </li>
          <li class="site-menu"><a href="{{ route('frontend.why_us') }}">WHY US</a></li>
          
          <li class="site-menu"><a href="{{ route('frontend.about_us') }}">ABOUT US</a></li>
        </ul>
      </nav>
      <button class="navToggle">
        <span class="hamburger-menu">
          <span class="bar"></span>
        </span>
      </button>

      <!-- Mobile Nav Header -->
      <nav class="mobnav-grid">
        <a href="javascript:void(0)" class="mobnav-close navToggle">
          <img src="{{asset('images/cross-icon-w.png')}}" alt="cross-icon-white">
        </a>
        <ul>
          <li class=""><a href="{{ route('frontend.home') }}">Home</a></li>
          <li class=""><a href="{{ route('frontend.why_us')}}">WHY US</a></li>
          <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link">Service</a>
            <ul class="menu-info none">
              <li><a href="{{ route('frontend.companies') }}">Buy or Sell a Company</a></li>
              <li><a href="{{ route('frontend.properties') }}">Property renting for GST & Incorporation</a></li>
              <li><a href="{{ route('frontend.treademark') }}">Trademark NOC Issuance</a></li>
              <li><a href="{{ route('frontend.assignments') }}">Assignment Outsourcing</a></li>
            </ul>
          </li>
          <li class=""><a href="{{ route('frontend.about_us') }}">ABOUT US</a></li>
        </ul>
      </nav>
    </div>
    @if (session('role') == 'seller')
    <a href="{{ route('user.seller.dashboard') }}" style="width: 200px;" class="cta-primary with-shadow">Dashboard</a>
    @elseif (session('role') == 'buyer')
    <a href="{{ route('user.buyer.dashboard') }}" style="width: 200px;" class="cta-primary with-shadow">Dashboard</a>
    @else
    <a href="{{ route('user.login') }}" class="cta-primary with-shadow">Login</a>
    @endif

  </div>
</header>
@if (Route::is('frontend.home'))
@php
  $banners = \App\Models\Banner::get();
@endphp

<section class="banner-home">
  <div class="slideSet">
    @forelse ($banners as $banner)
      <div class="slider" style="background-image: url('{{ asset('uploads/banners/' . $banner->image) }}');">
        <div class="banner-home_text">
          <h1>{{ $banner->title }}</h1>
          <p>{{ $banner->short_description }}</p>
          @if(!empty($banner->button_link))
            <a href="{{ $banner->button_link }}" class="cta-primary">View More</a>
          @endif
        </div>
      </div>
    @empty
      <div class="slider" style="background-color: #eee;">
        <div class="banner-home_text">
          <h1>No Banners Found</h1>
          <p>Please add banners from the admin panel.</p>
        </div>
      </div>
    @endforelse
  </div>
</section>

@endif()
