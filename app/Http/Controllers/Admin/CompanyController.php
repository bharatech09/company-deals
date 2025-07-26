<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;

class CompanyController extends Controller
{
    public function toggleFeatured($id)
    {
        // Company::query()->update(['home_featured' => false]);
        Company::where('id', $id)->update(['home_featured' => true]);
        return redirect()->route('admin.company.detail', $id)->with('message', 'Featured set successfully.');
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
    public function companydetail($id)
    {
        $company = Company::findOrFail($id);
        $user = $company->user;
        $paymentsObj = $company->allPayment();
        $payments = array();

        foreach ($paymentsObj as $key => $payment) {
            $payments[] = $this->getPaymentDetail($payment);
        }
        $intrestedBuyers = $company->buyers;
        $intrestedBuyersArr = array();
        foreach ($intrestedBuyers as $key => $user) {
            $buyerPaymentsObj = Payment::getAllPayment($user->pivot->id, 'buyer_company');
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
        return view('admin.pages.company.detail', compact('company', 'user', 'payments', 'intrestedBuyersArr'));
    }

    public function companylist()
    {
        $companys = Company::with('user')->sortable()->orderby('updated_at', 'desc')->paginate(4000);
        return view('admin.pages.company.list', compact('companys'));
    }
    public function paymentform($service_id, $service_type)
    {
        return view('admin.pages.company.payment', compact('service_id', 'service_type'));
    }

    public function paymentsave(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'service_start_date' => 'required|date',
            'service_end_date' => 'required|date|after:service_start_date',
        ]);

        $validatedData['user_id'] = \Auth::guard('admin')->user()->id;
        // $validatedData['service_type'] = $request->input('service_type');
        $validatedData['service_type'] = 'seller_company';
        $validatedData['service_id'] = $request->input('service_id');
        $validatedData['status'] = 'paid';
        $validatedData['payment_method'] = 'admin';
        $validatedData['payment_from'] = 'admin';

        $paymentObj = Payment::create($validatedData);
        if ($request->input('service_type') == 'seller_company') {
            $company = Company::findOrFail($request->input('service_id'));
            $companyData = array('status' => 'active', 'payment_id' => $paymentObj->id);
            $company->update($companyData);
            return redirect()->route('admin.companylist')->with('message', 'Payment done successfully.');
        } elseif ($request->input('service_type') == 'buyer_company') {
            $buyerCompanyRow = \DB::table('buyer_company')->where('id', $request->input('service_id'))->first();
            if ($buyerCompanyRow) {

                \DB::table('buyer_company')->where('id', $request->input('service_id'))->update(['is_active' => 'active']);
                return redirect()->route('admin.company.detail', $buyerCompanyRow->company_id)->with('message', 'Payment done successfully.');
            }
        }
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->back()->with('message', 'Company deleted successfully.');
    }
}
