<?php namespace App\Controllers;

/**
 * Class HomeController
 *
 * @package App\Controllers
 */
class HomeController extends BaseController
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
		$data = [
			'navigations'	=> 'home',
		];

		return view('pages/home', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: HomeController.php
 * Location: ./app/Controllers/HomeController.php
 * -----------------------------------------------------------------------
 */
