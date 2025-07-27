<?php

use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CompanyController;


Route::get('/user/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/user/login', [UserLoginController::class, 'login']);
Route::get('/user/email/verify/{id}/{hash}', [UserLoginController::class, 'verify_email'])->name('user.email.verification.verify');
Route::get('/user/verfy/emailphone/{id}/form', [UserLoginController::class, 'verify_email_phone'])->name('user.verfy.email_phone.form');
Route::get('/user/{id}/send_verify_email', [UserLoginController::class, 'send_verify_email'])->name('user.send_verify_email');


Route::get('/user/register', [UserLoginController::class, 'showRegistrationForm'])->name('user.register');
Route::post('/user/register', [UserLoginController::class, 'register']);

Route::get('/user/forgot-password', [UserLoginController::class, 'showForgotPasswordForm'])->name('user.password.request');
Route::post('/user/forgot-password', [UserLoginController::class, 'sendResetLink'])->name('user.password.email');
Route::get('/user/reset-password/{token}', [UserLoginController::class, 'showResetPasswordForm'])->name('user.password.reset');
Route::post('/user/reset-password', [UserLoginController::class, 'resetPassword'])->name('user.password.update');

Route::get('user/logout', [UserLoginController::class, 'logout'])->name('user.logout');

Route::get('/user/change-password', [UserLoginController::class, 'showChangePasswordForm'])->name('user.change-password.form');
Route::post('/user/change-password', [UserLoginController::class, 'changePassword'])->name('user.change-password');

Route::get('/user/buyer/message', [BuyerController::class, 'message'])->name('user.buyer.messaage');

