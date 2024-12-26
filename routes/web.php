
<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\User_Controller;
use App\Http\Controllers\Admin_Controller;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\ForgotPass_Controller;
use App\Http\Controllers\Main_Admin_Controller;
use App\Http\Controllers\Master_Admin_Controller;
use App\Http\Controllers\System_Admin_Controller;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Login Process
Route::prefix('/')->group(function () {

    // User Level
    Route::get('/', [UserLoginController::class, 'index'])->name('login');
    Route::post('/login_check', [UserLoginController::class, 'login_check'])->name('login.check');
    Route::post('/login_submit', [UserLoginController::class, 'login'])->name('login.submit');
    Route::get('/logout', [UserLoginController::class, 'logout'])->name('logout');

    // MA Default Account Procedure
    Route::get('/email', [AdminLoginController::class, 'email'])->name('ma.email');
    Route::get('/otp', [AdminLoginController::class, 'otp'])->name('ma.otp');
    Route::get('/credentials', [AdminLoginController::class, 'credentials'])->name('ma.credentials');

    Route::post('/email', [AdminLoginController::class, 'email_post'])->name('ma.email.send')->middleware('throttle:20,360');
    Route::post('/otp', [AdminLoginController::class, 'otp_post'])->name('ma.otp.send');
    Route::post('/credentials', [AdminLoginController::class, 'credentials_post'])->name('ma.credentials.verify');

    // System Admin Level
    Route::get('/system_login', [System_Admin_Controller::class, 'system'])->name('system.login');
    Route::post('/system_logged', [System_Admin_Controller::class, 'system_logged'])->name('system.logged');
});

