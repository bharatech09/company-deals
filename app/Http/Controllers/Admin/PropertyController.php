<?php

namespace App\Http\Controllers\Admin;

use App\Models\Property;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function toggleFeatured($id)
    {
        Property::query()->update(['home_featured' => false]);
        Property::where('id', $id)->update(['home_featured' => true]);
        return redirect()->route('admin.property.detail', $id)->with('message', 'Featured set successfully.');
    }

    public function toggleApproval($id)
    {
        $property = Property::findOrFail($id);
        $property->approved = !$property->approved; // Toggle the value

        $status = $property->approved ? 'approved' : 'disapproved';

        // if ($status == 'approved') {
        //     $property->status = 'active';
        // } else {
        //     $property->status = 'inactive';
        // }
        $property->save();

        return redirect()->back()->with('message', "Property has been $status.");
    }

    protected function getPaymentDetail($payment)
    {
        $userdetail = "";
        if ($payment->payment_from == 'admin') {
            $userObj = Admin::findOrFail($payment->user_id);
            $userdetail = $userObj->name . ' (' . $userObj->email . ')';
        } elseif ($payment->payment_from == 'user') {
            $userObj = User::findOrFail($payment->user_id);
            $userdetail = $userObj->name . ' (' . $userObj->email . ')';
        }
        $arrPayment = array(
            'user' => $userdetail,
            'amount' => $payment->amount,
            'status' => $payment->status,
            'payment_from' => $payment->payment_from,
            'payment_from' => $payment->payment_from,
            'service_start_date' => date_format(date_create($payment->service_start_date), "d F Y"),
            'service_end_date' => date_format(date_create($payment->service_end_date), "d F Y"),
            'payment_date' => date_format(date_create($payment->created_at), "d F Y"),
        );
        return $arrPayment;
    }
    public function propertydetail($id)
    {
        $property = Property::findOrFail($id);
        $user = $property->user;
        $paymentsObj = $property->allPayment();
        $payments = array();

        foreach ($paymentsObj as $key => $payment) {
            $payments[] = $this->getPaymentDetail($payment);
        }
        $intrestedBuyers = $property->buyers;
        $intrestedBuyersArr = array();
        foreach ($intrestedBuyers as $key => $user) {
            $buyerPaymentsObj = Payment::getAllPayment($user->pivot->id, 'buyer_property');
            $buperPayment = array();
            foreach ($buyerPaymentsObj as $key => $payment) {
                $buperPayment[] = $this->getPaymentDetail($payment);
            }
            $tempArr = array(
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => $user->email_verified,
                'phone' => $user->phone,
                'phone_verified' => $user->phone_verified,
                'pivot_buyer_id' => $user->pivot->id,
                'payments' => $buperPayment,
            );

            $intrestedBuyersArr[] = $tempArr;
        }
        return view('admin.pages.property.detail', compact('property', 'user', 'payments', 'intrestedBuyersArr'));
    }
    public function propertylist()
    {
        $properties = Property::with('user')->sortable()->orderBy('created_at', 'desc')->paginate(4000); // Enable sorting + pagination

        return view('admin.pages.property.list', compact('properties'));
    }

    public function paymentform($service_id, $service_type)
    {
        return view('admin.pages.property.payment', compact('service_id', 'service_type'));
    }

    public function destroy($id)
    {
        Property::findOrFail($id)->delete();
        return back()->with('message', 'Property deleted successfully.');
    }


    public function paymentsave(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'service_start_date' => 'required|date',
            'service_end_date' => 'required|date|after:service_start_date',
        ]);

        $validatedData['user_id'] = \Auth::guard('admin')->user()->id;
        $validatedData['service_type'] = $request->input('service_type');
        $validatedData['service_id'] = $request->input('service_id');
        $validatedData['status'] = 'paid';
        $validatedData['payment_method'] = 'admin';
        $validatedData['payment_from'] = 'admin';

        $paymentObj = Payment::create($validatedData);
        if ($request->input('service_type') == 'seller_property') {
            $property = Property::findOrFail($request->input('service_id'));
            $propertyData = array('status' => 'active', 'payment_id' => $paymentObj->id);
            $property->update($propertyData);
            return redirect()->route('admin.propertylist')->with('message', 'Payment done successfully.');
        } elseif ($request->input('service_type') == 'buyer_property') {
            $buyerPropertyRow = \DB::table('buyer_property')->where('id', $request->input('service_id'))->first();
            if ($buyerPropertyRow) {

                \DB::table('buyer_property')->where('id', $request->input('service_id'))->update(['is_active' => 'active']);
                // die("Here...".$buyerPropertyRow->property_id);
                return redirect()->route('admin.property.detail', $buyerPropertyRow->property_id)->with('message', 'Payment done successfully.');
            }
        }
    }
}
