<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\StoreFrontController;

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

// Auth Routes
// Route::get('index', [CustomAuthController::class, 'dashboard']); 
Route::get('/', [CustomAuthController::class, 'index'])->name('signin')->middleware('guest');
Route::get('/login', [CustomAuthController::class, 'index'])->name('login'); // Redirect for auth middleware
Route::post('custom-login', [CustomAuthController::class, 'customSignin'])->name('signin.custom'); 
Route::get('logout', [CustomAuthController::class, 'signOut'])->name('logout');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

// Session management routes
Route::get('/session/check', [App\Http\Controllers\SessionController::class, 'check'])->name('session.check');
Route::post('/session/ping', [App\Http\Controllers\SessionController::class, 'ping'])->name('session.ping');
// Route::get('register', [CustomAuthController::class, 'registration'])->name('register');
// Route::post('custom-register', [CustomAuthController::class, 'customRegister'])->name('register.custom');

Route::get('/index', [App\Http\Controllers\DashboardController::class, 'index'])->name('index')->middleware('auth');

Route::get('/product-list', [App\Http\Controllers\ProductController::class, 'index'])->name('product-list')->middleware('auth');
Route::get('/product-details/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('product-details')->middleware('auth');
Route::get('/edit-product/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit-product')->middleware('auth');
Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store')->middleware('auth');
Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update')->middleware('auth');
Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy')->middleware('auth');
Route::post('/products/{id}/status', [App\Http\Controllers\ProductController::class, 'updateStatus'])->name('products.status')->middleware('auth');

Route::get('/add-product', [App\Http\Controllers\ProductController::class, 'create'])->name('add-product')->middleware('auth');

Route::get('/expired-products', function () {
    return view('expired-products');
})->name('expired-products');

Route::get('/low-stocks', function () {
    return view('low-stocks');
})->name('low-stocks');

// Categories
Route::get('/category-list', [App\Http\Controllers\CategoryController::class, 'index'])->name('category-list')->middleware('auth');
Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store')->middleware('auth');
Route::get('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show')->middleware('auth');
Route::put('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update')->middleware('auth');
Route::delete('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('auth');
Route::post('/categories/{id}/toggle-status', [App\Http\Controllers\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status')->middleware('auth');

Route::get('/sub-categories', function () {
    return view('sub-categories');
})->name('sub-categories');

Route::get('/brand-list', function () {
    return view('brand-list');
})->name('brand-list');

Route::get('/units', function () {
    return view('units');
})->name('units');

Route::get('/varriant-attributes', function () {
    return view('varriant-attributes');
})->name('varriant-attributes');

Route::get('/warranty', function () {
    return view('warranty');
})->name('warranty');

Route::get('/barcode', function () {
    return view('barcode');
})->name('barcode');

Route::get('/qrcode', function () {
    return view('qrcode');
})->name('qrcode');

Route::get('/sales-dashboard', function () {
    return view('sales-dashboard');
})->name('sales-dashboard');

Route::get('/video-call', function () {
    return view('video-call');
})->name('video-call');

Route::get('/audio-call', function () {
    return view('audio-call');
})->name('audio-call');

Route::get('/call-history', function () {
    return view('call-history');
})->name('call-history');

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');

Route::get('/email', function () {
    return view('email');
})->name('email');

Route::get('/todo', function () {
    return view('todo');
})->name('todo');

Route::get('/notes', function () {
    return view('notes');
})->name('notes');

Route::get('/file-manager', function () {
    return view('file-manager');
})->name('file-manager');

Route::get('/file-shared', function () {
    return view('file-shared');
})->name('file-shared');

Route::get('/file-document', function () {
    return view('file-document');
})->name('file-document');

Route::get('/file-recent', function () {
    return view('file-recent');
})->name('file-recent');

Route::get('/file-favourites', function () {
    return view('file-favourites');
})->name('file-favourites');

Route::get('/file-archived', function () {
    return view('file-archived');
})->name('file-archived');

Route::get('/file-manager-deleted', function () {
    return view('file-manager-deleted');
})->name('file-manager-deleted');

Route::get('/chat', function () {                         
    return view('chat');
})->name('chat');     

Route::get('/manage-stocks', function () {                         
    return view('manage-stocks');
})->name('manage-stocks');      

Route::get('/stock-adjustment', function () {                         
    return view('stock-adjustment');
})->name('stock-adjustment');     

Route::get('/stock-transfer', function () {                         
    return view('stock-transfer');
})->name('stock-transfer'); 

Route::get('/purchase-list', [App\Http\Controllers\OrderController::class, 'index'])->name('purchase-list')->middleware('auth'); 

Route::get('/purchase-order-report', function () {                         
    return view('purchase-order-report');
})->name('purchase-order-report'); 

Route::get('/purchase-returns', function () {                         
    return view('purchase-returns');
})->name('purchase-returns'); 

// Expenses
Route::get('/expense-list', [App\Http\Controllers\ExpenseController::class, 'index'])->name('expense-list')->middleware('auth');
Route::post('/expenses', [App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store')->middleware('auth');
Route::get('/expenses/{id}', [App\Http\Controllers\ExpenseController::class, 'show'])->name('expenses.show')->middleware('auth');
Route::put('/expenses/{id}', [App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update')->middleware('auth');
Route::delete('/expenses/{id}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('auth');

// Expense Categories
Route::get('/expense-category', [App\Http\Controllers\ExpenseCategoryController::class, 'index'])->name('expense-category')->middleware('auth');
Route::post('/expense-categories', [App\Http\Controllers\ExpenseCategoryController::class, 'store'])->name('expense-categories.store')->middleware('auth');
Route::get('/expense-categories/{id}', [App\Http\Controllers\ExpenseCategoryController::class, 'show'])->name('expense-categories.show')->middleware('auth');
Route::put('/expense-categories/{id}', [App\Http\Controllers\ExpenseCategoryController::class, 'update'])->name('expense-categories.update')->middleware('auth');
Route::delete('/expense-categories/{id}', [App\Http\Controllers\ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy')->middleware('auth');
Route::get('/expense-categories-list', [App\Http\Controllers\ExpenseCategoryController::class, 'getCategories'])->name('expense-categories.list')->middleware('auth');     

Route::get('/purchase-report', function () {                         
    return view('purchase-report');
})->name('purchase-report');     

Route::get('/employees-grid', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees-grid')->middleware('auth');
Route::post('/employees', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store')->middleware('auth');
Route::put('/employees/{id}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update')->middleware('auth');
Route::delete('/employees/{id}', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('auth');     

Route::get('/edit-employee', function () {                         
    return view('edit-employee');
})->name('edit-employee');     

// Department Routes
Route::get('/department-grid', [App\Http\Controllers\DepartmentController::class, 'index'])->name('department-grid')->middleware('auth');
Route::get('/departments/data', [App\Http\Controllers\DepartmentController::class, 'getDepartmentData'])->name('departments.data')->middleware('auth');
Route::post('/departments', [App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store')->middleware('auth');
Route::get('/departments/{id}', [App\Http\Controllers\DepartmentController::class, 'show'])->name('departments.show')->middleware('auth');
Route::put('/departments/{id}', [App\Http\Controllers\DepartmentController::class, 'update'])->name('departments.update')->middleware('auth');
Route::delete('/departments/{id}', [App\Http\Controllers\DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('auth');

// Designation Routes
Route::get('/designation', [App\Http\Controllers\DesignationController::class, 'index'])->name('designation')->middleware('auth');
Route::get('/designations/data', [App\Http\Controllers\DesignationController::class, 'getDesignationData'])->name('designations.data')->middleware('auth');
Route::post('/designations', [App\Http\Controllers\DesignationController::class, 'store'])->name('designations.store')->middleware('auth');
Route::get('/designations/{id}', [App\Http\Controllers\DesignationController::class, 'show'])->name('designations.show')->middleware('auth');
Route::put('/designations/{id}', [App\Http\Controllers\DesignationController::class, 'update'])->name('designations.update')->middleware('auth');
Route::delete('/designations/{id}', [App\Http\Controllers\DesignationController::class, 'destroy'])->name('designations.destroy')->middleware('auth');

// Shift Routes
Route::get('/shift', [App\Http\Controllers\ShiftController::class, 'index'])->name('shift')->middleware('auth');
Route::get('/shifts/data', [App\Http\Controllers\ShiftController::class, 'getShiftData'])->name('shifts.data')->middleware('auth');
Route::post('/shifts', [App\Http\Controllers\ShiftController::class, 'store'])->name('shifts.store')->middleware('auth');
Route::get('/shifts/{id}', [App\Http\Controllers\ShiftController::class, 'show'])->name('shifts.show')->middleware('auth');
Route::put('/shifts/{id}', [App\Http\Controllers\ShiftController::class, 'update'])->name('shifts.update')->middleware('auth');
Route::delete('/shifts/{id}', [App\Http\Controllers\ShiftController::class, 'destroy'])->name('shifts.destroy')->middleware('auth');

// Attendance Routes
Route::get('/attendance-employee', [App\Http\Controllers\AttendanceController::class, 'employeeIndex'])->name('attendance-employee')->middleware('auth');
Route::get('/attendance-admin', [App\Http\Controllers\AttendanceController::class, 'adminIndex'])->name('attendance-admin')->middleware('auth');
Route::get('/attendances/data', [App\Http\Controllers\AttendanceController::class, 'getAttendanceData'])->name('attendances.data')->middleware('auth');
Route::post('/attendances', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendances.store')->middleware('auth');
Route::get('/attendances/{id}', [App\Http\Controllers\AttendanceController::class, 'show'])->name('attendances.show')->middleware('auth');
Route::put('/attendances/{id}', [App\Http\Controllers\AttendanceController::class, 'update'])->name('attendances.update')->middleware('auth');
Route::delete('/attendances/{id}', [App\Http\Controllers\AttendanceController::class, 'destroy'])->name('attendances.destroy')->middleware('auth');
Route::post('/attendances/clock-in', [App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendances.clock-in')->middleware('auth');
Route::post('/attendances/clock-out', [App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendances.clock-out')->middleware('auth');

// Leave Type Routes
Route::get('/leave-types', [App\Http\Controllers\LeaveTypeController::class, 'index'])->name('leave-types')->middleware('auth');
Route::get('/leave-types/data', [App\Http\Controllers\LeaveTypeController::class, 'getLeaveTypeData'])->name('leave-types.data')->middleware('auth');
Route::post('/leave-types', [App\Http\Controllers\LeaveTypeController::class, 'store'])->name('leave-types.store')->middleware('auth');
Route::get('/leave-types/{id}', [App\Http\Controllers\LeaveTypeController::class, 'show'])->name('leave-types.show')->middleware('auth');
Route::put('/leave-types/{id}', [App\Http\Controllers\LeaveTypeController::class, 'update'])->name('leave-types.update')->middleware('auth');
Route::delete('/leave-types/{id}', [App\Http\Controllers\LeaveTypeController::class, 'destroy'])->name('leave-types.destroy')->middleware('auth');

// Leave Routes
Route::get('/leaves-admin', [App\Http\Controllers\LeaveController::class, 'adminIndex'])->name('leaves-admin')->middleware('auth');
Route::get('/leaves-employee', [App\Http\Controllers\LeaveController::class, 'employeeIndex'])->name('leaves-employee')->middleware('auth');
Route::get('/leaves/data', [App\Http\Controllers\LeaveController::class, 'getLeaveData'])->name('leaves.data')->middleware('auth');
Route::post('/leaves', [App\Http\Controllers\LeaveController::class, 'store'])->name('leaves.store')->middleware('auth');
Route::get('/leaves/{id}', [App\Http\Controllers\LeaveController::class, 'show'])->name('leaves.show')->middleware('auth');
Route::post('/leaves/{id}/approve', [App\Http\Controllers\LeaveController::class, 'approve'])->name('leaves.approve')->middleware('auth');
Route::post('/leaves/{id}/reject', [App\Http\Controllers\LeaveController::class, 'reject'])->name('leaves.reject')->middleware('auth');
Route::delete('/leaves/{id}', [App\Http\Controllers\LeaveController::class, 'destroy'])->name('leaves.destroy')->middleware('auth');

// Holiday Routes
Route::get('/holidays', [App\Http\Controllers\HolidayController::class, 'index'])->name('holidays')->middleware('auth');
Route::get('/holidays/data', [App\Http\Controllers\HolidayController::class, 'getHolidayData'])->name('holidays.data')->middleware('auth');
Route::post('/holidays', [App\Http\Controllers\HolidayController::class, 'store'])->name('holidays.store')->middleware('auth');
Route::get('/holidays/{id}', [App\Http\Controllers\HolidayController::class, 'show'])->name('holidays.show')->middleware('auth');
Route::put('/holidays/{id}', [App\Http\Controllers\HolidayController::class, 'update'])->name('holidays.update')->middleware('auth');
Route::delete('/holidays/{id}', [App\Http\Controllers\HolidayController::class, 'destroy'])->name('holidays.destroy')->middleware('auth');

// Payroll Routes
Route::get('/payroll-list', [App\Http\Controllers\PayrollController::class, 'index'])->name('payroll-list')->middleware('auth');
Route::get('/payrolls/data', [App\Http\Controllers\PayrollController::class, 'getPayrollData'])->name('payrolls.data')->middleware('auth');
Route::post('/payrolls', [App\Http\Controllers\PayrollController::class, 'store'])->name('payrolls.store')->middleware('auth');
Route::get('/payrolls/{id}', [App\Http\Controllers\PayrollController::class, 'show'])->name('payrolls.show')->middleware('auth');
Route::put('/payrolls/{id}', [App\Http\Controllers\PayrollController::class, 'update'])->name('payrolls.update')->middleware('auth');
Route::delete('/payrolls/{id}', [App\Http\Controllers\PayrollController::class, 'destroy'])->name('payrolls.destroy')->middleware('auth');
Route::get('/payrolls/{id}/payslip', [App\Http\Controllers\PayrollController::class, 'payslip'])->name('payroll.payslip')->middleware('auth'); 


// Sales Module Routes
Route::get('/sales-list', [App\Http\Controllers\SalesController::class, 'index'])->name('sales-list')->middleware('auth');
Route::get('/sales/{id}', [App\Http\Controllers\SalesController::class, 'show'])->name('sales.show')->middleware('auth');
Route::delete('/sales/{id}', [App\Http\Controllers\SalesController::class, 'destroy'])->name('sales.destroy')->middleware('auth');
Route::post('/sales/{id}/payment', [App\Http\Controllers\SalesController::class, 'updatePayment'])->name('sales.payment')->middleware('auth');

// Invoice Routes
Route::get('/invoice-report', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice-report')->middleware('auth');
Route::post('/invoices', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store')->middleware('auth');
Route::get('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show')->middleware('auth');
Route::post('/invoices/{id}/payment', [App\Http\Controllers\InvoiceController::class, 'updatePayment'])->name('invoices.payment')->middleware('auth');
Route::delete('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoices.destroy')->middleware('auth');

// Sales Returns Routes
Route::get('/sales-returns', [App\Http\Controllers\SalesReturnController::class, 'index'])->name('sales-returns')->middleware('auth');
Route::post('/sales-returns', [App\Http\Controllers\SalesReturnController::class, 'store'])->name('sales-returns.store')->middleware('auth');
Route::get('/sales-returns/{id}', [App\Http\Controllers\SalesReturnController::class, 'show'])->name('sales-returns.show')->middleware('auth');
Route::post('/sales-returns/{id}/status', [App\Http\Controllers\SalesReturnController::class, 'updateStatus'])->name('sales-returns.status')->middleware('auth');
Route::post('/sales-returns/{id}/refund', [App\Http\Controllers\SalesReturnController::class, 'processRefund'])->name('sales-returns.refund')->middleware('auth');
Route::delete('/sales-returns/{id}', [App\Http\Controllers\SalesReturnController::class, 'destroy'])->name('sales-returns.destroy')->middleware('auth');

// Quotation Routes
Route::get('/quotation-list', [App\Http\Controllers\QuotationController::class, 'index'])->name('quotation-list')->middleware('auth');
Route::get('/quotations/products', [App\Http\Controllers\QuotationController::class, 'getProducts'])->name('quotations.products')->middleware('auth');
Route::post('/quotations', [App\Http\Controllers\QuotationController::class, 'store'])->name('quotations.store')->middleware('auth');
Route::get('/quotations/{id}', [App\Http\Controllers\QuotationController::class, 'show'])->name('quotations.show')->middleware('auth');
Route::post('/quotations/{id}/status', [App\Http\Controllers\QuotationController::class, 'updateStatus'])->name('quotations.status')->middleware('auth');
Route::post('/quotations/{id}/accept', [App\Http\Controllers\QuotationController::class, 'accept'])->name('quotations.accept')->middleware('auth');
Route::get('/quotations/{id}/invoice', [App\Http\Controllers\QuotationController::class, 'getInvoice'])->name('quotations.invoice')->middleware('auth');
Route::get('/quotations/{id}/pdf', [App\Http\Controllers\QuotationController::class, 'downloadPdf'])->name('quotations.pdf')->middleware('auth');
Route::delete('/quotations/{id}', [App\Http\Controllers\QuotationController::class, 'destroy'])->name('quotations.destroy')->middleware('auth');  

Route::get('/pos', [App\Http\Controllers\POSController::class, 'index'])->name('pos')->middleware('auth');
Route::get('/pos/products', [App\Http\Controllers\POSController::class, 'getProducts'])->name('pos.products')->middleware('auth');
Route::get('/pos/product/{id}', [App\Http\Controllers\POSController::class, 'getProduct'])->name('pos.product')->middleware('auth');
Route::post('/pos/checkout', [App\Http\Controllers\POSController::class, 'checkout'])->name('pos.checkout')->middleware('auth');
Route::get('/pos/orders', [App\Http\Controllers\POSController::class, 'getOrders'])->name('pos.orders')->middleware('auth');
Route::get('/pos/order/{id}', [App\Http\Controllers\POSController::class, 'getOrder'])->name('pos.order')->middleware('auth');

// Customer API Routes (for POS)
Route::get('/pos/customers', [App\Http\Controllers\CustomerController::class, 'getCustomers'])->name('customers.list')->middleware('auth');
Route::post('/pos/customers', [App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store')->middleware('auth');  

// Coupons
Route::get('/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupons')->middleware('auth');
Route::post('/coupons', [App\Http\Controllers\CouponController::class, 'store'])->name('coupons.store')->middleware('auth');
Route::get('/coupons/{id}', [App\Http\Controllers\CouponController::class, 'show'])->name('coupons.show')->middleware('auth');
Route::put('/coupons/{id}', [App\Http\Controllers\CouponController::class, 'update'])->name('coupons.update')->middleware('auth');
Route::delete('/coupons/{id}', [App\Http\Controllers\CouponController::class, 'destroy'])->name('coupons.destroy')->middleware('auth');
Route::post('/coupons/{id}/toggle-status', [App\Http\Controllers\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status')->middleware('auth');
Route::get('/coupons-generate-code', [App\Http\Controllers\CouponController::class, 'generateCode'])->name('coupons.generate-code')->middleware('auth');
Route::post('/coupons/apply', [App\Http\Controllers\CouponController::class, 'applyCoupon'])->name('coupons.apply')->middleware('auth');  

Route::get('/customers', function () {                         
    return view('customers');
})->name('customers');  

Route::get('/suppliers', function () {                         
    return view('suppliers');
})->name('suppliers');  

// Store Management Routes
Route::get('/store-list', [App\Http\Controllers\StoreController::class, 'index'])->name('store-list')->middleware('auth');
Route::post('/stores', [App\Http\Controllers\StoreController::class, 'store'])->name('stores.store')->middleware('auth');
Route::put('/stores/{id}', [App\Http\Controllers\StoreController::class, 'update'])->name('stores.update')->middleware('auth');
Route::delete('/stores/{id}', [App\Http\Controllers\StoreController::class, 'destroy'])->name('stores.destroy')->middleware('auth');
Route::post('/stores/{id}/assign-user', [App\Http\Controllers\StoreController::class, 'assignUser'])->name('stores.assign-user')->middleware('auth');
Route::delete('/stores/{storeId}/remove-user/{userId}', [App\Http\Controllers\StoreController::class, 'removeUser'])->name('stores.remove-user')->middleware('auth');
Route::post('/stores/{id}/create-user', [App\Http\Controllers\StoreController::class, 'createStoreUser'])->name('stores.create-user')->middleware('auth');
Route::post('/stores/{id}/toggle-status', [App\Http\Controllers\StoreController::class, 'toggleStatus'])->name('stores.toggle-status')->middleware('auth');
Route::get('/select-store/{id}', [App\Http\Controllers\StoreController::class, 'selectStore'])->name('select-store')->middleware('auth');

// Storefront CMS Routes (must be before storefront routes)
Route::get('/storefront-cms', [App\Http\Controllers\StoreThemeController::class, 'index'])->name('storefront-cms')->middleware('auth');
Route::put('/storefront-theme/{storeId}', [App\Http\Controllers\StoreThemeController::class, 'update'])->name('storefront-theme.update')->middleware('auth');
Route::get('/storefront-preview/{storeId}', [App\Http\Controllers\StoreThemeController::class, 'preview'])->name('storefront-preview')->middleware('auth');

// Store Frontend Routes (must be after CMS routes to avoid conflicts)
// Exclude common admin routes from being matched as store slugs
Route::prefix('{slug}')->where(['slug' => '^(?!index|product-list|add-product|store-list|pos|login|logout|signin|signout|register|api|admin|storefront-cms|storefront-theme|storefront-preview|laundry|audit-trail|users|roles-permissions|delete-account|session|expense|payroll|attendance|leave|holiday|shift|designation|department|employee|quotation|invoice|sales|coupon|customer|supplier|warehouse|report|inventory|purchase|tax|profit|income).*$'])->group(function () {
    Route::get('/', [StoreFrontController::class, 'index'])->name('storefront.index');
    Route::get('/products', [StoreFrontController::class, 'products'])->name('storefront.products');
    Route::get('/product/{productSlug}', [StoreFrontController::class, 'product'])->name('storefront.product');
    Route::get('/category/{categorySlug}', [StoreFrontController::class, 'category'])->name('storefront.category');
});  

Route::get('/warehouse', function () {                         
    return view('warehouse');
})->name('warehouse');  

Route::get('/ui-accordion', function () {
    return view('ui-accordion');
})->name('ui-accordion');

Route::get('/ui-alerts', function () {
    return view('ui-alerts');
})->name('ui-alerts');

Route::get('/ui-avatar', function () {
    return view('ui-avatar');
})->name('ui-avatar');

Route::get('/ui-badges', function () {
    return view('ui-badges');
})->name('ui-badges');

Route::get('/ui-borders', function () {
    return view('ui-borders');
})->name('ui-borders');

Route::get('/ui-breadcrumb', function () {
    return view('ui-breadcrumb');
})->name('ui-breadcrumb');

Route::get('/ui-buttons-group', function () {
    return view('ui-buttons-group');
})->name('ui-buttons-group');

Route::get('/ui-buttons', function () {
    return view('ui-buttons');
})->name('ui-buttons');

Route::get('/ui-cards', function () {
    return view('ui-cards');
})->name('ui-cards');

Route::get('/ui-carousel', function () {
    return view('ui-carousel');
})->name('ui-carousel');

Route::get('/ui-clipboard', function () {
    return view('ui-clipboard');
})->name('ui-clipboard');

Route::get('/ui-colors', function () {
    return view('ui-colors');
})->name('ui-colors');

Route::get('/ui-counter', function () {
    return view('ui-counter');
})->name('ui-counter');

Route::get('/ui-drag-drop', function () {
    return view('ui-drag-drop');
})->name('ui-drag-drop');

Route::get('/ui-dropdowns', function () {
    return view('ui-dropdowns');
})->name('ui-dropdowns');

Route::get('/ui-grid', function () {
    return view('ui-grid');
})->name('ui-grid');

Route::get('/ui-images', function () {
    return view('ui-images');
})->name('ui-images');

Route::get('/ui-lightbox', function () {
    return view('ui-lightbox');
})->name('ui-lightbox');

Route::get('/ui-media', function () {
    return view('ui-media');
})->name('ui-media');

Route::get('/ui-modals', function () {
    return view('ui-modals');
})->name('ui-modals');

Route::get('/ui-nav-tabs', function () {
    return view('ui-nav-tabs');
})->name('ui-nav-tabs');

Route::get('/ui-offcanvas', function () {
    return view('ui-offcanvas');
})->name('ui-offcanvas');

Route::get('/ui-pagination', function () {
    return view('ui-pagination');
})->name('ui-pagination');

Route::get('/ui-placeholders', function () {
    return view('ui-placeholders');
})->name('ui-placeholders');

Route::get('/ui-popovers', function () {
    return view('ui-popovers');
})->name('ui-popovers');

Route::get('/ui-progress', function () {
    return view('ui-progress');
})->name('ui-progress');

Route::get('/ui-rangeslider', function () {
    return view('ui-rangeslider');
})->name('ui-rangeslider');

Route::get('/ui-rating', function () {
    return view('ui-rating');
})->name('ui-rating');

Route::get('/ui-ribbon', function () {
    return view('ui-ribbon');
})->name('ui-ribbon');

Route::get('/ui-scrollbar', function () {
    return view('ui-scrollbar');
})->name('ui-scrollbar');

Route::get('/ui-spinner', function () {
    return view('ui-spinner');
})->name('ui-spinner');

Route::get('/ui-stickynote', function () {
    return view('ui-stickynote');
})->name('ui-stickynote');

Route::get('/ui-sweetalerts', function () {
    return view('ui-sweetalerts');
})->name('ui-sweetalerts');

Route::get('/ui-text-editor', function () {
    return view('ui-text-editor');
})->name('ui-text-editor');

Route::get('/ui-timeline', function () {
    return view('ui-timeline');
})->name('ui-timeline');

Route::get('/ui-toasts', function () {
    return view('ui-toasts');
})->name('ui-toasts');

Route::get('/ui-tooltips', function () {
    return view('ui-tooltips');
})->name('ui-tooltips');

Route::get('/ui-typography', function () {
    return view('ui-typography');
})->name('ui-typography');

Route::get('/ui-video', function () {
    return view('ui-video');
})->name('ui-video');

Route::get('/chart-apex', function () {
    return view('chart-apex');
})->name('chart-apex');

Route::get('/chart-js', function () {
    return view('chart-js');
})->name('chart-js');

Route::get('/chart-morris', function () {
    return view('chart-morris');
})->name('chart-morris');

Route::get('/chart-flot', function () {
    return view('chart-flot');
})->name('chart-flot');

Route::get('/chart-peity', function () {
    return view('chart-peity');
})->name('chart-peity');

Route::get('/chart-c3', function () {
    return view('chart-c3');
})->name('chart-c3');

Route::get('/icon-fontawesome', function () {
    return view('icon-fontawesome');
})->name('icon-fontawesome');

Route::get('/icon-feather', function () {
    return view('icon-feather');
})->name('icon-feather');

Route::get('/icon-ionic', function () {
    return view('icon-ionic');
})->name('icon-ionic');

Route::get('/icon-material', function () {
    return view('icon-material');
})->name('icon-material');

Route::get('/icon-pe7', function () {
    return view('icon-pe7');
})->name('icon-pe7');

Route::get('/icon-simpleline', function () {
    return view('icon-simpleline');
})->name('icon-simpleline');

Route::get('/icon-themify', function () {
    return view('icon-themify');
})->name('icon-themify');

Route::get('/icon-weather', function () {
    return view('icon-weather');
})->name('icon-weather');

Route::get('/icon-typicon', function () {
    return view('icon-typicon');
})->name('icon-typicon');

Route::get('/icon-flag', function () {
    return view('icon-flag');
})->name('icon-flag');

// User Management Routes
Route::middleware('auth')->group(function () {
    // Users - accessible by Super Admin and Business Owner
    Route::get('/users', [App\Http\Controllers\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/data', [App\Http\Controllers\UserManagementController::class, 'getUsersData'])->name('users.data');
    Route::post('/users', [App\Http\Controllers\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // Roles & Permissions - Super Admin only
    Route::get('/roles-permissions', [App\Http\Controllers\UserManagementController::class, 'rolesPermissions'])->name('roles-permissions');
    Route::get('/roles/data', [App\Http\Controllers\UserManagementController::class, 'getRolesData'])->name('roles.data');
    Route::post('/roles', [App\Http\Controllers\UserManagementController::class, 'storeRole'])->name('roles.store');
    Route::put('/roles/{id}', [App\Http\Controllers\UserManagementController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{id}', [App\Http\Controllers\UserManagementController::class, 'destroyRole'])->name('roles.destroy');
    
    // Delete Account Requests - Super Admin only
    Route::get('/delete-account', [App\Http\Controllers\UserManagementController::class, 'deleteAccountRequests'])->name('delete-account');
    Route::delete('/delete-account/{id}', [App\Http\Controllers\UserManagementController::class, 'processDeleteRequest'])->name('delete-account.process');
});

Route::get('/permissions', function () {
    return view('permissions');
})->name('permissions');
Route::get('/form-basic-inputs', function () {
    return view('form-basic-inputs');
})->name('form-basic-inputs');

Route::get('/form-checkbox-radios', function () {
    return view('form-checkbox-radios');
})->name('form-checkbox-radios');

Route::get('/form-input-groups', function () {
    return view('form-input-groups');
})->name('form-input-groups');

Route::get('/form-grid-gutters', function () {
    return view('form-grid-gutters');
})->name('form-grid-gutters');

Route::get('/form-select', function () {
    return view('form-select');
})->name('form-select');

Route::get('/form-mask', function () {
    return view('form-mask');
})->name('form-mask');

Route::get('/form-fileupload', function () {
    return view('form-fileupload');
})->name('form-fileupload');

Route::get('/form-horizontal', function () {
    return view('form-horizontal');
})->name('form-horizontal');

Route::get('/form-vertical', function () {
    return view('form-vertical');
})->name('form-vertical');

Route::get('/form-floating-labels', function () {
    return view('form-floating-labels');
})->name('form-floating-labels');

Route::get('/form-validation', function () {
    return view('form-validation');
})->name('form-validation');

Route::get('/form-select2', function () {
    return view('form-select2');
})->name('form-select2');

Route::get('/form-wizard', function () {
    return view('form-wizard');
})->name('form-wizard');

Route::get('/tables-basic', function () {
    return view('tables-basic');
})->name('tables-basic');

Route::get('/data-tables', function () {
    return view('data-tables');
})->name('data-tables');

// Report Routes - All handled by ReportController
Route::middleware('auth')->group(function () {
    Route::get('/sales-report', [App\Http\Controllers\ReportController::class, 'salesReport'])->name('sales-report');
    Route::get('/sales-report/data', [App\Http\Controllers\ReportController::class, 'getSalesReportData'])->name('sales-report.data');
    Route::get('/purchase-report', [App\Http\Controllers\ReportController::class, 'purchaseReport'])->name('purchase-report');
    Route::get('/inventory-report', [App\Http\Controllers\ReportController::class, 'inventoryReport'])->name('inventory-report');
    // invoice-report route is handled by InvoiceController (defined earlier in this file)
    Route::get('/supplier-report', [App\Http\Controllers\ReportController::class, 'supplierReport'])->name('supplier-report');
    Route::get('/customer-report', [App\Http\Controllers\ReportController::class, 'customerReport'])->name('customer-report');
    Route::get('/expense-report', [App\Http\Controllers\ExpenseController::class, 'report'])->name('expense-report');
    Route::get('/income-report', [App\Http\Controllers\ReportController::class, 'incomeReport'])->name('income-report');
    Route::get('/tax-reports', [App\Http\Controllers\ReportController::class, 'taxReport'])->name('tax-reports');
    Route::get('/profit-loss', [App\Http\Controllers\ReportController::class, 'profitLossReport'])->name('profit-loss');
});

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/under-maintenance', function () {
    return view('under-maintenance');
})->name('under-maintenance');

Route::get('/blank-page', function () {
    return view('blank-page');
})->name('blank-page');

Route::get('/coming-soon', function () {
    return view('coming-soon');
})->name('coming-soon');

Route::get('/countries', function () {
    return view('countries');
})->name('countries');

Route::get('/states', function () {
    return view('states');
})->name('states');

Route::get('/error-404', function () {
    return view('error-404');
})->name('error-404');

Route::get('/error-500', function () {
    return view('error-500');
})->name('error-500');

Route::get('/lock-screen', function () {
    return view('lock-screen');
})->name('lock-screen');

Route::get('/two-step-verification-3', function () {
    return view('two-step-verification-3');
})->name('two-step-verification-3');

Route::get('/two-step-verification-2', function () {
    return view('two-step-verification-2');
})->name('two-step-verification-2');

Route::get('/two-step-verification', function () {
    return view('two-step-verification');
})->name('two-step-verification');

Route::get('/email-verification-3', function () {
    return view('email-verification-3');
})->name('email-verification-3');

Route::get('/email-verification-2', function () {
    return view('email-verification-2');
})->name('email-verification-2');

Route::get('/email-verification', function () {
    return view('email-verification');
})->name('email-verification');

Route::get('/reset-password-3', function () {
    return view('reset-password-3');
})->name('reset-password-3');

Route::get('/reset-password-2', function () {
    return view('reset-password-2');
})->name('reset-password-2');

Route::get('/reset-password', function () {
    return view('reset-password');
})->name('reset-password');

Route::get('/forgot-password-3', function () {
    return view('forgot-password-3');
})->name('forgot-password-3');

Route::get('/forgot-password-2', function () {
    return view('forgot-password-2');
})->name('forgot-password-2');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');

Route::get('/register-3', function () {
    return view('register-3');
})->name('register-3');

Route::get('/register-2', function () {
    return view('register-2');
})->name('register-2');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/signin-3', function () {
    return view('signin-3');
})->name('signin-3');

Route::get('/signin-2', function () {
    return view('signin-2');
})->name('signin-2');


Route::get('/success-3', function () {
    return view('success-3');
})->name('success-3');

Route::get('/success-2', function () {
    return view('success-2');
})->name('success-2');

Route::get('/success', function () {
    return view('success');
})->name('success');

Route::get('/general-settings', function () {
    return view('general-settings');
})->name('general-settings');

Route::get('/security-settings', function () {
    return view('security-settings');
})->name('security-settings');

Route::get('/notification', function () {
    return view('notification');
})->name('notification');

Route::get('/connected-apps', function () {
    return view('connected-apps');
})->name('connected-apps');

Route::get('/system-settings', function () {
    return view('system-settings');
})->name('system-settings');

Route::get('/company-settings', function () {
    return view('company-settings');
})->name('company-settings');

Route::get('/localization-settings', function () {
    return view('localization-settings');
})->name('localization-settings');

Route::get('/prefixes', function () {
    return view('prefixes');
})->name('prefixes');

Route::get('/preference', function () {
    return view('preference');
})->name('preference');

Route::get('/appearance', function () {
    return view('appearance');
})->name('appearance');

Route::get('/social-authentication', function () {
    return view('social-authentication');
})->name('social-authentication');

Route::get('/language-settings', function () {
    return view('language-settings');
})->name('language-settings');

Route::get('/invoice-settings', function () {
    return view('invoice-settings');
})->name('invoice-settings');

Route::get('/printer-settings', function () {
    return view('printer-settings');
})->name('printer-settings');

Route::get('/pos-settings', function () {
    return view('pos-settings');
})->name('pos-settings');

Route::get('/custom-fields', function () {
    return view('custom-fields');
})->name('custom-fields');

Route::get('/email-settings', function () {
    return view('email-settings');
})->name('email-settings');

Route::get('/sms-gateway', function () {
    return view('sms-gateway');
})->name('sms-gateway');

Route::get('/otp-settings', function () {
    return view('otp-settings');
})->name('otp-settings');

Route::get('/gdpr-settings', function () {
    return view('gdpr-settings');
})->name('gdpr-settings');

Route::get('/payment-gateway-settings', function () {
    return view('payment-gateway-settings');
})->name('payment-gateway-settings');

Route::get('/bank-settings-grid', function () {
    return view('bank-settings-grid');
})->name('bank-settings-grid');     

Route::get('/tax-rates', function () {
    return view('tax-rates');
})->name('tax-rates');   

Route::get('/currency-settings', function () {
    return view('currency-settings');
})->name('currency-settings');    

Route::get('/storage-settings', function () {
    return view('storage-settings');
})->name('storage-settings');   

Route::get('/ban-ip-address', function () {
    return view('ban-ip-address');
})->name('ban-ip-address'); 

Route::get('/activities', function () {
    return view('activities');
})->name('activities'); 

Route::get('/add-employee', function () {
    return view('add-employee');
})->name('add-employee');

Route::get('/bank-settings-list', function () {
    return view('bank-settings-list');
})->name('bank-settings-list');

Route::get('/department-list', function () {
    return view('department-list');
})->name('department-list');

Route::get('/employees-list', function () {
    return view('employees-list');
})->name('employees-list');

Route::get('/language-settings-web', function () {
    return view('language-settings-web');
})->name('language-settings-web');

// ========== AUDIT TRAIL MODULE ==========
Route::middleware('auth')->prefix('audit-trail')->name('audit-trail.')->group(function () {
    Route::get('/', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\ActivityLogController::class, 'getData'])->name('data');
    Route::get('/export', [App\Http\Controllers\ActivityLogController::class, 'export'])->name('export');
    Route::get('/stats', [App\Http\Controllers\ActivityLogController::class, 'stats'])->name('stats');
    Route::get('/user/{userId?}', [App\Http\Controllers\ActivityLogController::class, 'userActivity'])->name('user');
    Route::get('/model/{modelType}/{modelId}', [App\Http\Controllers\ActivityLogController::class, 'modelHistory'])->name('model');
    Route::get('/{id}', [App\Http\Controllers\ActivityLogController::class, 'show'])->name('show');
});

// ========== LAUNDRY OPERATIONS MODULE ==========
Route::middleware('auth')->prefix('laundry')->name('laundry.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\LaundryController::class, 'dashboard'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [App\Http\Controllers\LaundryController::class, 'index'])->name('orders');
    Route::get('/orders/create', [App\Http\Controllers\LaundryController::class, 'create'])->name('create');
    Route::post('/orders', [App\Http\Controllers\LaundryController::class, 'store'])->name('store');
    Route::get('/orders/{id}', [App\Http\Controllers\LaundryController::class, 'show'])->name('show');
    Route::put('/orders/{id}/status', [App\Http\Controllers\LaundryController::class, 'updateStatus'])->name('update-status');
    
    // QR/Order Search
    Route::post('/scan-qr', [App\Http\Controllers\LaundryController::class, 'scanQR'])->name('scan-qr');
    Route::post('/find-order', [App\Http\Controllers\LaundryController::class, 'findByOrderNumber'])->name('find-order');
    Route::get('/orders/{id}/qr', [App\Http\Controllers\LaundryController::class, 'generateQRImage'])->name('qr-image');
    
    // Garment Types
    Route::get('/garment-types', [App\Http\Controllers\LaundryController::class, 'garmentTypes'])->name('garment-types');
    Route::post('/garment-types', [App\Http\Controllers\LaundryController::class, 'storeGarmentType'])->name('garment-types.store');
    Route::put('/garment-types/{id}', [App\Http\Controllers\LaundryController::class, 'updateGarmentType'])->name('garment-types.update');
    Route::delete('/garment-types/{id}', [App\Http\Controllers\LaundryController::class, 'deleteGarmentType'])->name('garment-types.delete');
    
    // Machines
    Route::get('/machines', [App\Http\Controllers\MachineController::class, 'index'])->name('machines');
    Route::post('/machines', [App\Http\Controllers\MachineController::class, 'store'])->name('machines.store');
    Route::put('/machines/{id}', [App\Http\Controllers\MachineController::class, 'update'])->name('machines.update');
    Route::delete('/machines/{id}', [App\Http\Controllers\MachineController::class, 'destroy'])->name('machines.destroy');
    Route::get('/machines/status', [App\Http\Controllers\MachineController::class, 'getStatus'])->name('machines.status');
    
    // Machine Usage
    Route::get('/machine-usage', [App\Http\Controllers\MachineController::class, 'usageHistory'])->name('machine-usage');
    Route::post('/machines/start', [App\Http\Controllers\MachineController::class, 'startUsage'])->name('machines.start');
    Route::put('/machines/usage/{id}/end', [App\Http\Controllers\MachineController::class, 'endUsage'])->name('machines.end');
    
    // Quality Control
    Route::get('/qc', [App\Http\Controllers\QualityCheckController::class, 'index'])->name('qc.index');
    Route::get('/qc/history', [App\Http\Controllers\QualityCheckController::class, 'history'])->name('qc.history');
    Route::get('/qc/{orderId}', [App\Http\Controllers\QualityCheckController::class, 'create'])->name('qc.create');
    Route::post('/qc/{orderId}', [App\Http\Controllers\QualityCheckController::class, 'store'])->name('qc.store');
    Route::get('/qc/view/{id}', [App\Http\Controllers\QualityCheckController::class, 'show'])->name('qc.show');
});








