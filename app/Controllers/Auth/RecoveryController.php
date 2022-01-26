<?php namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use App\Models\Auth\RecoveryModel;

/**
 * Class RecoveryController
 *
 * @package App\Controllers
 */
class RecoveryController extends BaseController
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
		$this->modRecovery 		= new RecoveryModel();
	}

	public function index()
	{
		$parameters = [
			'reset_token' 			=> uri_segment(1),
			'reset_updated_at' 	=> NULL,
		];

		if ($this->configIonix->allowForgot == true) {
			if ($this->libIonix->getQuery('password_resets', NULL, $parameters)->getNumRows() == true) {
				if (parseDateDiff($this->libIonix->getQuery('password_resets', NULL, $parameters)->getRow()->reset_expired)->getSeconds() < 0) {
					return view($this->configIonix->viewAuth['recovery'], $this->libIonix->appInit());
				}

				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			return redirect()->to(core_url());
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	public function attemptRecovery()
	{
		$parameters = [
			'reset_token' 			=> $this->request->getPost('token'),
			'reset_updated_at' 	=> NULL,
		];

		$request = [
			'password'					=> $this->request->getPost('password'),
			'repassword'				=> $this->request->getPost('repassword'),
		];

		$this->validation->setRules([
	    'password' 		=> 'required',
			'repassword' 	=> 'required_with[password]|matches[password]',
		]);

		if ($this->validation->run($request) == false) {
			return requestOutput(411);
		}

		if (regexPassword($this->request->getPost('password')) == false) {
     return requestOutput(406, 'Format <strong>Kata Sandi</strong> tidak sesuai dengan yang diizinkan');
    }

		if ($this->libIonix->getQuery('password_resets', NULL, $parameters)->getNumRows() == false) {
			return requestOutput(404, '<strong>Akses Token</strong> tidak ditemukan atau tidak valid');
		}

		if (parseDateDiff($this->libIonix->getQuery('password_resets', NULL, $parameters)->getRow()->reset_expired)->getSeconds() > 0) {
			return requestOutput(403, '<strong>Akses Token</strong> sudah kadaluarsa dan tidak dapat digunakan');
		}

		return $this->modRecovery->attemptRecovery($this->libIonix->getQuery('password_resets', NULL, $parameters)->getRow());
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/RecoveryController.php
 * Location: ./app/Controllers/Auth/RecoveryController.php
 * -----------------------------------------------------------------------
 */
