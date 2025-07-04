<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\TrademarkController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Models\Admin;
use App\Http\Controllers\MessageController;


Route::get('/admin', [AdminLoginController::class, 'showLoginForm'])->name('admin.login1');
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);

Route::get('/admin/forgot-password', [AdminLoginController::class, 'showForgotPasswordForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminLoginController::class, 'sendResetLink'])->name('admin.password.email');

Route::get('/admin/reset-password/{token}', [AdminLoginController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/admin/reset-password', [AdminLoginController::class, 'resetPassword'])->name('admin.password.update');

Route::middleware('adminloggedin')->group(function () {
  // banner  home page
  Route::get('admin/dashboard', [AdminLoginController::class, 'dashboard'])->name('admin.dashboard');
  Route::get('admin/banner/create', [AdminLoginController::class, 'bannercreate'])->name('admin.banner.create');
  Route::post('admin/banner/store', [AdminLoginController::class, 'bannerstore'])->name('admin.banner.store');
  Route::delete('/admin/banner/{id}', [AdminLoginController::class, 'bannerdestroy'])->name('admin.banner.destroy');


  // end banner page 
  Route::get('admin/home', [AdminLoginController::class, 'homepage'])->name('admin.homepage');
  Route::get('admin/about/{slug}', [AdminController::class, 'about'])->name('admin.about');
  Route::put('/admin/about', [AdminController::class, 'aboutupdate'])->name('admin.about.update');

  Route::get('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

  Route::get('/admin/change-password', [AdminLoginController::class, 'showChangePasswordForm'])->name('admin.change-password.form');
  Route::post('/admin/change-password', [AdminLoginController::class, 'changePassword'])->name('admin.change-password');

  //######## Admin Management ########//
  Route::get('/admin/adminlist', [AdminController::class, 'adminlist'])->name('admin.adminlist');
  Route::get('/admin/adminadd', [AdminController::class, 'create'])->name('admin.adminadd');
  Route::get('/admin/{id}/adminedit', [AdminController::class, 'edit'])->name('admin.adminedit');
  Route::get('/admin/{id}/admindelete', [AdminController::class, 'delete'])->name('admin.admindelete');
  Route::post('/admin/adminsave', [AdminController::class, 'store'])->name('admin.adminsave');
  Route::put('/admin/{id}/adminupdate', [AdminController::class, 'update'])->name('admin.adminupdate');

  // User management
  Route::get('/admin/userlist', [UserController::class, 'userlist'])->name('admin.userlist');
  Route::get('/admin/{id}/useredit', [UserController::class, 'edit'])->name('admin.useredit');
  Route::get('/admin/{id}/userdelete', [UserController::class, 'delete'])->name('admin.userdelete');

  Route::put('/admin/{id}/userupdate', [UserController::class, 'update'])->name('admin.userupdate');

  Route::get('/admin/{id}/verifyemail', [UserController::class, 'verifyemail'])->name('admin.user.verify_email');
  Route::get('/admin/{id}/verifyphone', [UserController::class, 'verifyphone'])->name('admin.user.verify_phone');
  // Property management
  Route::get('/admin/propertylist', [PropertyController::class, 'propertylist'])->name('admin.propertylist');
  Route::get('/admin/property/{service_id}/{service_type}/payment', [PropertyController::class, 'paymentform'])->name('admin.property.payment');
  Route::put('/admin/property/paymentsave', [PropertyController::class, 'paymentsave'])->name('admin.property.paymentsave');
  Route::get('/admin/property/{id}/detail', [PropertyController::class, 'propertydetail'])->name('admin.property.detail');
  Route::get('/admin/property/{id}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('admin.property.togglefeatured');
  Route::post('/admin/property/{id}/toggle-approval', [PropertyController::class, 'toggleApproval'])->name('admin.property.toggleApproval');
Route::delete('/admin/property/{id}', [PropertyController::class, 'destroy'])->name('admin.property.destroy');


  // NOC Trademark management
  Route::get('/admin/trademarklist', [TrademarkController::class, 'trademarklist'])->name('admin.trademarklist');
  //  Route::get('/admin/trademark/{id}/payment', [TrademarkController::class, 'paymentform'])->name('admin.trademark.payment');
  Route::get('/admin/trademark/{service_id}/{service_type}/payment', [TrademarkController::class, 'paymentform'])->name('admin.trademark.payment');
  Route::put('/admin/trademark/paymentsave', [TrademarkController::class, 'paymentsave'])->name('admin.trademark.paymentsave');
  Route::get('/admin/trademark/{id}/detail', [TrademarkController::class, 'trademarkdetail'])->name('admin.trademark.detail');
  Route::get('/admin/trademark/{id}/toggle-featured', [TrademarkController::class, 'toggleFeatured'])->name('admin.trademark.togglefeatured');
  Route::post('/admin/trademark/{id}/toggle-approval', [TrademarkController::class, 'toggleApproval'])->name('admin.trademark.toggleApproval');
Route::delete('/admin/trademark/{id}', [TrademarkController::class, 'destroy'])->name('admin.trademark.destroy');

  //Company management
  Route::get('/admin/companylist', [CompanyController::class, 'companylist'])->name('admin.companylist');
  Route::get('/admin/company/{service_id}/{service_type}/payment', [CompanyController::class, 'paymentform'])->name('admin.company.payment');
  Route::put('/admin/company/paymentsave', [CompanyController::class, 'paymentsave'])->name('admin.company.paymentsave');
  Route::get('/admin/company/{id}/detail', [CompanyController::class, 'companydetail'])->name('admin.company.detail');
  Route::get('/admin/company/{id}/toggle-featured', [CompanyController::class, 'toggleFeatured'])->name('admin.company.togglefeatured');
  Route::delete('/admin/company/{id}', [CompanyController::class, 'destroy'])->name('admin.company.destroy');

  //Assignment management
  Route::get('/admin/assignmentlist', [AssignmentController::class, 'assignmentlist'])->name('admin.assignmentlist');
  Route::get('/admin/assignment/{service_id}/{service_type}/payment', [AssignmentController::class, 'paymentform'])->name('admin.assignment.payment');
  Route::put('/admin/assignment/paymentsave', [AssignmentController::class, 'paymentsave'])->name('admin.assignment.paymentsave');
  Route::get('/admin/assignment/{id}/detail', [AssignmentController::class, 'assignmentdetail'])->name('admin.assignment.detail');
  Route::get('/admin/assignment/{id}/toggle-featured', [AssignmentController::class, 'toggleFeatured'])->name('admin.assignment.togglefeatured');
Route::delete('/admin/assignment/{id}', [AssignmentController::class, 'destroy'])->name('admin.assignment.destroy');

  Route::post('/admin/assignment/{id}/toggle-approval', [AssignmentController::class, 'toggleApproval'])->name('admin.assignment.toggleApproval');

  //Testimonial management
  Route::get('/admin/testimonial/list', [TestimonialController::class, 'index'])->name('admin.testimonial.list');
  Route::get('/admin/testimonial/add', [TestimonialController::class, 'create'])->name('admin.testimonial.add');
  Route::post('/admin/testimonial/save', [TestimonialController::class, 'store'])->name('testimonial.store.admin');
  Route::get('/admin/testimonial/{id}/edit', [TestimonialController::class, 'edit'])->name('admin.testimonial.edit');
  Route::post('/admin/testimonial/update', [TestimonialController::class, 'update'])->name('admin.testimonial.update');
  Route::get('/admin/testimonial/{id}/delete', [TestimonialController::class, 'delete'])->name('admin.testimonial.delete');

  //Announcement management
  Route::get('/admin/announcement/list', [AnnouncementController::class, 'index'])->name('admin.announcement.list');
  Route::get('/admin/announcement/add', [AnnouncementController::class, 'create'])->name('admin.announcement.add');
  Route::post('/admin/announcement.save', [AnnouncementController::class, 'store'])->name('admin.announcement.save');
  Route::get('/admin/announcement/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcement.edit');
  Route::post('/admin/announcement/update', [AnnouncementController::class, 'update'])->name('admin.announcement.update');
  Route::get('/admin/announcement/{id}/delete', [AnnouncementController::class, 'delete'])->name('admin.announcement.delete');


  ######## Message Management ########
  Route::get('/admin/messages', [MessageController::class, 'index'])->name('pages.messages.list');
  Route::get('/admin/messages/add', [MessageController::class, 'add'])->name('pages.messages.add');
  Route::post('/admin/messages', [MessageController::class, 'store'])->name('pages.messages.store');
  Route::get('/admin/messages/{id}/edit', [MessageController::class, 'edit'])->name('pages.messages.edit');
  Route::put('/admin/messages/{id}', [MessageController::class, 'update'])->name('pages.messages.update');
  Route::delete('/admin/messages/{id}', [MessageController::class, 'destroy'])->name('pages.messages.destroy');
});
