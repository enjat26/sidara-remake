<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
if (config('Ionix')->homePage == true) {
	$routes->setDefaultController('HomeController');
} elseif (config('Ionix')->homePage == false) {
	$routes->setDefaultController('App\Controllers\Auth\LoginController');
}
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
 if (config('Ionix')->homePage == true) {
		$routePanel = 'panel/';
 } elseif (config('Ionix')->homePage == false) {
		$routePanel = '';
 }

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

/*
 * --------------------------------------------------------------------
 * iAuth Routes
 * --------------------------------------------------------------------
 */
 if (config('Ionix')->homePage == true) {
 	 $routes->get('/', 'HomeController::index', ['filter' => 'site']);
 } elseif (config('Ionix')->homePage == false) {
 	 $routes->get('/', 'Auth\LoginController::index', ['filter' => 'site']);
 }

// Without filters
if (ENVIRONMENT !== 'development') {
	$routes->get($routePanel.'logout', 'Auth\LogoutController::index');
	$routes->get('image/(:segment)/(:segment)', 'FilesController::image');
	$routes->get('content/(:segment)/(:segment)/(:segment)', 'FilesController::content');
	$routes->get('file/d/(:segment)/download', 'FilesController::download');
	$routes->get('file/d/(:segment)/view', 'FilesController::viewer');
} else {
	$routes->add($routePanel.'logout', 'Auth\LogoutController::index');
	$routes->add('image/(:segment)/(:segment)', 'FilesController::image');
	$routes->add('content/(:segment)/(:segment)/(:segment)', 'FilesController::content');
	$routes->add('file/d/(:segment)/download', 'FilesController::download');
	$routes->add('file/d/(:segment)/view', 'FilesController::viewer');
}

// With filters
$routes->group('/', ['filter' => 'site'], function($routes) {
	$routes->get('login', 'Auth\LoginController::index');
	$routes->get('register', 'Auth\RegisterController::index');
	$routes->get('register/activation/(:segment)', 'Auth\ActivationController::index');
	$routes->get('forgot', 'Auth\ForgotController::index');
	$routes->get('recovery/(:segment)', 'Auth\RecoveryController::index');

	$routes->post('login', 'Auth\LoginController::attemptLogin', ['filter' => 'request']);
	$routes->post('register', 'Auth\RegisterController::attemptRegister', ['filter' => 'request']);
	$routes->post('forgot', 'Auth\ForgotController::attemptForgot', ['filter' => 'request']);
	$routes->post('recovery', 'Auth\RecoveryController::attemptRecovery', ['filter' => 'request']);
});

$routes->group($routePanel.'dashboard', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\DashboardController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\DashboardController::get', ['filter' => 'request']);
		$routes->get('show', 'Panel\DashboardController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\DashboardController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\DashboardController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\DashboardController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\DashboardController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\DashboardController::get', ['filter' => 'request']);
		$routes->add('show', 'Panel\DashboardController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\DashboardController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\DashboardController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\DashboardController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\DashboardController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'u', ['filter' => 'login'], function($routes) {
	$routes->get('(:segment)', 'Panel\ProfileController::detail');
});

$routes->group($routePanel.'profile', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\ProfileController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\ProfileController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\ProfileController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\ProfileController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\ProfileController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\ProfileController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\ProfileController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\ProfileController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\ProfileController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\ProfileController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\ProfileController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'notifications', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\NotificationController::index');

	if (ENVIRONMENT !== 'development') {
	  $routes->get('count', 'Panel\NotificationController::count', ['filter' => 'request']);
	  $routes->get('get', 'Panel\NotificationController::get', ['filter' => 'request']);
	  $routes->add('list', 'Panel\NotificationController::list', ['filter' => 'request']);
	  $routes->post('store', 'Panel\NotificationController::store');
	  $routes->post('update', 'Panel\NotificationController::update');
	  $routes->delete('delete', 'Panel\NotificationController::delete');
	} else {
		$routes->add('count', 'Panel\NotificationController::count', ['filter' => 'request']);
	  $routes->add('get', 'Panel\NotificationController::get', ['filter' => 'request']);
	  $routes->add('list', 'Panel\NotificationController::list', ['filter' => 'request']);
	  $routes->add('store', 'Panel\NotificationController::store');
	  $routes->add('update', 'Panel\NotificationController::update');
	  $routes->add('delete', 'Panel\NotificationController::delete');
	}
});

