<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Company;
use App\Models\Buyer;
use App\Models\NocTrademark;
use App\Models\Assignment;
use App\Rules\MaxDigits;
use App\Rules\NoEmailNoMobile;
use App\Http\Controllers\Utils\GeneralUtils;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;



class SellerController extends Controller
{
    public function addAssignment()
    {
        return view('pages.user.seller_addassignment');
    }
    public function seller_assignments()
    {
        $arrAssignment = Assignment::seller_assignments('inactive');
        return view('pages.user.seller_assignment', compact('arrAssignment'));
    }
    public function saveAssignment(Request $request)
    {

        $validated = $request->validate([
            'category' => ['required'],
            'subject' => 'required',
            'description' => ['required', new NoEmailNoMobile()],
            'deal_price' => ['required', 'numeric'],
            'deal_price_unit' => 'required',
            'assignment_pricing_type' => 'required',
        ]);
        $validated['deal_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('deal_price'), $request->input('deal_price_unit'));
        $validated['urn'] = uniqid();
        $validated['is_active'] = 'inactive';
        $validated['user_id'] = \Auth::guard('user')->id();
        Assignment::create($validated);
        return redirect()->route("user.seller.assignments")->with('status', 'Your assignment has been saved successfully.');
    }
    public function editAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        return view('pages.user.seller_editassignment', compact('assignment'));
    }
    public function UpdateAssignment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $validated = $request->validate([
            'category' => ['required'],
            'subject' => 'required',
            'description' => ['required', new NoEmailNoMobile()],
            'deal_price' => ['required', 'numeric'],
            'deal_price_unit' => 'required',
            'assignment_pricing_type' => 'required',

        ]);
        $validated['deal_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('deal_price'), $request->input('deal_price_unit'));
        $assignment->update($validated);
        return redirect()->route("user.seller.assignments")->with('status', 'Your assignment has been updated successfully.');
    }

    public function editNocTrademark($id)
    {
        $trademark = NocTrademark::findOrFail($id);
        return view('pages.user.seller_editnoctrademark', compact('trademark'));
    }

    public function UpdateNocTrademark(Request $request, $id)
    {
        $trademark = NocTrademark::findOrFail($id);
        $validated = $request->validate(
            [
                'wordmark' => ['required', new MaxDigits(7)],
                'application_no' => ['required', 'max_digits:9999999'],
                'class_no' => ['required'],
                'proprietor' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
                'description' => ['required', new NoEmailNoMobile()],

                'status' => 'required',
                'ask_price' => ['required', 'numeric', 'max:9999'],
                'ask_price_unit' => 'required',

            ],
            [
                'proprietor.regex' => 'Proprietor should contain letters and spaces.',
            ]
        );
        $validated['valid_upto'] = $request->input('valid_upto');
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        $trademark->update($validated);
        return redirect()->route("user.seller.noctrademark")->with('status', 'Your data for NOC trademark has been updated successfully.');
    }

    public function editProperty($id)
    {
        $property = Property::findOrFail($id);
        return view('pages.user.seller_editproperty', compact('property'));
    }
    public function closedealProperty($id, $buyer_id)
    {
        $property = Property::findOrFail($id);
        $seller = \Auth::guard('user')->user();

        $deal_close_amount = intval($seller->amount_deal_closed) + intval($property->ask_price_amount);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);
        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($property->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);