// Registration
Route::prefix('register')->group(function () {
    Route::get('/', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/create', [RegisterController::class, 'create'])->name('register.create')->middleware('throttle:10,360');
});

// Verification for Registration
Route::prefix('verification')->group(function () {
    Route::get('/', [RegisterController::class, 'verify_account'])->name('verify_account');
    Route::post('/otp', [RegisterController::class, 'account_activation'])->name('verify_code');
});

// Forgot Password Module
Route::prefix('forgot_password')->group(function () {
    Route::get('/', [ForgotPass_Controller::class, 'forgot_pass'])->name('forgot.pass');
    Route::get('/verification_code', [ForgotPass_Controller::class, 'verification_pass'])->name('verify.pass');
    Route::get('/new_pass', [ForgotPass_Controller::class, 'new_pass'])->name('reset.pass');

    Route::post('/', [ForgotPass_Controller::class, 'forgot_passPost'])->name('forgot.pass.post')->middleware('throttle:10,360');
    Route::post('/forgot_pass/verification_code', [ForgotPass_Controller::class, 'verification_passPost'])->name('verify.pass.post');
    Route::post('/new_pass', [ForgotPass_Controller::class, 'new_passPost'])->name('reset.pass.post');
});


// User Controller
Route::middleware(['auth', 'preventBackHistory'])->group(function () {
    Route::prefix('user')->group(function () {
        // Change Department
        Route::post('change', [UserLoginController::class, 'change'])->name('user.change');

        Route::get('/dashboard', [User_Controller::class, 'dashboard'])->name('dashboard');
        Route::get('/resource_type', [User_Controller::class, 'reserve_resources'])->name('reserve.resource');
        Route::get('/resource_type/equipment_form', [User_Controller::class, 'equipment_form'])->name('equipment.form');
        Route::get('/resource_type/lab_form', [User_Controller::class, 'lab_form'])->name('lab.form');
        Route::get('/resource_type/facility_form', [User_Controller::class, 'facility_form'])->name('facility.form');
        Route::get('/resource/reservations', [User_Controller::class, 'getReservations'])->name('reserved.dates');
        Route::get('/facility/reservations', [User_Controller::class, 'getFacilityReservations'])->name('reserved.facilities');
        Route::get('/my_reservations', [User_Controller::class, 'my_reservations'])->name('my_reservations');
        Route::get('/reservation_calendar', [User_Controller::class, 'reservation_calendar'])->name('reservation_calendar');
        Route::get('/account_settings', [User_Controller::class, 'account'])->name('account');
        Route::get('/my_reservations/overview/{transaction_id}', [User_Controller::class, 'overview'])->name('overview');
        Route::get('/my_reservations/facility_overview/{id}', [User_Controller::class, 'facility_overview'])->name('facility.overview');
        Route::get('/about_us', [User_Controller::class, 'about'])->name('about');

        Route::post('/image_upload', [User_Controller::class, 'image_upload'])->name('image.upload');
        // Added
        Route::post('/image_reset', action: [User_Controller::class, 'resetImage'])->name('image.reset');
        Route::post('/cancel_reservation', [User_Controller::class, 'cancel'])->name('user.cancel');
        Route::post('/facility/cancel_reservation', [User_Controller::class, 'facility_cancel'])->name('user.facility.cancel');
        Route::post('/feedback', [User_Controller::class, 'feedback'])->name('user.feedback');
        Route::post('/change_password', [User_Controller::class, 'change_password'])->name('password.change');
        Route::post('/equipment_form/requested', [User_Controller::class, 'equipment_request'])->name('equipment.request');
        Route::post('/lab_form/requested', [User_Controller::class, 'lab_request'])->name('lab.request');
        Route::post('/facility_form/requested', [User_Controller::class, 'facility_request'])->name('facility.request');

        Route::get('/approve_student', [User_Controller::class, 'noted_by'])->name('noted_by');
        Route::get('/approve_student/overview/{transaction_id}', [User_Controller::class, 'faculty_overview'])->name('faculty.overview');
        Route::post('/approved', [User_Controller::class, 'approve'])->name('faculty.approved');
        Route::post('/rejected', [User_Controller::class, 'reject'])->name('faculty.reject');
    });
});

// Department Admin Controller
Route::middleware(['auth', 'preventBackHistory'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [Admin_Controller::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard/range', [Admin_Controller::class, 'analytics_range'])->name('admin.range');
        Route::get('/requests', [Admin_Controller::class, 'department_requests'])->name('admin.requests');
        Route::get('/requests/completed', [Admin_Controller::class, 'department_archived_requests'])->name('admin.completed.requests');
        Route::get('/requests/fetch', [Admin_Controller::class, 'fetchNewRequests'])->name('admin.requests.fetch');
        Route::get('/requests/overview/{transaction_id}', [Admin_Controller::class, 'request_overview'])->name('admin.overview');
        Route::get('/requests/facility_overview/{id}', [Admin_Controller::class, 'request_facility_overview'])->name('admin.facility.overview');
        Route::get('/manage_resources', [Admin_Controller::class, 'department_resources'])->name('admin.manage.resources');

        Route::get('/manage_resources/view_resource', [Admin_Controller::class, 'view_resource'])->name('admin.view.resources');
        Route::get('/manage_resources/add_resource', [Admin_Controller::class, 'add_resource'])->name('admin.add.resources');
        Route::get('/manage_resources/add_facility', [Admin_Controller::class, 'add_facility'])->name('admin.add.facility');
        Route::get('/manage_resources/edit_facility', [Admin_Controller::class, 'edit_facility'])->name('admin.view.facility');
        Route::get('/policy_management', [Admin_Controller::class, 'policy'])->name('admin.policy');

        Route::get('/department_calendar', [Admin_Controller::class, 'reservation_calendar'])->name('admin.calendar');
        Route::get('/accounts', [Admin_Controller::class, 'account'])->name('admin.account');
        Route::get('/about_us', [Admin_Controller::class, 'admin_about'])->name('admin.about');

        // Image and Password Change
        Route::post('/image_upload', [Admin_Controller::class, 'image_upload'])->name('admin.image_upload');
        // Added
        Route::post('/image_reset', [Admin_Controller::class, 'resetImage'])->name('admin.image.reset');
        Route::post('/change_password', [Admin_Controller::class, 'change_password'])->name('admin.password.change');

        // Request Approval
        Route::post('/req_decision', [Admin_Controller::class, 'reqDecision'])->name('admin.decision');
        Route::post('/facility_approval', [Admin_Controller::class, 'facility_approval'])->name('admin.approve.facility');

        // Email Notification
        Route::post('/admin/update-reservation-status', [Admin_Controller::class, 'updateReservationStatus']);

        // Resource Management
        Route::post('/manage_resources/add_facility', [Admin_Controller::class, 'add_facilityPost'])->name('admin.create.facility');
        Route::post('/manage_resources/edit_facility', [Admin_Controller::class, 'edit_facilityPost'])->name('admin.edit.facility');
        Route::post('/store_resource', [Admin_Controller::class, 'store_resource'])->name('admin.add');
        Route::post('/edit_resource', [Admin_Controller::class, 'edit_resource'])->name('admin.edit');
        Route::post('/delete_resource', [Admin_Controller::class, 'delete_resource'])->name('admin.delete.resource');
        Route::post('/delete_facility', [Admin_Controller::class, 'delete_facility'])->name('admin.delete.facility');
        Route::post('/download/resources', [Admin_Controller::class, 'download_resources'])->name('admin.download.resources');

        // Policy Management
        Route::post('/policy_management/add_policy', [Admin_Controller::class, 'add_policy'])->name('admin.add.policy');
        Route::post('/policy_management/edit_policy', [Admin_Controller::class, 'edit_policy'])->name('admin.edit.policy');
        Route::post('/policy_management/delete_policy/{id}', [Admin_Controller::class, 'delete_policy'])->name('admin.delete.policy');

        Route::post('/general/add_policy', [Admin_Controller::class, 'add_gen_policy'])->name('admin.add.gen.policy');
        Route::post('/general/edit_policy', [Admin_Controller::class, 'edit_gen_policy'])->name('admin.edit.gen.policy');
        Route::delete('/general/delete_policy', [Admin_Controller::class, 'delete_gen_policy'])->name('admin.delete.gen.policy');
    });
});