$routes->group($routePanel.'areas', ['filter' => 'login'], function($routes) {
	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Area\AreaController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\AreaController::list', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Area\AreaController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\AreaController::list', ['filter' => 'request']);
	}
});

$routes->group($routePanel.'countrys', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Area\Country\CountryController::index');
	$routes->get('export/(:segment)', 'Panel\Area\Country\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Area\Country\CountryController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\Country\CountryController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Area\Country\CountryController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\Area\Country\CountryController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\Area\Country\CountryController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\Area\Country\CountryController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\Country\CountryController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Area\Country\CountryController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\Area\Country\CountryController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\Area\Country\CountryController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'provinces', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Area\Province\ProvinceController::index');
	$routes->get('export/(:segment)', 'Panel\Area\Province\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Area\Province\ProvinceController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\Province\ProvinceController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Area\Province\ProvinceController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\Area\Province\ProvinceController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\Area\Province\ProvinceController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\Area\Province\ProvinceController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\Province\ProvinceController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Area\Province\ProvinceController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\Area\Province\ProvinceController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\Area\Province\ProvinceController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'districts', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Area\District\DistrictController::index');
	$routes->get('export/(:segment)', 'Panel\Area\District\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Area\District\DistrictController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\District\DistrictController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Area\District\DistrictController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\Area\District\DistrictController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\Area\District\DistrictController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\Area\District\DistrictController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\District\DistrictController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Area\District\DistrictController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\Area\District\DistrictController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\Area\District\DistrictController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'subdistricts', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Area\SubDistrictController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Area\SubDistrictController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\SubDistrictController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Area\SubDistrictController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\Area\SubDistrictController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\Area\SubDistrictController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\Area\SubDistrictController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\SubDistrictController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Area\SubDistrictController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\Area\SubDistrictController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\Area\SubDistrictController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'villages', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Area\VillageController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Area\VillageController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\VillageController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Area\VillageController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\Area\VillageController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\Area\VillageController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\Area\VillageController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Area\VillageController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Area\VillageController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\Area\VillageController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\Area\VillageController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel . 'navigation_groups', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\NavigationGroupController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\NavigationGroupController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\NavigationGroupController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\NavigationGroupController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\NavigationGroupController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\NavigationGroupController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\NavigationGroupController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\NavigationGroupController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\NavigationGroupController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\NavigationGroupController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\NavigationGroupController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel . 'navigations', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\NavigationController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\NavigationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\NavigationController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\NavigationController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\NavigationController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\NavigationController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\NavigationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\NavigationController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\NavigationController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\NavigationController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\NavigationController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'gallerys', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\GalleryController::index');

	if (ENVIRONMENT !== 'development') {
	 $routes->get('get', 'Panel\GalleryController::get', ['filter' => 'request']);
	 $routes->add('list', 'Panel\GalleryController::list', ['filter' => 'request']);
	 $routes->post('store', 'Panel\GalleryController::store');
	 $routes->post('update', 'Panel\GalleryController::update');
	 $routes->delete('delete', 'Panel\GalleryController::delete');
	} else {
	 $routes->add('get', 'Panel\GalleryController::get', ['filter' => 'request']);
	 $routes->add('list', 'Panel\GalleryController::list', ['filter' => 'request']);
	 $routes->add('store', 'Panel\GalleryController::store');
	 $routes->add('update', 'Panel\GalleryController::update');
	 $routes->add('delete', 'Panel\GalleryController::delete');
	}
});

