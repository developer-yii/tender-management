<?php

use App\Http\Controllers\AiToolController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TempleteController;
use App\Http\Controllers\TenderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'is_admin'])->group(function () {

    // company Details
    Route::prefix('company-details')->as('company-details.')->group(function () {
        Route::get('/', [CompanyProfileController::class, 'index'])->name('index');
        Route::post('/update', [CompanyProfileController::class, 'update'])->name('update');
        Route::post('/detail', [CompanyProfileController::class, 'detail'])->name('detail');
    });

    // tags
    Route::prefix('tag')->as('tag.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/get', [TagController::class, 'get'])->name('list');
        Route::post('/addupdate', [TagController::class, 'addupdate'])->name('addupdate');
        Route::post('/detail', [TagController::class, 'detail'])->name('detail');
        Route::post('/delete', [TagController::class, 'delete'])->name('delete');
    });

    // employees
    Route::prefix('employee')->as('employee.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::post('/addupdate', [EmployeeController::class, 'addupdate'])->name('addupdate');
        Route::get('/details/{id}', [EmployeeController::class, 'employeeDetails'])->name('details');
        Route::post('/detail', [EmployeeController::class, 'detail'])->name('detail');
    });

    // certificates
    Route::prefix('certificate')->as('certificate.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::post('/addupdate', [CertificateController::class, 'addupdate'])->name('addupdate');
        Route::get('/details/{id}', [CertificateController::class, 'certificateDetails'])->name('details');
        Route::post('/detail', [CertificateController::class, 'detail'])->name('detail');
        Route::post('/delete', [CertificateController::class, 'delete'])->name('delete');
    });

    // Tender Routes
    Route::prefix('tender')->as('tender.')->group(function () {
        Route::get('/', [TenderController::class, 'index'])->name('index');
        Route::get('/get', [TenderController::class, 'get'])->name('list');
        Route::get('/add-edit', [TenderController::class, 'addEdit'])->name('add');
        Route::post('/createupdate', [TenderController::class, 'createUpdate'])->name('createupdate');
        Route::post('/detail', [TenderController::class, 'detail'])->name('detail');
        Route::post('/delete', [TenderController::class, 'delete'])->name('delete');
        Route::get('/employee-tenders', [TenderController::class, 'getTenders'])->name('assign-tenders');
        Route::get('/start', [TenderController::class, 'start'])->name('start');
        Route::post('/documents', [TenderController::class, 'tenderDocuments'])->name('documents');
        Route::post('/merge-docx', [TenderController::class, 'mergeDocx'])->name('merge-docx');
        Route::get('/preview-docx', [TenderController::class, 'previewDocx'])->name('preview-docx');
        Route::post('/merge-pdf', [TenderController::class, 'mergePdf'])->name('merge-pdf');
        Route::get('/preview-pdf', [TenderController::class, 'previewPdf'])->name('preview-pdf');
    });

    // reference
    Route::prefix('reference')->as('reference.')->group(function () {
        Route::get('/', [ReferenceController::class, 'index'])->name('index');
        Route::get('/get', [ReferenceController::class, 'get'])->name('list');
        Route::post('/addupdate', [ReferenceController::class, 'addupdate'])->name('addupdate');
        Route::get('/details/{id}', [ReferenceController::class, 'referenceDetails'])->name('details');
        Route::post('/detail', [ReferenceController::class, 'detail'])->name('detail');
        Route::post('/delete', [ReferenceController::class, 'delete'])->name('delete');
    });

    // Tender
    Route::prefix('ai')->as('ai.')->group(function () {
        Route::get('/', [AiToolController::class, 'index'])->name('index');
        Route::get('/conversation', [AiToolController::class, 'conversation'])->name('conversation');
    });

    // Documents
    Route::prefix('document')->as('document.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/addupdate', [DocumentController::class, 'addupdate'])->name('addupdate');
        Route::get('/details/{id}', [DocumentController::class, 'documentDetails'])->name('details');
        Route::post('/detail', [DocumentController::class, 'detail'])->name('detail');
        Route::post('/delete', [DocumentController::class, 'delete'])->name('delete');

    });

    // Templetes
    Route::prefix('templete')->as('templete.')->group(function () {
        Route::get('/', [TempleteController::class, 'index'])->name('index');
        Route::post('/addupdate', [TempleteController::class, 'addupdate'])->name('addupdate');
        Route::get('/details/{id}', [TempleteController::class, 'templeteDetails'])->name('details');
        Route::post('/detail', [TempleteController::class, 'detail'])->name('detail');
        Route::post('/delete', [TempleteController::class, 'delete'])->name('delete');
    });

    // servers
    Route::prefix('server')->as('server.')->group(function () {
        Route::get('/', [ServerController::class, 'index'])->name('index');
        Route::get('/get', [ServerController::class, 'get'])->name('list');
        Route::post('/addupdate', [ServerController::class, 'addupdate'])->name('addupdate');
        Route::post('/detail', [ServerController::class, 'detail'])->name('detail');
    });

});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-tenders', [TenderController::class, 'index'])->name('employee.tenders');
    Route::get('/tender/details/{id}', [TenderController::class, 'tenderDetails'])->name('tender.details');
});