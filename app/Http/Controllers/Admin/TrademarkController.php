<?php

namespace App\Http\Controllers\Admin;

use App\Models\NocTrademark;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrademarkController extends Controller
{
    public function toggleFeatured($id)
    {
        NocTrademark::query()->update(['home_featured' => false]);
        NocTrademark::where('id', $id)->update(['home_featured' => true]);
        return redirect()->route('admin.trademark.detail', $id)->with('message', 'Featured set successfully.');
    }

    public function toggleApproval($id)
    {
        $property = NocTrademark::findOrFail($id);
        $property->approved = !$property->approved; // Toggle the value
        $property->save();

        $status = $property->approved ? 'approved' : 'disapproved';
        return redirect()->back()->with('message', "Noc Trademark has been $status.");
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
    public function trademarkdetail($id)
    {
        $trademark = NocTrademark::findOrFail($id);
        $user = $trademark->user;
        $paymentsObj = $trademark->allPayment();
        $payments = array();
        foreach ($paymentsObj as $key => $payment) {
            $payments[] = $this->getPaymentDetail($payment);
        }

        $intrestedBuyers = $trademark->buyers;
        $intrestedBuyersArr = array();
        foreach ($intrestedBuyers as $key => $user) {
            $buyerPaymentsObj = Payment::getAllPayment($user->pivot->id, 'buyer_trademark');
            $buyerPayment = array();
            foreach ($buyerPaymentsObj as $key => $payment) {
                $buyerPayment[] = $this->getPaymentDetail($payment);
            }
            $tempArr = array(
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => $user->email_verified,
                'phone' => $user->phone,
                'phone_verified' => $user->phone_verified,
                'pivot_buyer_id' => $user->pivot->id,
                'payments' => $buyerPayment,
            );
            $intrestedBuyersArr[] = $tempArr;
        }

        return view('admin.pages.trademark.detail', compact('trademark', 'user', 'payments', 'intrestedBuyersArr'));
    }
    public function trademarklist()
    {

        $nocTrademarks = NocTrademark::sortable()->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pages.trademark.list', compact('nocTrademarks'));
    }
    public function paymentform($service_id, $service_type)
    {
        return view('admin.pages.trademark.payment', compact('service_id', 'service_type'));
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
        if ($request->input('service_type') == 'seller_trademark') {
            $trademark = NocTrademark::findOrFail($request->input('service_id'));
            $trademarkData = array('is_active' => 'active', 'payment_id' => $paymentObj->id);
            $trademark->update($trademarkData);
            return redirect()->route('admin.trademarklist')->with('message', 'Payment done successfully.');
        } elseif ($request->input('service_type') == 'buyer_trademark') {
            $buyerTrademarkRow = \DB::table('buyer_noc_trademark')->where('id', $request->input('service_id'))->first();
            if ($buyerTrademarkRow) {

                \DB::table('buyer_noc_trademark')->where('id', $request->input('service_id'))->update(['is_active' => 'active']);
                return redirect()->route('admin.trademark.detail', $buyerTrademarkRow->noc_trademark_id)->with('message', 'Payment done successfully.');
            }
        }
    }

    public function destroy($id)
    {
        $trademark = NocTrademark::findOrFail($id);
        $trademark->delete();

        return redirect()->back()->with('message', 'Trademark deleted successfully.');
    }
}
