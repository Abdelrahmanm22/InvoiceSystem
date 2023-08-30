<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


////Routes for invoices/////////////////
Route::get('/invoices', [InvoiceController::class,'index'])->name('invoices');
Route::get('/Invoice_Paid', [InvoiceController::class,'Invoice_Paid'])->name('Invoice_Paid');
Route::get('/Invoice_unPaid', [InvoiceController::class,'Invoice_unPaid'])->name('Invoice_unPaid');
Route::get('/Invoice_Partial', [InvoiceController::class,'Invoice_Partial'])->name('Invoice_Partial');
Route::get('/addInvoices', [InvoiceController::class,'create'])->name('invoices.add');
Route::post('/postaddInvoices',[InvoiceController::class,'store'])->name('invoices.postadd');
Route::get('/updateInvoice/{id}', [InvoiceController::class,'edit'])->name('invoices.update');
Route::get('/changepayment/{id}', [InvoiceController::class,'changePayment'])->name('invoices.change.payment');
Route::get('/printInvoice/{id}', [InvoiceController::class,'printInvoice'])->name('invoices.printing');
Route::post('/postchangepayment', [InvoiceController::class,'postChangePayment'])->name('invoices.post.change.payment');
Route::post('/postupdateInvoices',[InvoiceController::class,'update'])->name('invoices.postupdate');
Route::post('/deleteInvoice',[InvoiceController::class,'destroy'])->name('invoices.delete');
Route::post('/archiveInvoice',[InvoiceController::class,'archive'])->name('invoices.archive');
Route::get('/export', [InvoiceController::class, 'export'])->name('export');
Route::get('/MarkAsRead_all',[InvoiceController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');
Route::get('/MarkAsRead/{id}/{notification}',[InvoiceController::class, 'MarkAsRead'])->name('MarkAsRead');
////Routes for invoices/////////////////


////Routes for archive/////////////////
Route::get('/Archive', [InvoiceArchiveController::class,'index'])->name('Archive');
Route::post('/restore',[InvoiceArchiveController::class,'restore'])->name('invoices.restore');
Route::post('/ArchiveDelete',[InvoiceArchiveController::class,'delete'])->name('Archive.delete');
////Routes for archive/////////////////


////Routes for invoicesDetails/////////////////
Route::get('/InvoicesDetails/{id}', [InvoiceDetailsController::class,'index'])->name('InvoicesDetails');
////Routes for invoicesDetails/////////////////



////Routes for invoicesAttachment/////////////////
Route::get('/showAttach/{invoice_number}/{file_name}', [InvoiceAttachmentsController::class,'index'])->name('showAttach');
Route::get('/download/{invoice_number}/{file_name}', [InvoiceAttachmentsController::class,'download'])->name('showAttach');
Route::post('/deleteAttach}', [InvoiceAttachmentsController::class,'destroy'])->name('deleteAttach');
Route::post('/addAttach}', [InvoiceAttachmentsController::class,'store'])->name('addAttach');
////Routes for invoicesAttachment/////////////////



////Routes for sections/////////////////
Route::get('/sections', [SectionController::class,'index'])->name('sections');
Route::post('/sectionsStore', [SectionController::class,'store'])->name('sections.store');
Route::post('/sectionsUpdate', [SectionController::class,'update'])->name('sections.update');
Route::post('/sectionsDestroy', [SectionController::class,'destroy'])->name('sections.destroy');
Route::get('/section/{id}', [SectionController::class,'getSectionProducts'])->name('section.products');
////Routes for sections/////////////////


////Routes for products/////////////////
Route::get('/products', [ProductController::class,'index'])->name('products');
Route::post('/productsStore', [ProductController::class,'store'])->name('products.store');
Route::post('/productsUpdate', [ProductController::class,'update'])->name('products.update');
Route::post('/productsDestroy', [ProductController::class,'destroy'])->name('products.destroy');
////Routes for products/////////////////

////Routes for Spatie Permission////////////////
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
////Routes for Spatie Permission////////////////



////Routes for Reports ////////////////
Route::get('/invoicesReport', [InvoicesReportController::class,'index'])->name('invoicesReport');
Route::post('/Search_invoices', [InvoicesReportController::class,'Search_invoices'])->name('Search_invoices');

Route::get('/customersReport', [CustomersReportController::class,'index'])->name('customersReport');
Route::post('/Search_customers', [CustomersReportController::class,'Search_customers'])->name('Search_customers');
////Routes for Reports ////////////////


Route::get('/{page}',[AdminController::class,'index']);


