<?php
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
require __DIR__ . '/includes/admin.php';
require __DIR__ . '/includes/user.php';

Route::get('/', function () {
   return view('welcome');
});
Route::get('/', [IndexController::class, 'index'])->name('frontend.home');
Route::get('/companies', [IndexController::class, 'companylist'])->name('frontend.companies');
Route::get('/companies/detail/{id}', [IndexController::class, 'companydetail'])->name('company.detail');
Route::get('/properties', [IndexController::class, 'propertylist'])->name('frontend.properties');
Route::get('/treademarks', [IndexController::class, 'treademarklist'])->name('frontend.treademark');
Route::get('/assignments', [IndexController::class, 'assignmentlist'])->name('frontend.assignments');
Route::get('/company-detail/{id}', [IndexController::class, 'show'])->name('company.view');
Route::post('/share-card/upload', [IndexController::class, 'upload'])->name('share.card.upload');


Route::get("/privacy-policy", function () {
   $aboutContent = DB::table('pages')->where('slug', 'privacy')->value('content');

   return View::make("pages.privacy_policy", compact('aboutContent'));
})->name('frontend.privacy_policy');
Route::get("/terms-and-conditions", function () {
   $aboutContent = DB::table('pages')->where('slug', 'term')->value('content');

   return View::make("pages.terms_and_conditions", compact('aboutContent'));
})->name('frontend.terms_and_conditions');

Route::get("/why-us", function () {
   return View::make("pages.why_us");
})->name('frontend.why_us');

Route::get("/about-us", function () {
   $aboutContent = DB::table('pages')->where('slug', 'about')->value('content');
   return View::make("pages.about_us", compact('aboutContent'));
})->name('frontend.about_us');
Route::post('/contact-submit', [IndexController::class, 'contact_submit'])->name('contact.submit');

