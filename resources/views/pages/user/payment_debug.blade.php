@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="dashboard-details">
                    <header>
                        <h2>Payment Debug</h2>
                    </header>
                    
                    <div class="mb-4">
                        <h4>Test Payment Processing</h4>
                        <form id="testPaymentForm">
                            @csrf
                            <div class="mb-3">
                                <label for="order_id" class="form-label">Order ID</label>
                                <input type="text" class="form-control" id="order_id" name="order_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="property">Property</option>
                                    <option value="company">Company</option>
                                    <option value="trademark">Trademark</option>
                                    <option value="assignment">Assignment</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="item_id" class="form-label">Item ID</label>
                                <input type="number" class="form-control" id="item_id" name="item_id" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Test Payment Processing</button>
                        </form>
                    </div>
                    
                    <div id="result" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('testPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("user.buyer.pay.test") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerHTML = `
            <div class="alert alert-${data.success ? 'success' : 'danger'}">
                <h5>Result:</h5>
                <pre>${JSON.stringify(data, null, 2)}</pre>
            </div>
        `;
    })
    .catch(error => {
        document.getElementById('result').innerHTML = `
            <div class="alert alert-danger">
                <h5>Error:</h5>
                <pre>${error.message}</pre>
            </div>
        `;
    });
});
</script>
@endsection 