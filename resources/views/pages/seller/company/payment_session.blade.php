@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container py-5 text-center">
        <h2>Redirecting to Cashfree...</h2>
        <p>Please wait while we take you to payment gateway.</p>
    </div>
</section>

<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
    const cashfree = new Cashfree({ mode: "sandbox" });

    document.addEventListener("DOMContentLoaded", function () {
        cashfree.checkout({
            paymentSessionId: "{{ $paymentSessionId }}",
            redirectTarget: "_self"
        }).catch(function (e) {
            alert("Payment failed: " + e.message);
        });
    });
</script>
@endsection
