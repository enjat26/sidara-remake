<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\Auth\LoginModel;

/**
 * Class LoginController
 *
 * @package App\Controllers
 */
class LoginController extends BaseController
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
		$this->modLogin 		= new LoginModel();
	}

	public function index()
	{
		return view($this->configIonix->viewAuth['login'], $this->libIonix->appInit());
	}

	public function attemptLogin()
	{
		$identityFilter = regexIdentity($this->request->getPost('identity'));

		$request = [
			$identityFilter->scope		=> $this->request->getPost('identity'),
			'password'								=> $this->request->getPost('password'),
			'year'										=> $this->request->getPost('year'),
			'remember'								=> $this->request->getPost('remember'),
		];

		$this->validation->setRules([
	    empty($this->request->getPost('identity')) ? 'identity' : $identityFilter->scope 	=> empty($this->request->getPost('identity')) ? 'required' : $identityFilter->rules,
	    'password' 																																				=> 'required|min_length['.$this->configIonix->minimumPasswordLength.']',
			'year'																																						=> 'required',
		]);

		if ($this->validation->run($request) == false) {
			return requestOutput(411);
		}

		if ($identityFilter->status == false) {
			return requestOutput(411, 'Format <strong>Identitas</strong> yang Anda gunakan tidak sesuai '.$identityFilter->scope);
		}

		if ($this->libIonix->getUserData([$identityFilter->scope => $request[$identityFilter->scope]])->getNumRows() == false) {
			return requestOutput(404, '<strong>'.ucwords($identityFilter->caption).'</strong> yang Anda gunakan tidak ditemukan atau tidak terdaftar');
		}

		return $this->modLogin->attemptLogin($this->libIonix->getUserData([$identityFilter->scope => $this->request->getPost('identity')], 'object'));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/LoginController.php
 * Location: ./app/Controllers/Auth/LoginController.php
 * -----------------------------------------------------------------------
 */