// Authrized access
Route::middleware('sellerloggedin')->group(function () {
	Route::get('/user/seller/propertylist', [SellerController::class, 'seller_properties'])->name('user.seller.propertylist');

	Route::get('/user/seller/dashboard', [SellerController::class, 'seller_dashboard'])->name('user.seller.dashboard');

	Route::get('/user/seller/addproperty', [SellerController::class, 'addProperty'])->name('user.seller.addproperty');
	Route::post('/user/seller/saveproperty', [SellerController::class, 'saveProperty'])->name('user.seller.saveproperty');
	Route::get('/user/seller/{id}/editproperty', [SellerController::class, 'editProperty'])->name('user.seller.editproperty');
	Route::get('/user/seller/{id}/{buyer_id}/closedeal', [SellerController::class, 'closedealProperty'])->name('user.seller.closedeal');
	// Assignments
	Route::get('/user/seller/addassignment', [SellerController::class, 'addAssignment'])->name('user.seller.addassignment');
	Route::post('/user/seller/saveassignment', [SellerController::class, 'saveAssignment'])->name('user.seller.saveassignment');
	Route::get('/user/seller/assignments', [SellerController::class, 'seller_assignments'])->name('user.seller.assignments');
	Route::get('/user/seller/{id}/assignment', [SellerController::class, 'editAssignment'])->name('user.seller.editassignment');
	Route::put('/user/seller/{id}/updateassignment', [SellerController::class, 'UpdateAssignment'])->name('user.seller.updateassignment');
	Route::get('/user/seller/{id}/{buyer_id}/assignment/closedeal', [SellerController::class, 'closedealAssignment'])->name('user.seller.assignment.closedeal');


	Route::put('/user/seller/{id}/updateproperty', [SellerController::class, 'updateProperty'])->name('user.seller.updateproperty');
	Route::get('/user/seller/noctrademark', [SellerController::class, 'seller_noctrademark'])->name('user.seller.noctrademark');
	Route::get('/user/seller/addtrademark', [SellerController::class, 'seller_addtrademark'])->name('user.seller.addtrademark');

	Route::post('/user/seller/savetrademark', [SellerController::class, 'saveNocTrademark'])->name('user.seller.savetrademark');
	Route::get('/user/seller/{id}/noctrademark', [SellerController::class, 'editNocTrademark'])->name('user.seller.editnoctrademark');
	Route::put('/user/seller/{id}/updatenoctrademark', [SellerController::class, 'UpdateNocTrademark'])->name('user.seller.updatnoctrademark');
	Route::get('/user/seller/{id}/{buyer_id}/closedealnoc', [SellerController::class, 'closedealNoc'])->name('user.seller.closedealnoc');
	Route::get('/user/seller/company/pay/{company_id}', [SellerController::class, 'initiateCompanyPayment'])->name('user.seller.company.payment');
	Route::get('/user/seller/company/payment/return/{company_id}', [SellerController::class, 'paymentSuccess'])->name('user.seller.company.payment.return');

	// Property payment routes
	Route::get('/user/seller/property/pay/{property_id}', [SellerController::class, 'initiatePropertyPayment'])->name('user.seller.property.payment');
	Route::get('/user/seller/property/payment/return/{property_id}', [SellerController::class, 'propertyPaymentSuccess'])->name('user.seller.property.payment.return');

	// Trademark payment routes
	Route::get('/user/seller/trademark/pay/{trademark_id}', [SellerController::class, 'initiateTrademarkPayment'])->name('user.seller.trademark.payment');
	Route::get('/user/seller/trademark/payment/return/{trademark_id}', [SellerController::class, 'trademarkPaymentSuccess'])->name('user.seller.trademark.payment.return');

	// Assignment payment routes
	Route::get('/user/seller/assignment/pay/{assignment_id}', [SellerController::class, 'initiateAssignmentPayment'])->name('user.seller.assignment.payment');
	Route::get('/user/seller/assignment/payment/return/{assignment_id}', [SellerController::class, 'assignmentPaymentSuccess'])->name('user.seller.assignment.payment.return');


	Route::get('/user/seller/companylist', [CompanyController::class, 'seller_companylist'])->name('user.seller.companylist');
Route::get('/user/seller/{id}/{buyer_id}/closedealcompany', [CompanyController::class, 'closedeal'])->name('user.seller.closedealcompany');

	/***add edit company start****/
	Route::get('/user/seller/companyform/showstep1', [CompanyController::class, 'showstep1'])->name('user.seller.companyform.showstep1');
	Route::post('/user/seller/companyform/additionalstep1', [CompanyController::class, 'additionalstep1'])->name('user.seller.companyform.additionalstep1');
	Route::PUT('/user/seller/companyform/savestep1', [CompanyController::class, 'savestep1'])->name('user.seller.companyform.savestep1');
	Route::post('/user/seller/companyform/additionalstep2', [CompanyController::class, 'additionalstep2'])->name('user.seller.companyform.additionalstep2');
	Route::get('/user/seller/companyform/showstep2', [CompanyController::class, 'showstep2'])->name('user.seller.companyform.showstep2');
	Route::PUT('/user/seller/companyform/savestep2', [CompanyController::class, 'savestep2'])->name('user.seller.companyform.savestep2');
	Route::get('/user/seller/companyform/showstep3', [CompanyController::class, 'showstep3'])->name('user.seller.companyform.showstep3');
	Route::PUT('/user/seller/companyform/savestep3', [CompanyController::class, 'savestep3'])->name('user.seller.companyform.savestep3');
	Route::get('/user/seller/companyform/showstep4', [CompanyController::class, 'showstep4'])->name('user.seller.companyform.showstep4');
	Route::PUT('/user/seller/companyform/savestep4', [CompanyController::class, 'savestep4'])->name('user.seller.companyform.savestep4');
	Route::post('/user/seller/companyform/upload_document', [CompanyController::class, 'uploadDocument'])->name('user.seller.companyform.upload_document');
	Route::get('/user/seller/companyform/{company_id}/{field_name}/download_document', [CompanyController::class, 'download_document'])->name('user.seller.companyform.download_document');
	Route::get('/user/seller/companyform/{company_id}/{field_name}/delete_document', [CompanyController::class, 'delete_document'])->name('user.seller.companyform.delete_document');
	Route::post('/user/seller/companyform/check_name', [CompanyController::class, 'check_name'])->name('user.seller.companyform.check_name');


	/***add edit company end****/
	Route::get('/user/seller/payment', [SellerController::class, 'showPaymentForm'])->name('user.seller.payment');
	Route::post('/user/seller/payment/process', [SellerController::class, 'processPayment'])->name('user.seller.payment.process');
	Route::get('/user/seller/payment/return', [SellerController::class, 'paymentReturn'])->name('user.seller.payment.return');
	Route::get('/user/seller/payment/history', [SellerController::class, 'paymentHistory'])->name('user.seller.payment.history');
});

