<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->match(['get','post'], 'check-email', 'Auth::checkEmail');
$routes->get('register', 'Auth::register');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('verify/(:segment)', 'Auth::verify/$1');
$routes->post('save-registration', 'Auth::saveRegistration');
// Auth / Login
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

$routes->get('profile/choose-category', 'ProfileWizard::chooseCategory');
$routes->post('profile/choose-category', 'ProfileWizard::setCategory');

$routes->get('profile/wizard', 'ProfileWizard::index');
$routes->post('profile/wizard/save', 'ProfileWizard::saveStep');

// Optional: supply partial form html via ajax
$routes->get('profile/wizard/form/(:segment)/(:num)', 'ProfileWizard::formPartial/$1/$2');


$routes->group('profile', ['filter' => 'auth'], function($routes) {

    $routes->get('overview', 'ProfileController::overview');

    // Academic
    $routes->get('academic/personal', 'ProfileController::academicPersonal');
    $routes->post('academic/personal/save', 'ProfileController::saveAcademicPersonal');
    $routes->get('academic/employment', 'ProfileController::academicEmployment');
    $routes->post('academic/employment/save', 'ProfileController::saveAcademicEmployment');
    $routes->get('academic/professional', 'ProfileController::academicProfessional');
    $routes->post('academic/professional/save', 'ProfileController::saveAcademicProfessional');

    // Senior Non-Academic
    $routes->get('senior/personal', 'ProfileController::seniorPersonal');
    $routes->post('senior/personal/save', 'ProfileController::saveSeniorPersonal');
    $routes->get('senior/employment', 'ProfileController::seniorEmployment');
    $routes->post('senior/employment/save', 'ProfileController::saveSeniorEmployment');
    $routes->get('senior/professional', 'ProfileController::seniorProfessional');
    $routes->post('senior/professional/save', 'ProfileController::saveSeniorProfessional');

    // Junior Non-Academic
    $routes->get('junior/personal', 'ProfileController::juniorPersonal');
    $routes->post('junior/personal/save', 'ProfileController::saveJuniorPersonal');
    $routes->get('junior/employment', 'ProfileController::juniorEmployment');
    $routes->post('junior/employment/save', 'ProfileController::saveJuniorEmployment');
    $routes->get('junior/professional', 'ProfileController::juniorProfessional');
    $routes->post('junior/professional/save', 'ProfileController::saveJuniorProfessional');
});

    // Academic routes (render + save)
    
    $routes->get('profile/academic/personal', 'ProfileController::academicPersonal', ['filter'=>'auth']);
    $routes->post('profile/academic/personal/save', 'ProfileController::saveAcademicPersonal', ['filter'=>'auth']);

    $routes->get('profile/academic/employment', 'ProfileController::academicEmployment', ['filter'=>'auth']);
    $routes->post('profile/academic/employment/save', 'ProfileController::saveAcademicEmployment', ['filter'=>'auth']);

    $routes->get('profile/academic/qualifications', 'ProfileController::academicQualifications', ['filter'=>'auth']);
    $routes->post('profile/academic/qualifications/save', 'ProfileController::saveAcademicQualifications', ['filter'=>'auth']);

    $routes->get('profile/academic/experience', 'ProfileController::academicExperience', ['filter'=>'auth']);
    $routes->post('profile/academic/experience/save', 'ProfileController::saveAcademicExperience', ['filter'=>'auth']);

    // Senior Non-Academic (render + save)
    $routes->get('profile/senior/personal', 'ProfileController::seniorPersonal');
    $routes->post('profile/senior/personal/save', 'ProfileController::saveSeniorPersonal', ['filter'=>'auth']);

    $routes->get('profile/senior/employment', 'ProfileController::seniorEmployment', ['filter' => 'auth']);
    $routes->post('profile/senior/employment/save', 'ProfileController::saveSeniorEmployment', ['filter' => 'auth']);  
    
    $routes->get('profile/senior/qualifications', 'ProfileController::seniorQualifications', ['filter' => 'auth']);
    $routes->post('profile/senior/qualifications/save', 'ProfileController::saveSeniorQualifications', ['filter' => 'auth']);
    
    $routes->get('profile/senior/experience', 'ProfileController::seniorExperience', ['filter' => 'auth']);
    $routes->post('profile/senior/experience/save', 'ProfileController::saveSeniorExperience', ['filter' => 'auth']);
    
    // Junior Non-Academic (render + save)
    $routes->get('profile/junior/personal', 'ProfileController::juniorPersonal', ['filter'=>'auth']);
    $routes->post('profile/junior/personal/save', 'ProfileController::saveJuniorPersonal', ['filter'=>'auth']);

    $routes->get('profile/junior/employment', 'ProfileController::juniorEmployment', ['filter'=>'auth']);
    $routes->post('profile/junior/employment/save', 'ProfileController::saveJuniorEmployment', ['filter'=>'auth']);
    
     $routes->get('profile/junior/qualifications', 'ProfileController::JuniorQualifications', ['filter'=>'auth']);
    $routes->post('profile/junior/qualifications/save', 'ProfileController::saveJuniorQualifications', ['filter'=>'auth']);

    $routes->get('profile/junior/experience', 'ProfileController::juniorExperience', ['filter' => 'auth']);
    $routes->post('profile/junior/experience/save', 'ProfileController::saveJuniorExperience', ['filter' => 'auth']);
   

    $routes->get('profile/success', 'ProfileController::success', ['filter' => 'auth']);
     
     // Profile print summary
    $routes->get('profile/print-summary', 'ProfileController::printSummary', ['filter' => 'auth']);

    $routes->group('admin', ['filter'=>'auth'], function($routes){
    $routes->get('menus', 'Admin\MenuController::index');
    $routes->match(['get','post'],'menus/create','Admin\MenuController::create');
    $routes->match(['get','post'],'menus/edit/(:num)','Admin\MenuController::edit/$1');
    $routes->get('menus/delete/(:num)','Admin\MenuController::delete/$1');
});

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);


