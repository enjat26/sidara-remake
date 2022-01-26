<?php namespace App\Models\Auth;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class RecoveryModel extends Model
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
    //--------------------------------------------------------------------
    // Preload any models, libraries, etc, here.
    //--------------------------------------------------------------------
    // E.g.: $this->session = \Config\Services::session();

    // Load the config
    $this->configApp 	    = config('App');
    $this->configIonix 	  = config('Ionix');

    // Load the library
		$this->libIonix 		  = new Ionix();

    // Load Database
		$this->dbDefault 	    = \Config\Database::connect('default');

    // load parameters
    $this->session        = Services::session();
    $this->request 			  = Services::request();
    $this->response 			= Services::response();
    $this->agent 			    = $this->request->getUserAgent();
  }

  public function attemptRecovery(object $passwordResetData)
  {


    $output = [
      'update'   => $this->libIonix->updateQuery('users', ['email' => $passwordResetData->email], ['password' => password_hash($this->request->getPost('password'), $this->configIonix->hashAlgorithm), 'password_reset_at' => date('Y-m-d H:i:s')]),
      'remove'   => $this->libIonix->updateQuery('password_resets', ['email' => $passwordResetData->email], ['reset_token' => NULL]),
      'url'      => core_url('login'),
      'flash'    => $this->session->setFlashdata([
                      'alertType' => 'success',
                      'alertMessage' => '<strong>Kata Sandi</strong> Anda berhasil dirubah, sekarang Anda dapat <strong>Login</strong> dengan menggunakan <strong>Kata Sandi</strong> yang baru..'
                    ]),
    ];

    return requestOutput(202, NULL, $output);
  }

  // -------------------------------------------------------------------

} // End of Name Model Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/RecoveryModel.php
 * Location: ./app/Models/Auth/RecoveryModel.php
 * -----------------------------------------------------------------------
 */