$routes->group($routePanel.'files', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\FileController::index');

	if (ENVIRONMENT !== 'development') {
	 $routes->get('get', 'Panel\FileController::get', ['filter' => 'request']);
	 $routes->add('list', 'Panel\FileController::list', ['filter' => 'request']);
	 $routes->post('store', 'Panel\FileController::store', ['filter' => 'protected']);
	 $routes->post('update', 'Panel\FileController::update', ['filter' => 'protected']);
	 $routes->delete('delete', 'Panel\FileController::delete', ['filter' => 'protected']);
	} else {
	 $routes->add('get', 'Panel\FileController::get', ['filter' => 'request']);
	 $routes->add('list', 'Panel\FileController::list', ['filter' => 'request']);
	 $routes->add('store', 'Panel\FileController::store', ['filter' => 'protected']);
	 $routes->add('update', 'Panel\FileController::update', ['filter' => 'protected']);
	 $routes->add('delete', 'Panel\FileController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'roles', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\RoleController::index');
	$routes->get('(:segment)/manage', 'Panel\RoleController::manage');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\RoleController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\RoleController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\RoleController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\RoleController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\RoleController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\RoleController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\RoleController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\RoleController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\RoleController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\RoleController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'users', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\UserController::index');
	$routes->get('(:segment)/manage', 'Panel\UserController::detail');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\UserController::get', ['filter' => 'request']);
		$routes->get('count', 'Panel\UserController::count', ['filter' => 'request']);
		$routes->add('list', 'Panel\UserController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\UserController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\UserController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\UserController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\UserController::get', ['filter' => 'request']);
		$routes->add('count', 'Panel\UserController::count', ['filter' => 'request']);
		$routes->add('list', 'Panel\UserController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\UserController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\UserController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\UserController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'company', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\CompanyController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\CompanyController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\CompanyController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\CompanyController::store', ['filter' => 'protected']);
		$routes->post('update', 'Panel\CompanyController::update', ['filter' => 'protected']);
		$routes->delete('delete', 'Panel\CompanyController::delete', ['filter' => 'protected']);
	} else {
		$routes->add('get', 'Panel\CompanyController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\CompanyController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\CompanyController::store', ['filter' => 'protected']);
		$routes->add('update', 'Panel\CompanyController::update', ['filter' => 'protected']);
		$routes->add('delete', 'Panel\CompanyController::delete', ['filter' => 'protected']);
	}
});

$routes->group($routePanel.'about', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\AboutController::index');
});

/*
 * --------------------------------------------------------------------
 * Content Routes
 * --------------------------------------------------------------------
 */

$routes->group($routePanel.'years', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\YearController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\YearController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\YearController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\YearController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\YearController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\YearController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\YearController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\YearController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\YearController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\YearController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\YearController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel.'cabors', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\CaborController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\CaborController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\CaborController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\CaborController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\CaborController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\CaborController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\CaborController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\CaborController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\CaborController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\CaborController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\CaborController::delete', ['filter' => 'request']);
	}
});

// ========================================================================================== BREAK