$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes){
    // auth
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attempt');
    $routes->get('logout', 'Auth::logout');


    // admin user management (example)
    $routes->get('users', 'UserController::index', ['filter'=>'adminAuth']);
    // ... add admin CRUD routes here and apply 'adminAuth' filter
});
// Admin dashboard + manage users (staff)
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'adminAuth']);
$routes->get('', 'Dashboard::index', ['filter' => 'adminAuth']);

// Admin user/staff management (CRUD)
// $routes->get('users/create', 'UserManagement::create', ['filter' => 'adminAuth']);
// $routes->post('users/store', 'UserManagement::store', ['filter' => 'adminAuth']);
// $routes->get('users/show/(:num)', 'UserManagement::show/$1', ['filter' => 'adminAuth']);
// $routes->get('users/edit/(:num)', 'UserManagement::edit/$1', ['filter' => 'adminAuth']);
// $routes->post('users/update/(:num)', 'UserManagement::update/$1', ['filter' => 'adminAuth']);
// $routes->post('users/delete/(:num)', 'UserManagement::delete/$1', ['filter' => 'adminAuth']);



// -------------------------------
// Admin routes (Routes.php)
// -------------------------------
// $routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
//     // Public admin auth
//     $routes->get('login', 'Auth::login');
//     $routes->post('login', 'Auth::attempt');
//     $routes->get('logout', 'Auth::logout');

//     // Dashboard + user management — protect with adminAuth filter if you have it
//     // If you use an AdminAuth filter alias, add ['filter'=>'adminAuth'] to protected routes
//     $routes->get('', 'Dashboard::index', ['filter' => 'adminAuth']);
//     $routes->get('dashboard', 'Dashboard::index', ['filter' => 'adminAuth']);

