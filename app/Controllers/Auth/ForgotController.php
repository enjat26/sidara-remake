<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\Auth\ForgotModel;

/**
 * Class ForgotController
 *
 * @package App\Controllers
 */
class ForgotController extends BaseController
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
		$this->modForgot 		= new ForgotModel();
	}

	public function index()
	{
		if ($this->configIonix->allowForgot == true) {
			return view($this->configIonix->viewAuth['forgot'], $this->libIonix->appInit());
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	public function attemptForgot()
	{
		$request = [
			'email'		=> strtolower($this->request->getPost('email')),
		];

		$this->validation->setRules([
	    'email' 	=> 'required|valid_email',
		]);

		if ($this->validation->run($request) == false) {
			return requestOutput(411, 'Format <strong> Email </strong> yang Anda gunakan tidak sesuai');
		}

		if ($this->libIonix->getUserData(['email' => $request['email']])->getNumRows() == false) {
			return requestOutput(404, 'Email <strong>'.$request['email'].'</strong> yang Anda gunakan tidak ditemukan atau tidak terdaftar!');
		}

		return $this->modForgot->attemptForgot($this->libIonix->getUserData(['email' => $request['email']], 'object'));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/ForgotController.php
 * Location: ./app/Controllers/Auth/ForgotController.php
 * -----------------------------------------------------------------------
 */
