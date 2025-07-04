<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;

class AssignmentController extends Controller
{
    public function toggleFeatured($id)
    {
        Assignment::query()->update(['home_featured' => false]);
        Assignment::where('id', $id)->update(['home_featured' => true]);
        return redirect()->route('admin.assignment.detail', $id )->with('message', 'Featured set successfully.');
    }


      public function toggleApproval($id)
    {
        $property = Assignment::findOrFail($id);
        $property->approved = !$property->approved; // Toggle the value
        $property->save();

        $status = $property->approved ? 'approved' : 'disapproved';
        return redirect()->back()->with('message', "Assignment has been $status.");
    }



    
    protected function getPaymentDetail($payment){
            $userdetail = "";
            if($payment->payment_from =='admin')
            {
                $userObj = Admin::findOrFail($payment->user_id);
                $userdetail = $userObj->name.' ('.$userObj->email.')';
            }elseif($payment->payment_from =='user'){
                $userObj = User::findOrFail($payment->user_id);
                $userdetail = $userObj->name.' ('.$userObj->email.')';
            }
            $arrPayment = array(
                'user' => $userdetail,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'payment_from' => $payment->payment_from,
                'payment_from' => $payment->payment_from,
                'service_start_date' => date_format(date_create($payment->service_start_date),"d F Y"),
                'service_end_date' => date_format(date_create($payment->service_end_date),"d F Y"),
                'payment_date' => date_format(date_create($payment->created_at),"d F Y"),
            );
            return $arrPayment;

    }
    public function assignmentdetail($id)
    {
        $assignment = Assignment::findOrFail($id);
        $user = $assignment->user;
        $paymentsObj = $assignment->allPayment();
        $payments = array();
        foreach ($paymentsObj as $key => $payment)
        {
            $payments[] = $this->getPaymentDetail($payment);
        }
        
        $intrestedBuyers = $assignment->buyers;
        $intrestedBuyersArr = array();
        foreach($intrestedBuyers as $key => $user ){
            $buyerPaymentsObj = Payment::getAllPayment($user->pivot->id, 'buyer_trademark');
            $buyerPayment = array();
            foreach ($buyerPaymentsObj as $key => $payment)
            {
                $buyerPayment[] = $this->getPaymentDetail($payment);   
            }
            $tempArr = array(
                'name' =>$user->name,
                'email' =>$user->email,  
                'email_verified' =>$user->email_verified,
                'phone' =>$user->phone,
                'phone_verified' =>$user->phone_verified,
                'pivot_buyer_id' =>$user->pivot->id,
                'payments' =>$buyerPayment,
            );        
            $intrestedBuyersArr[] = $tempArr;
        }
        
        return view('admin.pages.assignment.detail', compact('assignment','user','payments','intrestedBuyersArr'));
    }
    public function assignmentlist()
    {
            $assignments = Assignment::sortable()->orderby('updated_at','desc')->paginate(4000);

        return view('admin.pages.assignment.list', compact('assignments'));
    }

    public function paymentform($service_id,$service_type)
    {
        return view('admin.pages.assignment.payment', compact('service_id','service_type'));
    }

    public function paymentsave(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'service_start_date' => 'required|date',
            'service_end_date' => 'required|date|after:service_start_date',
        ]);

        $validatedData['user_id'] =\Auth::guard('admin')->user()->id;
        $validatedData['service_type'] = $request->input('service_type');
        $validatedData['service_id'] = $request->input('service_id');
        $validatedData['status'] = 'paid';
        $validatedData['payment_method'] = 'admin';
        $validatedData['payment_from'] = 'admin';
        
        $paymentObj = Payment::create($validatedData);
        if($request->input('service_type') =='seller_assignment'){
            $assignment = Assignment::findOrFail($request->input('service_id'));
            $assignmentData = array('is_active'=>'active','payment_id'=> $paymentObj->id);
            $assignment->update($assignmentData);
            return redirect()->route('admin.assignmentlist')->with('message', 'Payment done successfully.');

        }elseif($request->input('service_type') =='buyer_assignment'){
            $buyerAssignmentRow = \DB::table('assignment_buyer')->where('id', $request->input('service_id'))->first();
            if($buyerAssignmentRow){

                \DB::table('assignment_buyer')->where('id',$request->input('service_id'))->update(['is_active' => 'active']);
                return redirect()->route('admin.assignment.detail', $buyerAssignmentRow->assignment_id )->with('message', 'Payment done successfully.');

            }
        }
    }

    public function destroy($id)
{
    $assignment = Assignment::findOrFail($id);
    $assignment->delete();

    return redirect()->back()->with('message', 'Assignment deleted successfully.');
}

}