        $property->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your property is closed successfully.');
    }

    public function closedealAssignment($id, $buyer_id)
    {
        $assignment = Assignment::findOrFail($id);
        $seller = \Auth::guard('user')->user();
        $deal_close_amount = intval($seller->amount_deal_closed) + intval($assignment->deal_price);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);

        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($assignment->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);
        $assignment->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your assignment is closed successfully.');
    }
    public function closedealNoc($id, $buyer_id)
    {
        $trademark = NocTrademark::findOrFail($id);
        $seller = \Auth::guard('user')->user();
        $deal_close_amount = intval($seller->amount_deal_closed) + intval($trademark->ask_price_amount);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);
        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($trademark->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);
        $trademark->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your Trademark is closed successfully.');
    }

    public function updateproperty(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $validated = $request->validate([
            'state' => 'required',
            'pincode' => ['required', 'digits:6'],
            'address' => 'required',
            'space' => ['required', 'numeric', 'max:9999'],
            'type' => 'required',
            'ask_price' => ['required', 'numeric', 'max:9999'],
            'ask_price_unit' => 'required',

        ]);
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        $property->update($validated);
        return redirect()->route("user.seller.propertylist")->with('status', 'Your property has been updated successfully.');
    }
    public function saveNocTrademark(Request $request)
    {

        $validated = $request->validate(
            [
                'wordmark' => ['required', new MaxDigits(7)],
                'application_no' => ['required', 'max_digits:9999999'],
                'class_no' => ['required'],
                'proprietor' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
                'description' => ['required', new NoEmailNoMobile()],
                'status' => 'required',
                'ask_price' => ['required', 'numeric', 'max:9999'],
                'ask_price_unit' => 'required',

            ],
            [
                'proprietor.regex' => 'Proprietor should contain letters and spaces.',
            ]
        );

        $validated['valid_upto'] = $request->input('valid_upto');
        $validated['urn'] = uniqid();
        $validated['is_active'] = 'inactive';
        $validated['user_id'] = \Auth::guard('user')->id();
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        NocTrademark::create($validated);
        return redirect()->route("user.seller.noctrademark")->with('status', 'Your trademark has been saved successfully.');
    }
    public function seller_addtrademark()
    {
        return view('pages.user.seller_addtrademark');
    }
    public function seller_noctrademark()
    {
        $arrTrademark = NocTrademark::seller_noctrademark('inactive');
        return view('pages.user.seller_noctrademark', compact('arrTrademark'));
    }
    public function addProperty()
    {
        return view('pages.user.seller_addprpperty');
    }



    public function seller_dashboard()
    {
        $activeCompanyArr = array();
        $dealClosedCompanyArr = array();
        $activePropertyArr = array();
        $dealClosedPropertyArr = array();
        $activeTrademarkArr = array();
        $dealClosedTrademarkArr = array();
        $activeAssignmentArr = array();
        $dealClosedAssignmentArr = array();
        $activeCompanyArr = Company::seller_companies("active", false, true);
        $activePropertyArr = Property::seller_properties('active', );
         $activeTrademarkArr = NocTrademark::seller_noctrademark('active');
         $activeAssignmentArr = Assignment::seller_assignments('active');

        // deal closed section
        $dealClosedCompanyArr = Company::seller_companies("all", true);
        $dealClosedPropertyArr = Property::seller_properties("all", true);
        $dealClosedTrademarkArr = NocTrademark::seller_noctrademark("all", true);
        $dealClosedAssignmentArr = Assignment::seller_assignments('all', true);

        return view('pages.user.seller_dashboard', compact('activePropertyArr', 'activeTrademarkArr', 'activeCompanyArr', 'activeAssignmentArr', 'dealClosedCompanyArr', 'dealClosedPropertyArr', 'dealClosedTrademarkArr', 'dealClosedAssignmentArr'));
    }
    public function seller_properties()
    {

        $arrPrperty = Property::seller_properties('inactive');
        return view('pages.user.seller_propertylist', compact('arrPrperty'));
    }

    public function saveProperty(Request $request)
    {

        $validated = $request->validate([
            'state' => 'required',
            'pincode' => ['required', 'digits:6'],
            'address' => 'required',
            'space' => ['required', 'numeric', 'max:9999'],
            'type' => 'required',
            'ask_price' => ['required', 'numeric', 'max:9999'],
            'ask_price_unit' => 'required',

        ]);
        $validated['urn'] = uniqid();
        $validated['status'] = 'inactive';
        $validated['user_id'] = \Auth::guard('user')->id();
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));

        Property::create($validated);

        return redirect()->route("user.seller.propertylist")->with('status', 'Your property has been saved successfully.');
    }

    public function showPaymentForm()
    {
        // You can pass amount or other details as needed
        return view('pages.user.seller_payment');
    }


    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $appId = config('services.cashfree.app_id');        // OR env('CASHFREE_APP_ID')
        $secretKey = config('services.cashfree.secret_key'); // OR env('CASHFREE_SECRET_KEY')


        $user = \Auth::guard('user')->user();
        $orderData = [
            "order_amount" => $request->amount,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => str_replace(['@', '.'], '_', $user->email),
                "customer_phone" => $user->phone,
                "customer_email" => $user->email,
                "customer_name" => $user->name ?? "Customer"
            ],
            "order_note" => "Seller Payment",
            "order_meta" => [
                "return_url" => route('user.seller.dashboard')
            ],
            "checkout_mode" => "REDIRECT"
        ];

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2025-01-01',
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.cashfree.com/pg/orders', $orderData);

        $body = $response->json();

        if (isset($body['payment_session_id'])) {
            return view('pages.user.payment_session', [
                'paymentSessionId' => $body['payment_session_id']
            ]);
        }

        return back()->with('error', $body['message'] ?? 'Cashfree session creation failed.');
    }


    public function paymentReturn(Request $request)
    {
        $orderId = $request->query('order_id');
        $orderToken = $request->query('order_token');
        $appId = config('services.cashfree.app_id');        // OR env('CASHFREE_APP_ID')
        $secretKey = config('services.cashfree.secret_key'); // OR env('CASHFREE_SECRET_KEY')
        if (!$orderId || !$orderToken) {
            return redirect()->route('user.seller.dashboard')->with('error', 'Invalid payment return.');
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://sandbox.cashfree.com/pg/orders/{$orderId}", [
                'headers' => [
                    'x-client-id' => $appId,
                    'x-client-secret' => $secretKey,
                    'x-api-version' => '2022-09-01',
                    'Content-Type' => 'application/json',
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            // Save/update payment in DB (assume Payment model exists)
            $payment = \App\Models\Payment::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'user_id' => auth()->id(),
                    'amount' => $body['order_amount'] ?? 0,
                    'status' => $body['order_status'] ?? 'unknown',
                    'payment_method' => $body['payment_method'] ?? null,
                    'transaction_id' => $body['payment_id'] ?? null,
                    'notes' => json_encode($body),
                ]
            );
            if (($body['order_status'] ?? '') === 'PAID') {
                return redirect()->route('user.seller.payment.history')->with('status', 'Payment successful!');
            } else {
                return redirect()->route('user.seller.payment.history')->with('error', 'Payment not successful.');
            }
        } catch (\Exception $e) {
            return redirect()->route('user.seller.payment.history')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    public function paymentHistory(Request $request)
    {
        $query = \App\Models\Payment::where('user_id', auth()->id());
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        $payments = $query->orderBy('created_at', 'desc')->get();
        return view('pages.user.seller_payment_history', compact('payments'));
    }






    // for company 
    public function initiateCompanyPayment($company_id)
    {
        $company = Company::findOrFail($company_id);
        $user = \Auth::guard('user')->user();

        $appId = config('services.cashfree.app_id');        // OR env('CASHFREE_APP_ID')
        $secretKey = config('services.cashfree.secret_key'); // OR env('CASHFREE_SECRET_KEY')


        $orderData = [
            "order_amount" => 100,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => str_replace(['@', '.'], '_', $user->email),
                "customer_email" => $user->email,
                "customer_phone" => $user->phone,
                "customer_name" => $user->name
            ],
            "order_note" => "Company Payment: {$company->name}",
            "order_meta" => [
                "return_url" => route('user.seller.company.payment.return', ['company_id' => $company->id])
            ],
            "checkout_mode" => "REDIRECT"
        ];

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2025-01-01',
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.cashfree.com/pg/orders', $orderData);

        $body = $response->json();

        if (isset($body['payment_session_id'])) {
            return view('pages.seller.company.payment_session', [
                'paymentSessionId' => $body['payment_session_id']
            ]);
        }

        return back()->with('error', $body['message'] ?? 'Cashfree payment session creation failed.');
    }

    public function paymentSuccess(Request $request, $company_id)
    {
        // return $company_id;

        $user = \Auth::guard('user')->user(); // or admin if needed

        $serviceType = 'seller_company';
        $serviceId = $company_id;
        $amount = 100; // fallback ₹100
        $startDate = now();
        $endDate = now()->addMonth();

        $transactionId = $request->input('order_id'); // comes from return_url

        // 1. Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'service_start_date' => $startDate,
            'service_end_date' => $endDate,
            'service_type' => $serviceType,
            'service_id' => $serviceId,
            'status' => 'paid',
            'payment_method' => 'Online',
            'payment_from' => 'seller',
            'payment_type' => 'Online',
            'transaction_id' => $transactionId,
        ]);

        // 2. Update related service
        if ($serviceType === 'seller_company') {
            $company = Company::findOrFail($serviceId);
            $company->update([
                'status' => 'active',
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('user.seller.dashboard')->with('success', 'Payment successful for your company listing.');
        } elseif ($serviceType === 'buyer_company') {
            $buyerCompany = \DB::table('buyer_company')->where('id', $serviceId)->first();
            if ($buyerCompany) {
                \DB::table('buyer_company')->where('id', $serviceId)->update([
                    'is_active' => 'active'
                ]);

                return redirect()->route('user.seller.dashboard')
                    ->with('success', 'Payment successful. Buyer company activated.');
            }
        }

















        // return   $company = Company::findOrFail($company_id);
        // $company->status = 'active'; // or mark payment received
        // $company->save();

        // return redirect()->route('user.seller.dashboard')->with('success', 'Payment successful for your company listing.');
    }

    // for property payment
    public function initiatePropertyPayment($property_id)
    {
        $property = Property::findOrFail($property_id);
        $user = \Auth::guard('user')->user();

        $appId = config('services.cashfree.app_id');        // OR env('CASHFREE_APP_ID')
        $secretKey = config('services.cashfree.secret_key'); // OR env('CASHFREE_SECRET_KEY')

        $orderData = [
            "order_amount" => 100,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => str_replace(['@', '.'], '_', $user->email),
                "customer_email" => $user->email,
                "customer_phone" => $user->phone,
                "customer_name" => $user->name
            ],
            "order_note" => "Property Payment: {$property->urn}",
            "order_meta" => [
                "return_url" => route('user.seller.property.payment.return', ['property_id' => $property->id])
            ],
            "checkout_mode" => "REDIRECT"
        ];

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2025-01-01',
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.cashfree.com/pg/orders', $orderData);

        $body = $response->json();

        if (isset($body['payment_session_id'])) {
            return view('pages.seller.property.payment_session', [
                'paymentSessionId' => $body['payment_session_id']
            ]);
        }

        return back()->with('error', $body['message'] ?? 'Cashfree payment session creation failed.');
    }

    public function propertyPaymentSuccess(Request $request, $property_id)
    {
        $user = \Auth::guard('user')->user();

        $serviceType = 'seller_property';
        $serviceId = $property_id;
        $amount = 100; // ₹100 for property payment
        $startDate = now();
        $endDate = now()->addMonth();

        $transactionId = $request->input('order_id'); // comes from return_url

        // 1. Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'service_start_date' => $startDate,
            'service_end_date' => $endDate,
            'service_type' => $serviceType,
            'service_id' => $serviceId,
            'status' => 'paid',
            'payment_method' => 'Online',
            'payment_from' => 'seller',
            'payment_type' => 'Online',
            'transaction_id' => $transactionId,
        ]);

        // 2. Update related service
        if ($serviceType === 'seller_property') {
            $property = Property::findOrFail($serviceId);
            $property->update([
                'status' => 'active',
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('user.seller.dashboard')->with('success', 'Payment successful for your property listing.');
        }

        return redirect()->route('user.seller.dashboard')->with('error', 'Payment processing failed.');
    }

    // for trademark payment
    public function initiateTrademarkPayment($trademark_id)
    {
        $trademark = NocTrademark::findOrFail($trademark_id);
        $user = \Auth::guard('user')->user();

        $appId = config('services.cashfree.app_id');        // OR env('CASHFREE_APP_ID')
        $secretKey = config('services.cashfree.secret_key'); // OR env('CASHFREE_SECRET_KEY')

        $orderData = [
            "order_amount" => 100,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => str_replace(['@', '.'], '_', $user->email),
                "customer_email" => $user->email,
                "customer_phone" => $user->phone,
                "customer_name" => $user->name
            ],
            "order_note" => "Trademark Payment: {$trademark->wordmark}",
            "order_meta" => [
                "return_url" => route('user.seller.trademark.payment.return', ['trademark_id' => $trademark->id])
            ],
            "checkout_mode" => "REDIRECT"
        ];

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2025-01-01',
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.cashfree.com/pg/orders', $orderData);

        $body = $response->json();

        if (isset($body['payment_session_id'])) {
            return view('pages.seller.trademark.payment_session', [
                'paymentSessionId' => $body['payment_session_id']
            ]);
        }

        return back()->with('error', $body['message'] ?? 'Cashfree payment session creation failed.');
    }

    public function trademarkPaymentSuccess(Request $request, $trademark_id)
    {
        $user = \Auth::guard('user')->user();

        $serviceType = 'seller_trademark';
        $serviceId = $trademark_id;
        $amount = 100; // ₹100 for trademark payment
        $startDate = now();
        $endDate = now()->addMonth();

        $transactionId = $request->input('order_id'); // comes from return_url

        // 1. Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'service_start_date' => $startDate,
            'service_end_date' => $endDate,
            'service_type' => $serviceType,
            'service_id' => $serviceId,
            'status' => 'paid',
            'payment_method' => 'Online',
            'payment_from' => 'seller',
            'payment_type' => 'Online',
            'transaction_id' => $transactionId,
        ]);

        // 2. Update related service
        if ($serviceType === 'seller_trademark') {
            $trademark = NocTrademark::findOrFail($serviceId);
            $trademark->update([
                'is_active' => 'active',
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('user.seller.dashboard')->with('success', 'Payment successful for your trademark listing.');
        }

        return redirect()->route('user.seller.dashboard')->with('error', 'Payment processing failed.');
    }

    // for assignment payment
    public function initiateAssignmentPayment($assignment_id)
    {
        $assignment = Assignment::findOrFail($assignment_id);
        $user = \Auth::guard('user')->user();

        $appId = config('services.cashfree.app_id');        // OR env('CASHFREE_APP_ID')
        $secretKey = config('services.cashfree.secret_key'); // OR env('CASHFREE_SECRET_KEY')

        $orderData = [
            "order_amount" => 100,
            "order_currency" => "INR",
            "customer_details" => [
                "customer_id" => str_replace(['@', '.'], '_', $user->email),
                "customer_email" => $user->email,
                "customer_phone" => $user->phone,
                "customer_name" => $user->name
            ],
            "order_note" => "Assignment Payment: {$assignment->subject}",
            "order_meta" => [
                "return_url" => route('user.seller.assignment.payment.return', ['assignment_id' => $assignment->id])
            ],
            "checkout_mode" => "REDIRECT"
        ];

        $response = Http::withHeaders([
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2025-01-01',
            'Content-Type' => 'application/json',
        ])->post('https://sandbox.cashfree.com/pg/orders', $orderData);

        $body = $response->json();

        if (isset($body['payment_session_id'])) {
            return view('pages.seller.assignment.payment_session', [
                'paymentSessionId' => $body['payment_session_id']
            ]);
        }

        return back()->with('error', $body['message'] ?? 'Cashfree payment session creation failed.');
    }

    public function assignmentPaymentSuccess(Request $request, $assignment_id)
    {
        $user = \Auth::guard('user')->user();

        $serviceType = 'seller_assignment';
        $serviceId = $assignment_id;
        $amount = 100; // ₹100 for assignment payment
        $startDate = now();
        $endDate = now()->addMonth();

        $transactionId = $request->input('order_id'); // comes from return_url

        // 1. Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'service_start_date' => $startDate,
            'service_end_date' => $endDate,
            'service_type' => $serviceType,
            'service_id' => $serviceId,
            'status' => 'paid',
            'payment_method' => 'Online',
            'payment_from' => 'seller',
            'payment_type' => 'Online',
            'transaction_id' => $transactionId,
        ]);

        // 2. Update related service
        if ($serviceType === 'seller_assignment') {
            $assignment = Assignment::findOrFail($serviceId);
            $assignment->update([
                'is_active' => 'active',
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('user.seller.dashboard')->with('success', 'Payment successful for your assignment listing.');
        }

        return redirect()->route('user.seller.dashboard')->with('error', 'Payment processing failed.');
    }
}
