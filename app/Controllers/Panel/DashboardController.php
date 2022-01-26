<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

/**
 * Class DashboardController
 *
 * @package App\Controllers
 */
class DashboardController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */

	/**
	 * __construct ()
	 * --------------------------------------------------------------------
	 *
	 * Constructor
	 *
	 * NOTE: Not needed if not setting values or extending a Class.
	 *
	 */
	public function __construct()
	{

	}

	public function index()
	{

		return view('panels/dashboard', $this->libIonix->appInit());
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: DashboardController.php
 * Location: ./app/Controllers/Panel/DashboardController.php
 * -----------------------------------------------------------------------
 */
