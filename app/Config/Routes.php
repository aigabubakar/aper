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

    $routes->get('profile/academic/teaching_research', 'ProfileController::academicTeachingResearch', ['filter'=>'auth']);
    $routes->post('profile/academic/teaching_research/save', 'ProfileController::saveAcademicTeachingResearch', ['filter'=>'auth']);

    $routes->get('profile/academic/qualifications', 'ProfileController::academicQualifications', ['filter'=>'auth']);
    $routes->post('profile/academic/qualifications/save', 'ProfileController::saveAcademicQualifications', ['filter'=>'auth']);

    $routes->get('profile/academic/experience', 'ProfileController::academicExperience', ['filter'=>'auth']);
    $routes->post('profile/academic/experience/save', 'ProfileController::saveAcademicExperience', ['filter'=>'auth']);

    // Senior Non-Academic (render + save)
    $routes->get('profile/senior/personal', 'ProfileController::seniorPersonal');
    $routes->get('profile/senior/employment', 'ProfileController::seniorEmployment', ['filter' => 'auth']);
    $routes->post('profile/senior/employment/save', 'ProfileController::saveSeniorEmployment', ['filter' => 'auth']);
   
    $routes->get('profile/junior/personal', 'ProfileController::juniorPersonal');
    $routes->get('profile/nonacademic/personal', 'ProfileController::nonAcademicPersonal');

    $routes->get('profile/senior/qualifications', 'ProfileController::seniorQualifications', ['filter' => 'auth']);
    $routes->post('profile/senior/qualifications/save', 'ProfileController::saveSeniorQualifications', ['filter' => 'auth']);
    
    $routes->get('profile/senior/experience', 'ProfileController::seniorExperience', ['filter' => 'auth']);
    $routes->post('profile/senior/experience/save', 'ProfileController::saveSeniorExperience', ['filter' => 'auth']);
    
    // Junior Non-Academic (render + save)
     $routes->get('profile/success', 'ProfileController::success', ['filter' => 'auth']);






$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);









