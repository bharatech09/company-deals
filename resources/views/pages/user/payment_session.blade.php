@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container py-5 text-center">
        <h2 class="mb-4">Redirecting to Cashfree...</h2>
        <p>Please wait while we take you to the secure payment gateway.</p>
    </div>
</section>

<!-- ✅ Load Cashfree SDK -->
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cashfree = new Cashfree({ mode: "sandbox" }); // change to "production" when live

        cashfree.checkout({
            paymentSessionId: "{{ $paymentSessionId }}",
            redirectTarget: "_self"  // open in same tab
        }).catch((e) => {
            alert("Something went wrong while redirecting to Cashfree: " + e.message);
        });
    });
</script>
@endsection
