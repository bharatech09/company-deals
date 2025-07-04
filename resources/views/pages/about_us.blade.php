@extends('layout.master')
@section('content')

<style>
  .section-bg {
    background: linear-gradient(to right, #ecfdf5, #f0fdfa);
    padding: 60px 0;
  }

  .heading {
    font-size: 2.5rem;
    font-weight: 700;
    color: #39c074;
    margin-bottom: 20px;
  }

  .subheading {
    font-size: 1.1rem;
    color: #475569;
    line-height: 1.8;
  }

  .about-card {
    background: #ffffff;
    padding: 40px;
    border-radius: 18px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.07);
  }
  .form-floating>.form-control, .form-floating>.form-control:focus{
    padding-top: 30px !important;
  }

  .contact-form-card {
    background: #ffffff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    margin: auto;
  }


  .form-floating>.form-control,
  .form-floating>.form-control:focus {
    padding: 1rem;
    height: auto;
    border-radius: 10px;
    border: 1px solid #ddd;
  }

  .form-floating>label {
    padding: 1rem 0.75rem;
  }

  .btn-submit {
    background-color: #39c074;
    border: none;
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease-in-out;
  }

  .btn-submit:hover {
    background-color: #0d9488;
  }

  .contact-info p {
    margin-bottom: 8px;
    font-weight: 500;
  }

  @media (max-width: 767px) {
    .heading {
      font-size: 2rem;
    }

    .about-card,
    .contact-form-card {
      padding: 25px;
    }
  }
</style>

<section class="section-bg">
  <div class="container">
    <div class="row g-5 align-items-start">
      <!-- About Us Content -->
      <div class="col-lg-12">
        <div class="about-card">
          <h2 class="heading">About Us</h2>

          {!! $aboutContent !!}


          <div class="d-flex justify-content-center mt-5">
            <div class="contact-form-card" style="max-width: 800px; width: 100%;">
              <h2 class="heading mb-4 text-center">Send Us a Message</h2>
              <form id="contactForm" method="POST" action="#">
                @csrf
                <div class="form-floating mb-4">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" value="{{ old('name') }}" required>
                  <label for="name">Your Name *</label>
                </div>

                <div class="form-floating mb-4">
                  <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" value="{{ old('email') }}" required>
                  <label for="email">Your Email *</label>
                </div>

                <div class="form-floating mb-4">
                  <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" value="{{ old('subject') }}" required>
                  <label for="subject">Subject *</label>
                </div>

                <div class="form-floating mb-4">
                  <textarea name="message" class="form-control" placeholder="Your Message" id="message" style="height: 120px;" required>{{ old('message') }}</textarea>
                  <label for="message">Your Message *</label>
                </div>

                <div id="responseMessage" class="mb-3"></div>

                <div class="text-end">
                  <button type="submit" class="btn-submit">Send Message</button>
                </div>
              </form>
            </div>
          </div>

        </div>



      </div>

      <!-- Contact Form -->
      <div class="col-lg-6">

      </div>
    </div>
  </div>
</section>

@push('plugin-scripts')
<script>
  $(document).ready(function() {
    $('#contactForm').submit(function(event) {
      event.preventDefault();
      $('#responseMessage').html('<div class="text-info">Sending your message...</div>');

      $.ajax({
        url: "{{ route('contact.submit') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
          $('#responseMessage').html('<div class="alert alert-success">' + response.success + '</div>');
          $('#contactForm')[0].reset();
        },
        error: function(xhr) {
          let errors = xhr.responseJSON.errors;
          let errorMessage = '<div class="alert alert-danger"><ul>';
          $.each(errors, function(key, value) {
            errorMessage += '<li>' + value + '</li>';
          });
          errorMessage += '</ul></div>';
          $('#responseMessage').html(errorMessage);
        }
      });
    });
  });
</script>
@endpush

@endsection