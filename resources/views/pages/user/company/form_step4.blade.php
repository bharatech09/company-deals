@extends('layout.master')
@section('content')
@php
use App\Http\Controllers\Utils\GeneralUtils;
$selected_ask_price_unit = old('ask_price_unit_option',isset($companyData)? $companyData->ask_price_unit:'');
$ask_price_unit_option = GeneralUtils::get_select_option('ask_price_unit_option',$selected_ask_price_unit);
@endphp
<section class="dashboard-wrap">
    <div class="container">
        <div class="row">
            @include('layout.seller_nav')
            <div class="col-lg-8 col-xl-9">
                <div class="dashboard-details">
                    <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
                    <div class="form-wrap">
                        <!-- <header>
                            <h2>SUPPORTING DOCUMENTS (OPTIONAL)</h2>
                        </header> -->

                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('user.seller.companyform.savestep4')}}" id="companyFrm4" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="id" id="company_id" value="{{$companyData->id}}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                {{-- -
                                @foreach ($documents as $doc)
                                <div class="col-md-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">{{$doc['label']}}</legend>
                                <div class="upload">
                                    <p style="font-size: 12px;">Allowed file types: .pdf, .jpeg, .png, .xls, .doc &nbsp;|&nbsp; Max size: 2MB</p>
                                    @if ($doc['uploaded'] == 1)
                                    <a class="cta-primary" href="{{$doc['download_link']}}">Download</a>
                                    <button type="button" class="cta-primary document_delete" data-url="{{$doc['delete_link']}}">delete</button>
                                    @else
                                    <input id="{{$doc['field_name']}}" type="file" class="form-control file_input" name="{{$doc['field_name']}}">
                                    @endif
                                </div>
                                </fieldset>
                            </div>
                            @endforeach

                            --}}
                            <div class="col-md-12">
                                <fieldset class="scheduler-border pricewithunit">
                                    <legend class="scheduler-border">Ask price</legend>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="field">
                                                <input id="ask_price" type="number" class="form-control onlynumber fourdigit" name="ask_price" placeholder="Ask price" required="" value="{{old('ask_price',isset($companyData)? $companyData->ask_price:'')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <select id="ask_price_unit" class="form-select" name="ask_price_unit" required="">
                                                    <option value="">-Select-</option>
                                                    {!!$ask_price_unit_option!!}
                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Agree to Terms and Conditions</legend>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>
                                                <input type="checkbox" class="terms" id="term1">

                                                I agree that the platform fee paid to Companydeals is non-refundable, even if no deal happens. The fee is only for access to listings and sharing contacts, not for ensuring any deal or response. There is no guarantee of buyer interest, seller action, or a successful transaction.


                                            </label><br>

                                            <label>
                                                <input type="checkbox" class="terms" id="term2"> I am solely responsible for checking the authenticity and legality of all parties and documents involved. Companydeals will not be held liable for any loss, fraud, or failure by any party in the deal.
                                                Any issue found before or after the deal is entirely my responsibility.


                                            </label><br>

                                            <label>
                                                <input type="checkbox" class="terms" id="term3">I allow Companydeals to share my name, phone number, and email with the other party after payment. I accept that all communication and transactions are at my own risk. I will not hold Companydeals responsible for any dispute, miscommunication, or failed deal.
                                                <!-- <b>All the entries of Companies, Properties, Trademarks and Assignments (in Seller & buyer Section both) must reflect on top as and when edited.</b> -->

                                            </label><br>
                                            <p class="text-danger d-none">You must agree to all terms before submitting.</p>
                                        </div>
                                    </div>

                                </fieldset>

                                <div class="row">
                                    <div class="col-md-6">
                                        <a class="cta-primary" href="{{ route('user.seller.companyform.showstep3',['id' => $companyData->id])}}">Previous</a>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="cta-primary float-end" type="submit">Save</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('plugin-scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $('.upload').on('click', 'button.document_delete', function() {
            let wraperdev = $(this).parent("div.upload");
            let delete_url = $(this).data('url');
            $.ajax({
                url: delete_url, // Replace with your upload script
                type: "GET",
                contentType: false,
                processData: false,
                success: function(response) {
                    wraperdev.html(response.message);
                },
                error: function() {
                    wraperdev.html("Delete failed.");
                }
            });

        });
        $('.upload').on('change', 'input.file_input', function() {
            let file = this.files[0]; // Get selected file
            let fild_name = $(this).attr('name');
            let company_id = $('#company_id').val();
            let wraperdev = $(this).parent("div.upload");
            wraperdev.parent().find(".alert").remove();
            let success_msg = '<p class="alert alert-success">Upload successful!</p>';
            if (file) {
                let formData = new FormData();
                formData.append("file", file);
                formData.append("fild_name", fild_name);
                formData.append("company_id", company_id);
                formData.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    url: "{{ route('user.seller.companyform.upload_document') }}", // Replace with your upload script
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        wraperdev.html(response.message);
                    },
                    error: function(xhr) {
                        var errorMsg = "";
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMsg = errors.file ? errors.file[0] : 'Unknown error'
                        } else {
                            errorMsg = 'Something went wrong please try again!';
                        }
                        wraperdev.append("<p class='alert alert-danger'>" + errorMsg + "</p>");
                    }
                });
            }
        });

        $("#companyFrm4").on("submit", function(e) {
            if ($(".terms:checked").length < 3) {
                $(".text-danger").removeClass("d-none");
                e.preventDefault(); // Prevent form submission
            } else {
                $(".text-danger").addClass("d-none");
            }
        });
    });
</script>
@endpush
@endsection