<?php namespace App\Models\Auth;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class RegisterModel extends Model
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

  public function attemptRegister(array $request)
  {
    if (regexUsername($request['username']) == false) {
      return requestOutput(411, 'Format <strong>Username</strong> yang Anda gunakan tidak benar');
    }

    if (in_array($request['username'], $this->configIonix->blockedUsername)) {
      return requestOutput(400, '<strong>Username</strong> mengandung unsur kata-kata yang dilarang');
    }

    if ($this->libIonix->getUserData(['username' => $request['username']])->getNumRows() == true) {
      return requestOutput(406, '<strong>Username</strong> sudah digunakan oleh <strong>Pengguna</strong> lain. Tidak dapat menggunakan <strong>Username</strong> yang sama');
    }

    if (regexEmail($request['email']) == false) {
      return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
    }

    if (in_array(explode('@', $request['email'])[0], $this->configIonix->blockedUsername)) {
      return requestOutput(400, '<strong>Email</strong> mengandung unsur kata-kata yang dilarang');
    }

    if ($this->libIonix->getUserData(['email' => $request['email']])->getNumRows() == true) {
      return requestOutput(406, '<strong>Email</strong> sudah digunakan oleh <strong>Pengguna</strong> lain. Tidak dapat menggunakan <strong>Email</strong> yang sama');
    }

    if (regexPassword($this->request->getPost('password')) == false) {
      return requestOutput(411, 'Format <strong>Kata Sandi</strong> tidak sesuai dengan yang diizinkan');
    }

    $query = (object) [
      'create'	=> !is_dir($this->configIonix->uploadsFolder['user'].'/'.$request['uuid']) ? mkdir($this->configIonix->uploadsFolder['user'].'/'.$request['uuid'], 0777, true) : NULL,
      'insert'  => $this->libIonix->insertQuery('users', $request),
    ];

    $output = [
      'update'  => $this->libIonix->updateQuery('user_info', ['user_id' => $query->insert], ['workunit_id' => $this->libIonix->getQuery('workunits', NULL, ['workunit_code' => $this->configIonix->defaultWorkunit])->getRow()->workunit_id]),
      'url'     => core_url('login'),
      'flash'   => $this->session->setFlashdata([
        'alertType'    => 'success',
        'alertMessage' => '<strong>Selamat</strong>! Anda telah berhasil <strong>mendaftarkan</strong> Akun. Anda dapat <strong>Login</strong> dengan Akun yang baru saja dibuat.',
      ]),
    ];

    return requestOutput(201, NULL, $output);
  }

  // -------------------------------------------------------------------

} // End of Name Model Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/RegisterModel.php
 * Location: ./app/Models/Auth/RegisterModel.php
 * -----------------------------------------------------------------------
 */