$routes->group($routePanel.'youth_statistics', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Youth\Statistic\StatisticController::index');
	$routes->get('export/(:segment)', 'Panel\Youth\Statistic\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('show', 'Panel\Youth\Statistic\StatisticController::show', ['filter' => 'request']);
		$routes->get('get', 'Panel\Youth\Statistic\StatisticController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Statistic\StatisticController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Youth\Statistic\StatisticController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Youth\Statistic\StatisticController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Youth\Statistic\StatisticController::delete', ['filter' => 'request']);
	} else {
		$routes->add('show', 'Panel\Youth\Statistic\StatisticController::show', ['filter' => 'request']);
		$routes->add('get', 'Panel\Youth\Statistic\StatisticController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Statistic\StatisticController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Youth\Statistic\StatisticController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Youth\Statistic\StatisticController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Youth\Statistic\StatisticController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'youth_organizations', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Youth\Organization\OrganizationController::index');
	$routes->get('export/(:segment)', 'Panel\Youth\Organization\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('show', 'Panel\Youth\Organization\OrganizationController::show', ['filter' => 'request']);
		$routes->get('get', 'Panel\Youth\Organization\OrganizationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Organization\OrganizationController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Youth\Organization\OrganizationController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Youth\Organization\OrganizationController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Youth\Organization\OrganizationController::delete', ['filter' => 'request']);
	} else {
		$routes->add('show', 'Panel\Youth\Organization\OrganizationController::show', ['filter' => 'request']);
		$routes->add('get', 'Panel\Youth\Organization\OrganizationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Organization\OrganizationController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Youth\Organization\OrganizationController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Youth\Organization\OrganizationController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Youth\Organization\OrganizationController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'youth_trainings', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Youth\Training\TrainingController::index');
	$routes->get('(:segment)/manage', 'Panel\Youth\Training\TrainingController::detail');
	$routes->get('export/(:segment)', 'Panel\Youth\Training\ExportController::index');
	$routes->get('export/(:segment)/(:segment)', 'Panel\Youth\Training\ExportController::detail');

	if (ENVIRONMENT !== 'development') {
		$routes->get('show', 'Panel\Youth\Training\TrainingController::show', ['filter' => 'request']);
		$routes->get('get', 'Panel\Youth\Training\TrainingController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Training\TrainingController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Youth\Training\TrainingController::store', ['filter' => 'request']);
		$routes->post('import', 'Panel\Youth\Training\ImportController::index', ['filter' => 'request']);
		$routes->post('update', 'Panel\Youth\Training\TrainingController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Youth\Training\TrainingController::delete', ['filter' => 'request']);
	} else {
		$routes->add('show', 'Panel\Youth\Training\TrainingController::show', ['filter' => 'request']);
		$routes->add('get', 'Panel\Youth\Training\TrainingController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Training\TrainingController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Youth\Training\TrainingController::store', ['filter' => 'request']);
		$routes->add('import', 'Panel\Youth\Training\ImportController::index', ['filter' => 'request']);
		$routes->add('update', 'Panel\Youth\Training\TrainingController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Youth\Training\TrainingController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel.'youth_entrepreneurships', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::index');
	$routes->get('export/(:segment)', 'Panel\Youth\Entrepreneurship\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::get', ['filter' => 'request']);
		$routes->get('show', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::get', ['filter' => 'request']);
		$routes->add('show', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Youth\Entrepreneurship\EntrepreneurshipController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel.'youth_scouts', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Youth\Scout\ScoutController::index');
	$routes->get('export/(:segment)', 'Panel\Youth\Scout\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Youth\Scout\ScoutController::get', ['filter' => 'request']);
		$routes->get('show', 'Panel\Youth\Scout\ScoutController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Scout\ScoutController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Youth\Scout\ScoutController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Youth\Scout\ScoutController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Youth\Scout\ScoutController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Youth\Scout\ScoutController::get', ['filter' => 'request']);
		$routes->add('show', 'Panel\Youth\Scout\ScoutController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Scout\ScoutController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Youth\Scout\ScoutController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Youth\Scout\ScoutController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Youth\Scout\ScoutController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel.'youth_assets', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Youth\Asset\AssetController::index');
	$routes->get('export/(:segment)', 'Panel\Youth\Asset\ExportController::index');
	$routes->get('(:segment)/manage', 'Panel\Youth\Asset\AssetController::detail');
	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Youth\Asset\AssetController::get', ['filter' => 'request']);
		$routes->get('show', 'Panel\Youth\Asset\AssetController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Asset\AssetController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Youth\Asset\AssetController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Youth\Asset\AssetController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Youth\Asset\AssetController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Youth\Asset\AssetController::get', ['filter' => 'request']);
		$routes->add('show', 'Panel\Youth\Asset\AssetController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Youth\Asset\AssetController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Youth\Asset\AssetController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Youth\Asset\AssetController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Youth\Asset\AssetController::delete', ['filter' => 'request']);
	}
});

// ===================SPORT=======================

$routes->group($routePanel . 'sport_cabors', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Cabor\CaborController::index');
	$routes->get('export/(:segment)', 'Panel\Sport\Cabor\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('show', 'Panel\Sport\Cabor\CaborController::show', ['filter' => 'request']);
		$routes->get('get', 'Panel\Sport\Cabor\CaborController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Cabor\CaborController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Cabor\CaborController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Cabor\CaborController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Cabor\CaborController::delete', ['filter' => 'request']);
	} else {
		$routes->add('show', 'Panel\Sport\Cabor\CaborController::show', ['filter' => 'request']);
		$routes->add('get', 'Panel\Sport\Cabor\CaborController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Cabor\CaborController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Cabor\CaborController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Cabor\CaborController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Cabor\CaborController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'sport_organizations', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Organization\OrganizationController::index');
	$routes->get('export/(:segment)', 'Panel\Sport\Organization\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('show', 'Panel\Sport\Organization\OrganizationController::show', ['filter' => 'request']);
		$routes->get('get', 'Panel\Sport\Organization\OrganizationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Organization\OrganizationController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Organization\OrganizationController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Organization\OrganizationController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Organization\OrganizationController::delete', ['filter' => 'request']);
	} else {
		$routes->add('show', 'Panel\Sport\Organization\OrganizationController::show', ['filter' => 'request']);
		$routes->add('get', 'Panel\Sport\Organization\OrganizationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Organization\OrganizationController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Organization\OrganizationController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Organization\OrganizationController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Organization\OrganizationController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'sport_clubs', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Club\ClubController::index');
	$routes->get('export/(:segment)', 'Panel\Sport\Club\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Sport\Club\ClubController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Club\ClubController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Club\ClubController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Club\ClubController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Club\ClubController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Sport\Club\ClubController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Club\ClubController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Club\ClubController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Club\ClubController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Club\ClubController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'sport_certifications', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Certification\CertificationController::index');
	$routes->get('export/(:segment)', 'Panel\Sport\Certification\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Sport\Certification\CertificationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Certification\CertificationController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Certification\CertificationController::store', ['filter' => 'request']);
		$routes->post('import', 'Panel\Sport\Certification\ImportController::index', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Certification\CertificationController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Certification\CertificationController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Sport\Certification\CertificationController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Certification\CertificationController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Certification\CertificationController::store', ['filter' => 'request']);
		$routes->add('import', 'Panel\Sport\Certification\ImportController::index', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Certification\CertificationController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Certification\CertificationController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'sport_atlets', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Atlet\AtletController::index');
	$routes->get('(:segment)/manage', 'Panel\Sport\Atlet\AtletController::detail');
	$routes->get('export/(:segment)', 'Panel\Sport\Atlet\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('count', 'Panel\Sport\Atlet\AtletController::count', ['filter' => 'request']);
		$routes->get('get', 'Panel\Sport\Atlet\AtletController::get', ['filter' => 'request']);
		$routes->get('show', 'Panel\Sport\Atlet\AtletController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Atlet\AtletController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Atlet\AtletController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Atlet\AtletController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Atlet\AtletController::delete', ['filter' => 'request']);
	} else {
		$routes->add('count', 'Panel\Sport\Atlet\AtletController::count', ['filter' => 'request']);
		$routes->add('get', 'Panel\Sport\Atlet\AtletController::get', ['filter' => 'request']);
		$routes->add('show', 'Panel\Sport\Atlet\AtletController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Atlet\AtletController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Atlet\AtletController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Atlet\AtletController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Atlet\AtletController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'sport_championships', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Championship\ChampionshipController::index');
	$routes->get('(:segment)/manage', 'Panel\Sport\Championship\ChampionshipController::detail');
	$routes->get('export/(:segment)', 'Panel\Sport\Championship\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Sport\Championship\ChampionshipController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Championship\ChampionshipController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Championship\ChampionshipController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Championship\ChampionshipController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Championship\ChampionshipController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Sport\Championship\ChampionshipController::get', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Championship\ChampionshipController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Championship\ChampionshipController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Championship\ChampionshipController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Championship\ChampionshipController::delete', ['filter' => 'request']);
	}
});

$routes->group($routePanel . 'sport_achievements', ['filter' => 'login'], function ($routes) {
	$routes->get('/', 'Panel\Sport\Achievement\AchievementController::index');
	$routes->get('export/(:segment)', 'Panel\Sport\Achievement\ExportController::index');

	if (ENVIRONMENT !== 'development') {
		$routes->get('count', 'Panel\Sport\Achievement\AchievementController::count');
		$routes->get('get', 'Panel\Sport\Achievement\AchievementController::get');
		$routes->get('show', 'Panel\Sport\Achievement\AchievementController::show');
		$routes->add('list', 'Panel\Sport\Achievement\AchievementController::list');
		$routes->post('store', 'Panel\Sport\Achievement\AchievementController::store');
		$routes->post('update', 'Panel\Sport\Achievement\AchievementController::update');
		$routes->post('import', 'Panel\Sport\Achievement\ImportController::index');
		$routes->delete('delete', 'Panel\Sport\Achievement\AchievementController::delete');
		$routes->post('approve', 'Panel\Sport\Achievement\AchievementController::approve');
	} else {
		$routes->add('count', 'Panel\Sport\Achievement\AchievementController::count');
		$routes->add('get', 'Panel\Sport\Achievement\AchievementController::get');
		$routes->add('show', 'Panel\Sport\Achievement\AchievementController::show');
		$routes->add('list', 'Panel\Sport\Achievement\AchievementController::list');
		$routes->add('store', 'Panel\Sport\Achievement\AchievementController::store');
		$routes->add('update', 'Panel\Sport\Achievement\AchievementController::update');
		$routes->add('import', 'Panel\Sport\Achievement\ImportController::index');
		$routes->add('delete', 'Panel\Sport\Achievement\AchievementController::delete');
		$routes->add('approve', 'Panel\Sport\Achievement\AchievementController::approve');
	}
});

$routes->group($routePanel.'sport_assets', ['filter' => 'login'], function($routes) {
	$routes->get('/', 'Panel\Sport\Asset\AssetController::index');
	$routes->get('export/(:segment)', 'Panel\Sport\Asset\ExportController::index');
	$routes->get('(:segment)/manage', 'Panel\Sport\Asset\AssetController::detail');
	if (ENVIRONMENT !== 'development') {
		$routes->get('get', 'Panel\Sport\Asset\AssetController::get', ['filter' => 'request']);
		$routes->get('show', 'Panel\Sport\Asset\AssetController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Asset\AssetController::list', ['filter' => 'request']);
		$routes->post('store', 'Panel\Sport\Asset\AssetController::store', ['filter' => 'request']);
		$routes->post('update', 'Panel\Sport\Asset\AssetController::update', ['filter' => 'request']);
		$routes->delete('delete', 'Panel\Sport\Asset\AssetController::delete', ['filter' => 'request']);
	} else {
		$routes->add('get', 'Panel\Sport\Asset\AssetController::get', ['filter' => 'request']);
		$routes->add('show', 'Panel\Sport\Asset\AssetController::show', ['filter' => 'request']);
		$routes->add('list', 'Panel\Sport\Asset\AssetController::list', ['filter' => 'request']);
		$routes->add('store', 'Panel\Sport\Asset\AssetController::store', ['filter' => 'request']);
		$routes->add('update', 'Panel\Sport\Asset\AssetController::update', ['filter' => 'request']);
		$routes->add('delete', 'Panel\Sport\Asset\AssetController::delete', ['filter' => 'request']);
	}
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