// Master Admin Controller
Route::middleware(['auth', 'preventBackHistory'])->group(function () {
    Route::prefix('ma')->group(function () {

        Route::get('/dashboard', [Master_Admin_Controller::class, 'dashboard'])->name('ma.dashboard');
        Route::get('/dashboard/range', [Master_Admin_Controller::class, 'analytics_range'])->name('ma.range');
        Route::get('/requests', [Master_Admin_Controller::class, 'department_requests'])->name('ma.requests');
        Route::get('/requests/completed', [Master_Admin_Controller::class, 'department_archived_requests'])->name('ma.completed.requests');
        Route::get('/requests/fetch', [Master_Admin_Controller::class, 'fetchNewRequests'])->name('ma.requests.fetch');
        Route::get('/requests/overview/{transaction_id}', [Master_Admin_Controller::class, 'request_overview'])->name('ma.overview');
        Route::get('/requests/facility_overview/{id}', [Master_Admin_Controller::class, 'request_facility_overview'])->name('ma.facility.overview');

        //Added: Student's Approval : Faculty Tab
        Route::get('/approve_student/overview/{transaction_id}', [Master_Admin_Controller::class, 'faculty_overview'])->name('ma.faculty.overview');

        Route::get('/manage_resources', [Master_Admin_Controller::class, 'department_resources'])->name('ma.manage.resources');

        Route::get('/manage_resources/view_resource', [Master_Admin_Controller::class, 'view_resource'])->name('ma.view.resources');
        Route::get('/manage_resources/add_resource', [Master_Admin_Controller::class, 'add_resource'])->name('ma.add.resources');
        Route::get('/manage_resources/add_facility', [Master_Admin_Controller::class, 'add_facility'])->name('ma.add.facility');
        Route::get('/manage_resources/edit_facility', [Master_Admin_Controller::class, 'edit_facility'])->name('ma.view.facility');
        Route::get('/policy_management', [Master_Admin_Controller::class, 'policy'])->name('ma.policy');
        Route::get('/feedbacks_archived', [Master_Admin_Controller::class, 'feedback_archived'])->name('ma.feedbacks.archived');

        Route::get('/department_calendar', [Master_Admin_Controller::class, 'reservation_calendar'])->name('ma.calendar');
        Route::get('/accounts', [Master_Admin_Controller::class, 'account'])->name('ma.account');
        Route::get('/about_us', [Master_Admin_Controller::class, 'admin_about'])->name('ma.about');

        // Image and Password Change
        Route::post('/image_upload', [Master_Admin_Controller::class, 'image_upload'])->name('ma.image_upload');
        // Added
        Route::post('/image_reset', [Master_Admin_Controller::class, 'resetImage'])->name('ma.image.reset');
        Route::post('/change_password', [Master_Admin_Controller::class, 'change_password'])->name('ma.password.change');

        // Request Approval
        Route::post('/req_decision', [Master_Admin_Controller::class, 'reqDecision'])->name('ma.decision');
        Route::post('/facility_approval', [Master_Admin_Controller::class, 'facility_approval'])->name('ma.approve.facility');

        // Resource Management
        Route::post('/manage_resources/add_facility', [Master_Admin_Controller::class, 'add_facilityPost'])->name('ma.create.facility');
        Route::post('/manage_resources/edit_facility', [Master_Admin_Controller::class, 'edit_facilityPost'])->name('ma.edit.facility');
        Route::post('/manage_resources/feedbacks_archived/{id}', [Master_Admin_Controller::class, 'archive_feedback'])->name('ma.archive.feedback');
        Route::post('/manage_resources/restore/{id}', [Master_Admin_Controller::class, 'restore_feedback'])->name('ma.feedback.restore');
        Route::post('/store_resource', [Master_Admin_Controller::class, 'store_resource'])->name('ma.add');
        Route::post('/edit_resource', [Master_Admin_Controller::class, 'edit_resource'])->name('ma.edit');
        Route::post('/delete_resource', [Master_Admin_Controller::class, 'delete_resource'])->name('ma.delete.resource');
        Route::post('/delete_facility', [Master_Admin_Controller::class, 'delete_facility'])->name('ma.delete.facility');
        Route::post('/download/resources', [Master_Admin_Controller::class, 'download_resources'])->name('ma.download.resources');

        // Policy Management
        Route::post('/policy_management/add_policy', [Master_Admin_Controller::class, 'add_policy'])->name('ma.add.policy');
        Route::post('/policy_management/edit_policy', [Master_Admin_Controller::class, 'edit_policy'])->name('ma.edit.policy');
        Route::post('/policy_management/delete_policy/{id}', [Master_Admin_Controller::class, 'delete_policy'])->name('ma.delete.policy');
        Route::post('/download/policy', [Master_Admin_Controller::class, 'download_policy'])->name('ma.download.policy');

        Route::post('/general/add_policy', [Master_Admin_Controller::class, 'add_gen_policy'])->name('ma.add.gen.policy');
        Route::post('/general/edit_policy', [Master_Admin_Controller::class, 'edit_gen_policy'])->name('ma.edit.gen.policy');
        Route::delete('/general/delete_policy', [Master_Admin_Controller::class, 'delete_gen_policy'])->name('ma.delete.gen.policy');

        // Master Admin Restricted Routes
        Route::get('/cms', [Master_Admin_Controller::class, 'ma_cms'])->name('ma.cms');
        Route::get('/account_management', [Master_Admin_Controller::class, 'account_management'])->name('ma.account.management');
        Route::get('/audit', [Master_Admin_Controller::class, 'audit'])->name('ma.audit');
        Route::get('/audits_archived', [Master_Admin_Controller::class, 'audits_archived'])->name('ma.audits.archived');

        Route::post('/cms/general', [Master_Admin_Controller::class, 'cms_general'])->name('ma.cms.general');
        Route::post('/cms/about', [Master_Admin_Controller::class, 'cms_about'])->name('ma.cms.about');

        Route::post('/account_management/{userId}', [Master_Admin_Controller::class, 'update_account'])->name('ma.update.account');

        Route::post('/audit/archive/{id}', [Master_Admin_Controller::class, 'audit_archive'])->name('ma.audit.archive');
        Route::post('/audit/mass/archive', [Master_Admin_Controller::class, 'mass_archive'])->name('ma.audit.mass.archive');
        Route::post('/audit/restore/{id}', [System_Admin_Controller::class, 'restore_archive'])->name('ma.audit.restore');
    });
});