Route::middleware('buyerloggedin')->group(function () {
	Route::get('/user/buyer/dashboard', [BuyerController::class, 'dashboard'])->name('user.buyer.dashboard');
	Route::get('/user/buyer/property', [BuyerController::class, 'property_filter'])->name('user.buyer.property');
	Route::post('/user/buyer/property-filter-ajax', [BuyerController::class, 'property_filter_ajax'])->name('user.buyer.property_filter_ajax');

	Route::get('/user/buyer/noctrademark', [BuyerController::class, 'noctrademark_filter'])->name('user.buyer.noctrademark');
	Route::post('/user/buyer/noctrademark-filter-ajax', [BuyerController::class, 'noctrademark_filter_ajax'])->name('user.buyer.noctrademark_filter_ajax');
	Route::get('/user/buyer/{id}/property/addtointerested', [BuyerController::class, 'property_addtointerested'])->name('user.buyer.property.addtointerested');
	Route::get('/user/buyer/{id}/property/removefrominterested', [BuyerController::class, 'property_remove_from_interested'])->name('user.buyer.property.removefrominterested');



	Route::get('/user/buyer/{id}/trademark/addtointerested', [BuyerController::class, 'trademark_addtointerested'])->name('user.buyer.trademark.addtointerested');
	Route::get('/user/buyer/{id}/trademark/removefrominterested', [BuyerController::class, 'trademark_remove_from_interested'])->name('user.buyer.trademark.removefrominterested');

	Route::get('/user/buyer/company', [BuyerController::class, 'company_filter'])->name('user.buyer.company');
	Route::post('/user/buyer/company-filter-ajax', [BuyerController::class, 'company_filter_ajax'])->name('user.buyer.company_filter_ajax');
	Route::get('/user/buyer/{id}/company/addtointerested', [BuyerController::class, 'company_addtointerested'])->name('user.buyer.company.addtointerested');
	Route::get('/user/buyer/{id}/company/removefrominterested', [BuyerController::class, 'company_remove_from_interested'])->name('user.buyer.company.removefrominterested');

	Route::get('/user/buyer/assignment', [BuyerController::class, 'assignment_filter'])->name('user.buyer.assignment');
	Route::post('/user/buyer/assignment-filter-ajax', [BuyerController::class, 'assignment_filter_ajax'])->name('user.buyer.assignment_filter_ajax');
	Route::get('/user/buyer/{id}/assignment/addtointerested', [BuyerController::class, 'assignment_addtointerested'])->name('user.buyer.assignment.addtointerested');
	Route::get('/user/buyer/{id}/assignment/removefrominterested', [BuyerController::class, 'assignment_remove_from_interested'])->name('user.buyer.assignment.removefrominterested');

	// Buyer one-time payment to view seller details
	Route::get('/user/buyer/pay', [BuyerController::class, 'showSellerDetailsPaymentForm'])->name('user.buyer.pay');
	Route::post('/user/buyer/pay/process', [BuyerController::class, 'processSellerDetailsPayment'])->name('user.buyer.pay.process');
	
	// Cashfree webhook for payment notifications
	Route::post('/user/buyer/pay/webhook', [BuyerController::class, 'cashfreeWebhook'])->name('user.buyer.pay.webhook');
	
	// Test route for manual payment processing (for debugging)
	Route::post('/user/buyer/pay/test', [BuyerController::class, 'testPaymentProcessing'])->name('user.buyer.pay.test');
	
	// Debug page for payment testing
	Route::get('/user/buyer/pay/debug', function() {
		return view('pages.user.payment_debug');
	})->name('user.buyer.pay.debug');
});

// Payment return route - outside middleware to handle authentication manually
Route::get('/user/buyer/pay/return', [BuyerController::class, 'sellerDetailsPaymentReturn'])->name('user.buyer.pay.return');
