<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Auth::guard('user')->check()) {
            return redirect()->route('user.login')->with('status', 'Please login as seller.');
        }elseif(\Auth::guard('user')->check() && !(\Session::get('role') == 'seller') ){
            
              return  redirect()->route('user.login',['as'=>'seller'])->with('status', 'Please login as seller.');

        }elseif(\Auth::guard('user')->check()){
            $user = \Auth::guard('user')->user();
            if($user->email_verified != 1 && $user->phone_verified != 1){
                \Auth::guard('user')->logout();
                \Session::forget('role');
            return redirect()->route('user.verfy.email_phone.form',$user->id)->with('status', 'Verify your email');
            }
            
        }
        return $next($request);
    }
}