// System Admin Controller
Route::prefix('system')->middleware(['auth:sysad', 'preventBackHistory'])->group(function () {

    Route::get('/control_panel', [System_Admin_Controller::class, 'control_panel'])->name('system.control.panel');
    Route::get('control_panel/departments/{college}', [System_Admin_Controller::class, 'departments'])->name('system.departments');
    Route::get('/email_restriction', [System_Admin_Controller::class, 'email_restriction'])->name('system.email.restrict');
    Route::get('/audit_trail', [System_Admin_Controller::class, 'audit_trail'])->name('system.audit.trail');
    Route::get('/registration_policy', [System_Admin_Controller::class, 'registration_policy'])->name('system.regis.policy');
    Route::get('/about_us', [System_Admin_Controller::class, 'about_us'])->name('system.about.us');
    Route::get('/account', [System_Admin_Controller::class, 'account'])->name('system.account');
    Route::get('/audit_trail/audits_archived', [System_Admin_Controller::class, 'audits_archived'])->name('system.audits.archived');

    Route::post('/add/college', [System_Admin_Controller::class, 'add_college'])->name('college.add');
    Route::post('/edit/college', [System_Admin_Controller::class, 'edit_college'])->name('college.edit');
    Route::post('{collegeId}/add/department', [System_Admin_Controller::class, 'add_dept'])->name('department.add');
    Route::post('/edit/department/{department_id}/{collegeId}', [System_Admin_Controller::class, 'edit_dept'])->name('department.edit');

    Route::post('/add/master', [System_Admin_Controller::class, 'add_master_account'])->name('system.master.add');
    Route::post('/edit/master', [System_Admin_Controller::class, 'edit_master_account'])->name('system.master.edit');

    Route::post('/registration/policy', [System_Admin_Controller::class, 'policy_save'])->name('system.policy.save');
    Route::post('/registration/domain', [System_Admin_Controller::class, 'domain_save'])->name('system.domain.save');

    Route::post('/audit/archive/{id}', [System_Admin_Controller::class, 'audit_archive'])->name('system.audit.archive');
    Route::post('/audit/mass/archive', [System_Admin_Controller::class, 'mass_archive'])->name('system.audit.mass.archive');
    Route::post('/audit/restore/{id}', [System_Admin_Controller::class, 'restore_archive'])->name('system.audit.restore');


    Route::post('/account', [System_Admin_Controller::class, 'account_pass_change'])->name('system.password.change');
    Route::post('/account/credentials', [System_Admin_Controller::class, 'account_change_credentials'])->name('system.credentials.change');
});

