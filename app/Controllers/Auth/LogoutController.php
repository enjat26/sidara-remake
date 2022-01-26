<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;

/**
 * Class LogoutController
 *
 * @package App\Controllers
 */
class LogoutController extends BaseController
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
		$output = [
			'destroy'	=> $this->session->destroy(),
		];

		return redirect()->to(core_url('login'));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/LogoutController.php
 * Location: ./app/Controllers/Auth/LogoutController.php
 * -----------------------------------------------------------------------
 */