// // Admin user/staff management (CRUD)
//     $routes->get('users', 'UserManagement::index', ['filter' => 'adminAuth']);
//     $routes->get('users/create', 'UserManagement::create', ['filter' => 'adminAuth']);
//     $routes->post('users/store', 'UserManagement::store', ['filter' => 'adminAuth']);
//     $routes->get('users/edit/(:num)', 'UserManagement::edit/$1', ['filter' => 'adminAuth']);
//     $routes->post('users/update/(:num)', 'UserManagement::update/$1', ['filter' => 'adminAuth']);
//     $routes->post('users/delete/(:num)', 'UserManagement::delete/$1', ['filter' => 'adminAuth']);
// });


// $routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
//     $routes->get('staff', 'AdminStaffController::index');
//     $routes->get('staff/create', 'AdminStaffController::create');
//     $routes->post('staff/store', 'AdminStaffController::store');
//     $routes->get('staff/edit/(:num)', 'AdminStaffController::edit/$1');
//     $routes->post('staff/update/(:num)', 'AdminStaffController::update/$1');
//     $routes->get('staff/delete/(:num)', 'AdminStaffController::delete/$1');
// });



// Admin area

// $routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
//     // public
//         $routes->post('attempt', 'Auth::attemptLogin'); // <-- add this line
//     // existing: $routes->post('login', 'Auth::attemptLogin');
//        $routes->get('logout', 'Auth::logout');

//     // protected
//     $routes->group('/', ['filter' => 'admin_auth'], function($routes) {
//         $routes->get('admin/dashboard', 'Dashboard::index', ['filter' => 'admin_auth']);
        


//         // Staff management (CRUD)
//         $routes->get('staff', 'StaffController::index');
//         $routes->get('staff/data', 'StaffController::data'); // DataTables server-side
//         $routes->get('staff/create', 'StaffController::create');
//         $routes->post('staff/create', 'StaffController::create');
//         $routes->get('staff/(:num)', 'StaffController::view/$1');
//         $routes->get('staff/(:num)/edit', 'StaffController::edit/$1');
//         $routes->post('staff/(:num)/edit', 'StaffController::edit/$1');
//         $routes->post('staff/(:num)/delete', 'StaffController::delete/$1');


//         // evaluation stub
//         $routes->get('staff/(:num)/evaluate', 'StaffController::evaluate/$1');
//     });
// });

// Admin routes group
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function($routes) {

    // Authentication (login form + submit)
    $routes->get('login', 'Auth::login');                // show login form   -> /admin/login (GET)
    $routes->post('login', 'Auth::attemptLogin');       // handle login form -> /admin/login (POST)
    // compatibility: some forms may post to /admin/attempt
    $routes->post('attempt', 'Auth::attemptLogin');     // optional alias   -> /admin/attempt (POST)
    $routes->get('logout', 'Auth::logout');             // logout           -> /admin/logout (GET)

    // Dashboard (landing)
    $routes->get('/', 'Dashboard::index');              // /admin/
    $routes->get('dashboard', 'Dashboard::index');      // /admin/dashboard

    // Staff CRUD
    $routes->get('staff', 'Staff::index');              // /admin/staff
    $routes->get('staff/create', 'Staff::create');      // /admin/staff/create
    $routes->post('staff/store', 'Staff::store');       // /admin/staff/store
    $routes->get('staff/(:num)/edit', 'Staff::edit/$1');    // /admin/staff/12/edit
    $routes->post('staff/(:num)/update', 'Staff::update/$1');// /admin/staff/12/update
    $routes->get('staff/(:num)/view', 'Staff::view/$1');    // /admin/staff/12/view
    $routes->get('staff/(:num)/delete', 'Staff::delete/$1');// /admin/staff/12/delete
    $routes->get('staff/(:num)/evaluate', 'Staff::evaluate/$1'); // /Evaluations
    // Settings
    $routes->get('settings', 'Settings::index');
    // in app/Config/Routes.php admin group
     $routes->get('admin/staff/export', 'Admin\Staff::export'); // or exportCsv depending on your controller
     $routes->get('staff/export', 'Staff::export', ['filter' => 'adminauth']);
    // $routes->get('staff/exportCsv', 'Staff::export', ['filter' => 'adminauth']); // alias -> export   
    // $routes->get('admin/staff/export', 'Admin\Staff::export', ['filter' => 'adminauth']);
    



});












