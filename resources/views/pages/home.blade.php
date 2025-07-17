@extends('layout.master')
@section('content')
  <section class="gbl py-5">
    <div class="container">
    <div class="row g-4">
      @php
    $cards = [
      [
      'title' => 'Buy or Sell a Company',
      'desc' => 'Looking to buy or sell a company? List or explore opportunities here.',
      'route' => route('frontend.companies')
      ],
      [
      'title' => 'Rent Out Your Property for GST or Company Registration',
      'desc' => 'Have a property available for Office use or GST registration? Rent it out here.',
      'route' => route('frontend.properties')
      ],
      [
      'title' => 'Issue No Objection Certificate of Your Trade Mark',
      'desc' => 'Want to allow someone to use your trademark? Issue or request a NOC.',
      'route' => route('frontend.treademark')
      ],
      [
      'title' => 'Outsource your Professional Assignment',
      'desc' => 'Need help with CA, CS, CMA, Legal, or Other Professional tasks? Outsource or find work here.',
      'route' => route('frontend.assignments')
      ],
    ];
    @endphp

      @foreach ($cards as $card)
      <div class="col-md-3 d-flex align-items-stretch">
      <div class="card premium-card shadow-lg border-0 w-100">
      <div class="card-body d-flex flex-column p-4">
      <article class="flex-grow-1">
        <h3 class="card-title mb-3 text-dark">{{ $card['title'] }}</h3>
        <p class="card-text text-secondary">{{ $card['desc'] }}</p>
      </article>
      <footer class="mt-4">
        <a href="{{ $card['route'] }}" class="btn parrot-btn w-100">View More</a>
      </footer>
      </div>
      </div>
      </div>
    @endforeach
    </div>
    </div>
  </section>

  <style>
    .premium-card {
    background: #fff;
    border-radius: 16px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .card-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #222;
    }

    .card-text {
    font-size: 0.95rem;
    line-height: 1.5;
    color: #555;
    }

    .parrot-btn {
    background-color: #22c55e;
    color: white;
    font-weight: 600;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: background-color 0.3s ease;
    }

    .parrot-btn:hover {
    background-color: #16a34a;
    color: white;
    }

    .featured-card {
    border-radius: 14px;
    transition: transform 0.3s ease;
    overflow: hidden;
    background: #fff;
    position: relative;
    }

    .featured-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.07);
    }

    .featured-card .card-title {
    color: #1e293b;
    font-size: 1.1rem;
    }

    .featured-card .badge {
    font-size: 0.75rem;
    border-radius: 50px;
    padding: 0.4em 0.8em;
    background-color: #22c55e;
    /* parrot green */
    }


    .featured-card {
    border-radius: 14px;
    transition: transform 0.3s ease;
    overflow: hidden;
    position: relative;
    background: #fff;
    }

    .featured-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
    }

    .featured-card .card-body {
    padding: 1rem 1.25rem;
    }

    .featured-card h5 {
    font-size: 1.15rem;
    }

    .featured-card hr {
    margin: 0.5rem 0 1rem;
    }
  </style>
  <section class="gbl py-5 bg-light">
    <div class="container">
    <div class="section-header text-center mb-5">
      <h2 class="display-6 fw-bold" style="color: #22c55e;">Featured Listings</h2>
      <p class="text-muted mb-0">Explore premium opportunities currently available on our platform</p>
    </div>

    <div class="row g-4">
      {{-- Featured Company --}}
      @if ($dashBoardData['featured_company']->count() > 0)
      @foreach($dashBoardData['featured_company'] as $company)

      <div class="col-md-6 col-lg-3 d-flex">
      <div class="card featured-card w-100 shadow-sm">
      <div class="badge bg-success text-white position-absolute top-0 end-0 m-3">Companies</div>
      <div class="card-body p-0">
      <div class="featured-companies-scroll" style="max-height: 800px; overflow-y: auto;">
      <div class="p-3 border-bottom">
      <img src="{{ asset('images/feature-img1.jpg') }}" class="card-img-top mb-2" alt="Company for sale">
      <h5 class="card-title fw-semibold">Company for Sale</h5>
      <hr>

      {{-- Featured Badge --}}
      <span class="badge bg-success position-absolute top-0 start-0 m-2 rounded-pill px-3 py-2 shadow-sm">
      Featured
      </span>
      <div class="mb-2">
      <strong class="text-success d-block">{{ $company->name }} {{ $company->name_prefix }}</strong>
      </div>
      <div class="mb-2">
      <strong class="text-dark d-block">Type of Entity:</strong>
      <span class="text-muted">{{ $company->type_of_entity }}</span>
      </div>
      <div class="mb-2">
      <strong class="text-dark d-block">ROC:</strong>
      <span class="text-muted">{{ $company->roc }}</span>
      </div>
      <div class="mb-2">
      <strong class="text-dark d-block">Incorporation Year:</strong>
      <span class="text-muted">{{ $company->year_of_incorporation }}</span>
      </div>
      <div class="mb-2">
      <strong class="text-dark d-block">Industry:</strong>
      <span class="text-muted">{{ $company->industry }}</span>
      </div>
      <div>
      <strong class="text-dark d-block">GST Available:</strong>
      <span class="text-muted">{{ $company->have_gst }}</span>
      </div>
      <a href="{{ route('company.detail', $company->id) }}">View Details</a>
      </div>
      </div>
      </div>
      </div>
      </div>
    @endforeach

    @endif

    </div>
    </div>
  </section>

  <section class="counters gbl-60">
    <div class="container">
    <ul class="counters_list">
      <li>
      <div class="counters_block">
        <span class="counts">{{$dashBoardData['no_company']}}</span>
        <span class="fact-symbol persent">+</span>
        <p>No. of Business Available</p>
      </div>
      </li>
      <li>
      <div class="counters_block">
        <span class="counts">{{$dashBoardData['no_users']}}</span>
        <span class="fact-symbol">+</span>
        <p>No. of users on board</p>
      </div>
      </li>
      <li>
      <div class="counters_block">
        <span class="counts">{{$dashBoardData['no_deal_closed']}}</span>
        <span class="fact-symbol plus">+</span>
        <p>No. of deals done</p>
      </div>
      </li>
      <li>
      <div class="counters_block">
        <span class="counts">{{$dashBoardData['amount_deal_closed']}}</span>
        <span class="fact-symbol">+</span>
        <p>Amount of deals done (in Thousands)</p>
      </div>
      </li>
    </ul>
    </div>
  </section>
  @if (count($dashBoardData['testimonial']) > 0)

    <section class="our-testimonials">
    <div class="container">
    <header class="common-header text-center with-underline">
      <h2>Our Testimonial</h2>
    </header>

    <div class="slideSet">
      @foreach ($dashBoardData['testimonial'] as $testimonial)
      <div class="slider">
      <div class="cards">
      <div class="card-testimonial">
      <figure><img src="{{asset('storage/' . $testimonial->client_image)}}" alt=""></figure>
      <article>
      <h3>{{$testimonial->client_name}}</h3>
      <span>{{$testimonial->heading}}</span>
      <p>{{$testimonial->description}}</p>
      </article>
      </div>
      </div>
      </div>
    @endforeach
    </div>
    </div>
    </section>
  @endif
  @if (count($dashBoardData['announcement']) > 0)
    <section class="latest-announce" style="background-image: url(images/announcement-bg.jpg);">
    <div class="container">
    <header class="common-header text-center with-underline">
      <h2>Latest Announcement</h2>
    </header>

    <div class="row">
      @foreach ($dashBoardData['announcement'] as $announcement)
      <div class="col-lg-4">
      <div class="cards">
      <div class="card-announcement">
      <h3>{{$announcement->title}}</h3>
      <small>{{date_format(date_create($announcement->announcement_date), "l, F d, Y")}}</small>
      <p>{{$announcement->description}}</p>
      </div>
      </div>
      </div>
    @endforeach
    </div>
    </div>
    </section>
  @endif
@endsection