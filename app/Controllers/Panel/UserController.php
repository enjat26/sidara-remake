<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\RoleModel;
use App\Models\UserModel;

/**
 * Class UserController
 *
 * @package App\Controllers
 */
class UserController extends BaseController
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
		$this->modCountry			= new CountryModel();
		$this->modRole 				= new RoleModel();
		$this->modUser 				= new UserModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modRole'				=> $this->modRole,
			'modUser'				=> $this->modUser,
		];

		return view('panels/users/users', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		$parameters = [
			'uuid' 	=> $this->libIonix->Decode(uri_segment(2)),
		];

		if ($this->modUser->fetchData($parameters)->countAllResults() == true) {
			if ($this->libIonix->getUserData($parameters, 'object')->uuid == $this->libIonix->getUserData(NULL, 'object')->uuid) {
				return redirect()->to(panel_url('profile'));
			}

			$data = [
				'modCountry'		=> $this->modCountry,
				'modRole'				=> $this->modRole,
				'clientData'		=> $this->libIonix->getUserData($parameters, 'object'),
			];

			return view('panels/users/user-detail', $this->libIonix->appInit($data));
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	/*
	 * --------------------------------------------------------------------
	 * Count Method
	 * --------------------------------------------------------------------
	 */

	public function count()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'user') {
			if ($this->request->getGet('format') == 'JSON') {
				return $this->countUserJSON();
			}
		}
	}

	private function countUserJSON()
	{
		if ($this->libIonix->Decode($this->request->getGet('id')) == 'total-stakeholder') {
			return requestOutput(200, NULL, $this->modUser->fetchData(['role_access <' => $this->configIonix->roleController], false, 'DESC', false)->countAllResults());
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'total-user') {
			return requestOutput(200, NULL, $this->modUser->fetchData()->countAllResults());
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'user') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getUserData(['uuid' => $this->libIonix->Decode($this->request->getGet('id'))], 'object'));
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getUserHTML($this->libIonix->getUserData(['uuid' => $this->libIonix->Decode($this->request->getGet('id'))], 'object'));
			}
		}
	}

	private function getUserHTML(object $userData)
	{
		if ($userData->cover) {
			$userCover = '<img src="'.core_url('content/user/'.$this->libIonix->Encode($userData->uuid).'/'.$this->libIonix->Encode($userData->cover)).'" alt="'.$userData->name.'" class="img-fluid">';
		} else {
			$userCover = '<img src="'.$this->configIonix->mediaFolder['image'].'default/cover.jpg'.'" alt="'.$userData->name.'" class="img-fluid">';
		}

		if ($userData->avatar) {
			$userAvatar = '<img src="'.core_url('content/user/'.$this->libIonix->Encode($userData->uuid).'/'.$this->libIonix->Encode($userData->avatar)).'" alt="'.$userData->name.'" class="img-thumbnail rounded-circle">';
		} else {
			$userAvatar = '<span class="avatar-title rounded-circle bg-light font-size-24" style="color: #'.$userData->role_color.';">
											'.substr($userData->name, 0, 1).'
										</span>';
		}

		$output = [
			'cover'			=> $userCover,
			'avatar'		=> $userAvatar,
			'uuid'			=> $userData->uuid,
			'username'	=> $userData->username,
			'name'			=> $userData->name,
			'roles'			=> $userData->role_name,
			'active'		=> $userData->active == true ? '<span class="badge rounded-pill bg-soft bg-success text-success font-size-12">Aktif</span>' : '<span class="badge rounded-pill bg-soft bg-danger text-danger font-size-12">Dibanned</span>',
			'bio'				=> $userData->bio ? $userData->bio : '<i>Belum menautkan biografi</i>',
			'address'		=> parseAddress($userData),
			'email'			=> $userData->email ? $userData->email : '-',
			'phone'			=> $userData->phone ? $userData->phone : '-',
		];
		return requestOutput(200, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'user') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listUserDT();
			}
		}
	}

	private function listUserDT()
	{
		$data = [];
		foreach ($this->modUser->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if ($row->avatar) {
				$userAvatar	= '<div class="d-flex align-items-center">
													<div class="avatar-sm">
															<img src="'.core_url('content/user/'.$this->libIonix->Encode($row->uuid).'/'.$this->libIonix->Encode($row->avatar)).'" alt="'.$row->name.'" class="img-thumbnail rounded-circle">
													</div>
											</div>';
			} else {
				$userAvatar = '<div class="d-flex align-items-center">
													<div class="avatar-sm">
															<span class="avatar-title rounded-circle font-size-14" style="background-color: '.hexToRGB($row->role_color, 18).';color: #'.$row->role_color.'">'.substr($row->name, 0, 1).'</span>
													</div>
											</div>';
			}

			if ($row->email) {
				$userEmail = $row->email;
			} else {
				$userEmail = '-';
			}

			$subArray[] = $userAvatar;
			$subArray[] = '<p class="text-truncate mb-0"><strong>'.$row->name.'</strong></p>
										<p class="text-muted mb-0">'.$row->uuid.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">@'.$row->username.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$userEmail.'</p>';
			$subArray[] = '<div class="text-center">
												<span class="badge rounded-pill font-size-12" style="background-color: '.hexToRGB($row->role_color, 18).';color: #'.$row->role_color.'">'.$row->role_name.'</span>
										</div>';
			$subArray[] = '<div class="d-flex align-items-center">
											<div class="form-check form-switch mx-auto mb-0">
													<input type="checkbox" name="status" class="form-check-input" onclick="updateMethod(false ,\''.$this->libIonix->Encode('active').'\', \''.$this->libIonix->Encode($row->uuid).'\');" '.toggleStatus($row->active).'>
											</div>
										</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="'.panel_url('u/'.$row->username).'" target="_blank"><i class="mdi mdi-eye font-size-16 align-middle text-primary me-1"></i>Lihat Profil</a>
														<a class="dropdown-item" href="'.panel_url('users/'.$this->libIonix->Encode($row->uuid).'/manage').'"><i class="mdi mdi-vector-link font-size-16 align-middle text-primary me-1"></i>Rincian & Kelola</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('user').'\', \''.$this->libIonix->Encode($row->uuid).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modUser->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modUser->fetchData()->get()->getNumRows(),
				"data"             => $data,
		];
		echo json_encode($output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'user') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addUser();
			} else {
				return $this->updateUser($this->libIonix->getUserData(['uuid' => $this->libIonix->Decode($this->request->getGet('id'))], 'object'));
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'password') {
			return $this->updatePassword($this->libIonix->getUserData(['uuid' => $this->libIonix->Decode($this->request->getGet('id'))], 'object'));
		}
	}

	private function addUser()
	{
		$request = [
			'uuid'			=> $this->libIonix->generateUUID(strtolower($this->request->getPost('username'))),
			'username'	=> strtolower($this->request->getPost('username')),
			'password'	=> !empty($this->request->getPost('password')) ? password_hash($this->request->getPost('password'), $this->configIonix->hashAlgorithm) : password_hash($this->configIonix->passwordDefault, $this->configIonix->hashAlgorithm),
			'name'			=> ucwords($this->request->getPost('name')),
			'email'			=> !empty($this->request->getPost('email')) ? strtolower($this->request->getPost('email')) : NULL,
			'role_code'	=> $this->request->getPost('role'),
			'active'		=> true,
		];

		if (regexUsername($request['username']) == false) {
      return requestOutput(411, 'Format <strong>Username</strong> yang Anda gunakan tidak benar');
    }

		if (in_array($request['username'], $this->configIonix->blockedUsername)) {
      return requestOutput(400, '<strong>Username</strong> mengandung unsur kata-kata yang dilarang');
    }

    if ($this->libIonix->getUserData(['username' => $request['username']])->getNumRows() == true) {
      return requestOutput(406, '<strong>Username</strong> sudah digunakan oleh <strong>Pengguna</strong> lain. Tidak dapat menggunakan <strong>Username</strong> yang sama');
    }

		if (!empty($this->request->getPost('password')) && regexPassword($this->request->getPost('password')) == false) {
      return requestOutput(411, 'Format <strong>Kata Sandi</strong> tidak sesuai dengan yang diizinkan');
    }

		if ($request['email'] && regexEmail($request['email']) == false) {
      return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
    }

    if ($request['email'] && in_array(explode('@', $request['email'])[0], $this->configIonix->blockedUsername)) {
      return requestOutput(400, '<strong>Email</strong> mengandung unsur kata-kata yang dilarang');
    }

    if ($request['email'] && $this->libIonix->getUserData(['email' => $request['email']])->getNumRows() == true) {
      return requestOutput(406, '<strong>Email</strong> sudah digunakan oleh <strong>Pengguna</strong> lain. Tidak dapat menggunakan <strong>Email</strong> yang sama');
    }

		$output = [
      'create'	=> !is_dir($this->configIonix->uploadsFolder['user'].'/'.$request['uuid']) ? mkdir($this->configIonix->uploadsFolder['user'].'/'.$request['uuid'], 0777, true) : NULL,
      'insert'  => $this->libIonix->insertQuery('users', $request),
    ];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['name'].'</strong> sebagai <strong>Pengguna</strong> baru', $output);
	}

	private function updateUser(object $userData)
	{
		$requestUser = [
			'name'			=> ucwords($this->request->getPost('fullname')),
			'email'			=> !empty($this->request->getPost('email')) ? strtolower($this->request->getPost('email')) : NULL,
			'role_code'	=> $this->request->getPost('role'),
		];

		$requestInfo = [
			'bio'									=> !empty($this->request->getPost('bio')) ? $this->request->getPost('bio') : NULL,
			'address'							=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'country_id'					=> !empty($this->request->getPost('country')) ? $this->request->getPost('country') : NULL,
			'province_id'					=> !empty($this->request->getPost('province')) ? $this->request->getPost('province') : NULL,
			'district_id'					=> !empty($this->request->getPost('district')) ? $this->request->getPost('district') : NULL,
			'sub_district_id'			=> !empty($this->request->getPost('subdistrict')) ? $this->request->getPost('subdistrict') : NULL,
			'village_id'					=> !empty($this->request->getPost('village')) ? $this->request->getPost('village') : NULL,
			'zip_code'						=> !empty($this->request->getPost('zipcode')) ? $this->request->getPost('zipcode') : NULL,
			'phone'				=> !empty($this->request->getPost('phone')) ? $this->request->getPost('phone') : NULL,
		];

		if ($requestUser['email'] && regexEmail($requestUser['email']) == false) {
			return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
		}

		if ($requestUser['email'] && in_array(explode('@', $requestUser['email'])[0], $this->configIonix->blockedUsername)) {
			return requestOutput(400, '<strong>Email</strong> mengandung unsur kata-kata yang dilarang');
		}

		if ($requestUser['email'] && $requestUser['email'] != $userData->email) {
			if ($this->libIonix->getUserData(['email' => $requestUser['email']])->getNumRows() > 0) {
				return requestOutput(406, '<strong>Email</strong> sudah digunakan oleh <strong>Pengguna Lain</strong>. Tidak dapat menggunakan <strong>Email</strong> yang sama');
			}
		}

		$output = [
			'updateUser'	=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], $requestUser),
			'updateInfo'	=> $this->libIonix->updateQuery('user_info', ['user_id' => $userData->user_id], $requestInfo),
		];

		return requestOutput(202, 'Berhasil merubah informasi pribadi pada <strong>Pengguna</strong> ini', $output);
	}

	private function updatePassword(object $userData)
	{
		$request = [
			'password' 					=> password_hash($this->request->getPost('password'), $this->configIonix->hashAlgorithm),
			'password_reset_at' => date('Y-m-d H:i:s'),
		];

		if (regexPassword($this->request->getPost('password')) == false) {
		 return requestOutput(411, 'Format <strong>Kata Sandi</strong> tidak sesuai dengan yang diizinkan');
		}

		$output = [
			'update'   	=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], $request),
		];

		return requestOutput(202, 'Berhasil mengatur ulang <strong>Kata Sandi</strong> pada <strong>Pengguna</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Update Method
	 * --------------------------------------------------------------------
	 */

	public function update()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'active') {
			return $this->updateStatus($this->libIonix->getUserData(['uuid' => $this->libIonix->Decode($this->request->getGet('id'))], 'object'));
		}
	}

	private function updateStatus(object $userData)
	{
		if ($userData->uuid == $this->libIonix->getUserData(NULL, 'object')->uuid) {
			return requestOutput(406, 'Tidak dapat merubah status pada Akun Anda sendiri');
		}

		if ($userData->uuid != $this->libIonix->getUserData(NULL, 'object')->uuid && $userData->role_access > $this->libIonix->getUserData(NULL, 'object')->role_access) {
			return requestOutput(406, 'Tidak dapat merubah status pada Hak Akses yang lebih tinggi dari Anda');
		}

		if ($userData->active == false) {
			$output = [
				'status'  => 'Aktif',
				'update'	=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], ['active' => true]),
			];
		} elseif ($userData->active == true) {
			$output = [
				'status'  			=> 'Tidak Aktif/Dibanned',
				'update'				=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], ['active' => false]),
			];
		}

		return requestOutput(202, 'Berhasil merubah <strong>'.$userData->name.'</strong> menjadi <strong>'.$output['status'].'</strong>', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'user') {
			return $this->deleteUser($this->libIonix->getUserData(['uuid' => $this->libIonix->Decode($this->request->getGet('id'))], 'object'));
		}
	}

	private function deleteUser(object $userData)
	{
		if ($userData->uuid == $this->libIonix->getUserData(NULL, 'object')->uuid) {
			return requestOutput(406, 'Tidak dapat menghapus Akun sendiri, metode tersebut tidak diizinkan');
		}

		if (is_dir($this->configIonix->uploadsFolder['user'].'/'.$userData->uuid)) {
			delete_files($this->configIonix->uploadsFolder['user'].'/'.$userData->uuid, TRUE);
			rmdir($this->configIonix->uploadsFolder['user'].'/'.$userData->uuid);
		}

		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('users', ['uuid' => $userData->uuid]),
		];
		return requestOutput(202, 'Berhasil menghapus <strong>Pengguna</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: UserController.php
 * Location: ./app/Controllers/Panel/UserController.php
 * -----------------------------------------------------------------------
 */
