@extends('layout.master')
@section('content')
<style>
  .policy-container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
  }

  .policy-container h2 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 30px;
  }

  .policy-container strong {
    color: #34495e;
  }

  .policy-container p {
    margin-bottom: 15px;
    line-height: 1.7;
    color: #555;
  }

  .mainlist>li {
    margin-top: 25px;
  }

  .mainlist>li>strong {
    font-size: 18px;
    color: #2d3436;
  }

  .mainlist ol,
  .mainlist ul {
    margin-left: 40px;
    margin-top: 10px;
  }

  ul.circleli,
  ul.discli {
    margin-left: 40px;
    margin-top: 10px;
  }

  ul.circleli li,
  ul.discli li {
    margin-bottom: 8px;
  }

  .highlight-box {
    background: #f9f9f9;
    padding: 15px 20px;
    border-left: 4px solid #3498db;
    margin-bottom: 20px;
    border-radius: 6px;
  }

  .contact-box {
    background: #f1f1f1;
    padding: 15px 20px;
    border-left: 4px solid #27ae60;
    border-radius: 6px;
    margin-top: 20px;
  }

  a {
    color: #2980b9;
  }

  @media (max-width: 768px) {
    .policy-container {
      padding: 25px 15px;
    }
  }
</style>

<section class="dashboard-wrap">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
         {!! $aboutContent !!}
      </div>
    </div>
  </div>
</section>
@endsection