// System Admin Verification Module
Route::prefix('system_admin_verification')->group(function () {
    Route::get('/logout', [System_Admin_Controller::class, 'system_logout'])->name('system.logout');

    // Default Account Procedure
    Route::get('/email', [System_Admin_Controller::class, 'email'])->name('system.email');
    Route::get('/otp', [System_Admin_Controller::class, 'otp'])->name('system.otp');
    Route::get('/credentials', [System_Admin_Controller::class, 'credentials'])->name('system.credentials');

    Route::post('/email', [System_Admin_Controller::class, 'email_post'])->name('system.email.send')->middleware('throttle:20,360');
    Route::post('/otp', [System_Admin_Controller::class, 'otp_post'])->name('system.otp.verify');
    Route::post('/credentials', [System_Admin_Controller::class, 'credentials_post'])->name('system.credentials.verify');

    // Forgot Password
    Route::get('/forgot_pass/email', [System_Admin_Controller::class, 'forgotpass'])->name('system.forgotpass');
    Route::get('/forgot_pass/otp', [System_Admin_Controller::class, 'otpforgotpass'])->name('system.forgotpass.otp');
    Route::get('/forgot_pass/password', [System_Admin_Controller::class, 'changepass'])->name('system.forgotpass.change');

    Route::post('/forgot_pass/email', [System_Admin_Controller::class, 'forgot_pass_email'])->name('system.forgot.email.send')->middleware('throttle:10,360');
    Route::post('/forgot_pass/otp', [System_Admin_Controller::class, 'forgot_pass_otp'])->name('system.forgot.otp.send');
    Route::post('/forgot_pass/password', [System_Admin_Controller::class, 'forgot_pass_password'])->name('system.forgot.change.send');
});
