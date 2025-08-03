<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\NocTrademark;
use App\Models\Company;
use App\Models\Assignment;
use App\Models\Testimonial;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function index()
    {
        $dashBoardData = array();

        $dashBoardData['no_users'] = \DB::table('users')->where([['email_verified', 1]])->count('*');
        $companies = \DB::table('companies')
            ->where('status', 'active')
            ->where('deal_closed', 1)

            ->count();

        // for counting actice companies
        $active_companies = \DB::table('companies')
            ->where('status', 'active')
            ->where('deal_closed', 0)
            ->count();

        //  get total count 

        $properties = \DB::table('properties')
            ->where('status', 'active')
            ->where('deal_closed', 1)
            ->count();


        // for counting actice properties
        $active_properties = \DB::table('properties')
            ->where('status', 'active')
            ->where('deal_closed', 0)
            ->count();

        // assginments
        $assignments = \DB::table('assignments')
            ->where('is_active', 'active')
            ->where('deal_closed', 1)
            ->count();

        // for counting actice assignments  
        $active_assignments = \DB::table('assignments')
            ->where('is_active', 'active')
            ->where('deal_closed', 0)
            ->count();



        // trademarks
        $trademarks = \DB::table('noc_trademarks')
            ->where('is_active', 'active')
            ->where('deal_closed', 1)
            ->count();

        // for counting actice trademarks
        $active_trademarks = \DB::table('noc_trademarks')
            ->where('is_active', 'active')
            ->where('deal_closed', 0)
            ->count();


        $dashBoardData['no_deal_closed'] = $companies + $assignments + $trademarks + $properties;

        $dashBoardData['no_company'] = $active_companies + $active_properties + $active_assignments + $active_trademarks;



        $amount_deal_closed = \DB::table('users')->sum('amount_deal_closed');
        $dashBoardData['amount_deal_closed'] = $amount_deal_closed / 1000;

        $dashBoardData['featured_company'] = Company::where('home_featured', 1)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('deal_closed', 0)
                    ->orWhereNull('deal_closed');
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        $dashBoardData['featured_property'] = Property::where([['home_featured', 1], ['status', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['featured_nocTrademark'] = NocTrademark::where([['home_featured', 1], ['is_active', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['featured_assignment'] = Assignment::where([['home_featured', 1], ['is_active', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['testimonial'] = Testimonial::where([['status', 'active']])->orderBy('updated_at', 'desc')->get();
        $dashBoardData['announcement'] = Announcement::where([['status', 'active']])->orderBy('updated_at', 'desc')->limit(3)->get();

        $banners = Banner::all();
        return view('pages.home', compact('dashBoardData', 'banners'));
    }

    public function assignmentlist()
    {
        $assignments = Assignment::where('is_active', 'active')->whereNotIn('deal_closed', [1])->orderBy('updated_at', 'desc')->get();
        return view('pages.assignment', compact('assignments'));
    }

    public function propertylist()
    {
        $properties = Property::where('status', 'active')->where('deal_closed', 0)->orderBy('updated_at', 'desc')->get();
        return view('pages.propertylist', compact('properties'));
    }
    public function treademarklist()
    {
        $nocTrademarks = NocTrademark::where('is_active', 'active')->whereNotIn('deal_closed', [1])->orderBy('updated_at', 'desc')->get();
        return view('pages.trademarklist', compact('nocTrademarks'));
    }
    public function companylist()
    {
        $companys = \DB::table('companies')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('deal_closed', 0)
                    ->orWhereNull('deal_closed');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.companylist', compact('companys'));
    }

    public function companydetail(Request $request)
    {
        $companys = \DB::table('companies')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('deal_closed', 0)
                    ->orWhereNull('deal_closed');
            })
            ->where('id',$request->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.companylist', compact('companys'));
    }


    public function show($id)
    {
        $companys = \DB::table('companies')
            ->where('id', $id)
            ->get(); // Only one record

        if (!$companys) {
            abort(404); // Not Found
        }

        return view('pages.companylist', compact('companys'));
    }


    public function contact_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        // Send email
        $to = config('mail.contact_us.mail_to');
        Mail::to($to)->send(new ContactMail($request->all()));
        return response()->json(['success' => 'Your message has been sent successfully!']);
    }

    public function upload(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|integer',
        ]);

        $companyId = $data['company_id'];

        // Clean base64 string
        // $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
        // $image = str_replace(' ', '+', $image);

        // $filename = 'company_' . $companyId . '_' . time() . '.png';
        // $savePath = public_path('shared_cards/' . $filename);

        // Ensure the folder exists
        // if (!file_exists(public_path('shared_cards'))) {
        //     mkdir(public_path('shared_cards'), 0777, true);
        // }

        // Save the file
        // file_put_contents($savePath, base64_decode($image));

        // $url = asset('shared_cards/' . $filename);

        return response()->json([
            'success' => true,
            'company_id' => url('companies/detail/'.$companyId),
        ]);
    }
}
