<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

/**
 * Class AboutController
 *
 * @package App\Controllers
 */
class AboutController extends BaseController
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
		return view('panels/abouts/about', $this->libIonix->appInit());
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: AboutController.php
 * Location: ./app/Controllers/Panel/AboutController.php
 * -----------------------------------------------------------------------
 */
