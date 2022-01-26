<?php namespace App\Models\Auth;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class ForgotModel extends Model
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

  public function attemptForgot(object $userData)
  {
    $reset = [
      'email'          => $userData->email,
      'reset_token'    => random_string('sha1'),
      'reset_expired'  => date('Y-m-d H:i:s', time()+$this->configIonix->recoveryLength),
    ];

    $data = [
     'name' 	         => $userData->name,
     'url'		         => core_url('recovery/'.$reset['reset_token']),
     'expired_at'      => $reset['reset_expired'],
    ];

    $config = [
      'to'             => $userData->email,
      'subject'        => 'Permintaan Pemulihan Kata Sandi',
      'body'           => view($this->configIonix->viewAuth['email']['forgot'], $this->libIonix->appInit($data)),
    ];

    $send = (object) [
      'email' => $this->libIonix->sendEmail($config),
    ];

    if ($send->email->status == false) {
      if (ENVIRONMENT !== 'development') {
        return requestOutput(500);
      }

      return requestOutput(503, 'Permintaan Pemulihan Kata Sandi tidak dapat diproses karena <br/>'.$send->email->debug);
    }

    $output = [
      'insert'  => $this->libIonix->insertQuery('password_resets', $reset),
      'url'     => core_url('login'),
      'token'   => $reset['reset_token'],
      'flash'   => $this->session->setFlashdata([
                     'alertType'      => 'success',
                     'alertMessage'   => 'Selamat! Permintaan pemulihan <strong>Kata Sandi</strong> telah berhasil dikirim, harap periksa <strong>Kotak Masuk</strong> Anda.'
                   ]),
    ];

    return requestOutput(201, NULL, $output);
  }

  // -------------------------------------------------------------------

} // End of Name Model Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/ForgotModel.php
 * Location: ./app/Models/Auth/ForgotModel.php
 * -----------------------------------------------------------------------
 */
