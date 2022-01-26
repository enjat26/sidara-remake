<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\Auth\RegisterModel;

/**
 * Class RegisterController
 *
 * @package App\Controllers
 */
class RegisterController extends BaseController
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
		$this->modRegister 		= new RegisterModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		if ($this->configIonix->allowRegistration == true) {
			return view($this->configIonix->viewAuth['register'], $this->libIonix->appInit());
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function attemptRegister()
	{
		$request = [
			'uuid'					=> $this->libIonix->generateUUID(strtolower($this->request->getPost('username'))),
			'name'					=> ucwords($this->request->getPost('name')),
			'username'			=> strtolower($this->request->getPost('username')),
			'email'					=> strtolower($this->request->getPost('email')),
			'password'			=> password_hash($this->request->getPost('password'), $this->configIonix->hashAlgorithm),
			'role_code'			=> $this->configIonix->defaultRole,
			'active'				=> true,
		];

		$this->validation->setRules([
			'uuid' 				=> 'required',
			'name' 				=> 'required',
			'username' 		=> regexIdentity($request['username'])->rules,
	    'email' 			=> regexIdentity($request['email'])->rules,
			'password' 		=> 'required|min_length['.$this->configIonix->minimumPasswordLength.']',
			'role_code' 	=> 'required',
		]);

		if ($this->validation->run($request) == false) {
			return requestOutput(411, $this->validation->listErrors());
		}

		return $this->modRegister->attemptRegister($request);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/RegisterController.php
 * Location: ./app/Controllers/Auth/RegisterController.php
 * -----------------------------------------------------------------------
 */
