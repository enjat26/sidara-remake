<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\UserModel;

/**
 * Class ProfileController
 *
 * @package App\Controllers
 */
class ProfileController extends BaseController
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
		$this->modCountry 		= new CountryModel();
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
			'modCountry'			=> $this->modCountry,
			'queryTelegram'		=> $this->libIonix->builderQuery('notification_telegrams')->where(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id, 'notification_telegram_token' => NULL, 'notification_telegram_pair' => true])->orderBy('notification_telegram_id', 'DESC'),
		];

		return view('panels/profile/profile', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		$parameters = [
			'username' 	=> uri_segment(2),
			'active'	 	=> true,
		];

		if ($this->modUser->fetchData($parameters, false, 'DESC', false)->countAllResults() == true) {
			if ($this->libIonix->getUserData(NULL, 'object')->username == $parameters['username']) {
				return redirect()->to(panel_url('profile'));
			}

			$data = [
				'clientData'		=> $this->libIonix->getUserData($parameters, 'object'),
			];

			return view('panels/profile/detail', $this->libIonix->appInit($data));
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'profile') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getUserData(NULL, 'object'));
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getUserHTML($this->libIonix->getUserData(NULL, 'object'));
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'telegram') {
			if ($this->request->getGet('format') == 'JSON') {
				$request = [
					'user_id'												=> $this->libIonix->getUserData(NULL, 'object')->user_id,
					'notification_telegram_token'		=> random_string('sha1'),
				];

				$output = [
					'insert'	=> $this->libIonix->insertQuery('notification_telegrams', $request),
					'token'		=> '/pair@'.$request['notification_telegram_token'],
				];

				return requestOutput(200, NULL, $output);
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'sosprov') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getQuery('social_provider', NULL, ['sosprov_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getRow());
			}
		}
	}

	private function getUserHTML(object $userData)
	{
		if ($userData->cover) {
			$userCover = '<button type="button" class="btn" style="position: absolute!important;" data-scope="'.$this->libIonix->Encode('image').'" data-val="'.$this->libIonix->Encode('cover').'" key="upd-cover"><i class="mdi mdi-pencil-box-outline font-size-20 text-white"></i></button>
										<img src="'.core_url('content/user/'.$this->libIonix->Encode($userData->uuid).'/'.$this->libIonix->Encode($userData->cover)).'" alt="'.$userData->name.'" class="img-fluid">';
		} else {
			$userCover = '<button type="button" class="btn" style="position: absolute!important;" data-scope="'.$this->libIonix->Encode('image').'" data-val="'.$this->libIonix->Encode('cover').'" key="upd-cover"><i class="mdi mdi-pencil-box-outline font-size-20 text-white"></i></button>
										<img src="'.$this->configIonix->mediaFolder['image'].'default/cover.jpg'.'" alt="'.$userData->name.'" class="img-fluid">';
		}

		if ($userData->avatar) {
			$userAvatar = '<div class="user-avatar">
												<img src="'.core_url('content/user/'.$this->libIonix->Encode($userData->uuid).'/'.$this->libIonix->Encode($userData->avatar)).'" alt="'.$userData->name.'" class="img-thumbnail rounded">
												<div class="d-flex align-items-center justify-content-center avatar-inner bg-light">
														<a href="javascript:void(0);" data-scope="'.$this->libIonix->Encode('image').'" data-val="'.$this->libIonix->Encode('avatar').'" key="upd-avatar"><i class="mdi mdi-pencil-box-outline font-size-20"></i></a>
												</div>
										</div>';
		} else {
			$userAvatar = '<span class="avatar-title user-avatar rounded font-size-24" style="background-color: '.hexToRGB($userData->role_color, 18).';color: #'.$userData->role_color.';">
											'.substr($userData->name, 0, 1).'
											<div class="d-flex align-items-center justify-content-center avatar-inner bg-light">
													<a href="javascript:void(0);" data-scope="'.$this->libIonix->Encode('image').'" data-val="'.$this->libIonix->Encode('avatar').'" key="upd-avatar"><i class="mdi mdi-pencil-box-outline font-size-20"></i></a>
											</div>
										</span>';
		}

		$output = [
			'cover'			=> $userCover,
			'avatar'		=> $userAvatar,
			'uuid'			=> $userData->uuid,
			'name'			=> parseFullName($userData->name, $userData->role_access),
			'username'	=> $userData->username,
			'safe'			=> $userData->safe_mode,
			'bio'				=> $userData->bio ? $userData->bio : '<i>Belum memiliki biografi</i>',
			'address'		=> parseAddress($userData, true, false),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id])->getResult());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->listSocialHTML($this->libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id])->getResult());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'activity') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->builderQuery('auth_login')->where(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id])->orderBy('login_created_at', 'DESC')->get()->getResult());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->listActivityHTML($this->libIonix->builderQuery('auth_login')->where(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id])->orderBy('login_created_at', 'DESC')->get()->getResult());
			}
		}
	}

	private function listSocialHTML(array $socialData)
	{
		foreach ($socialData as $row) {
			if ($row->user_id == $this->libIonix->getUserData(NULL, 'object')->user_id) {
				$buttonAction = '<div class="float-end">
													<button type="button" class="btn-close" aria-label="Close" data-scope="'.$this->libIonix->Encode('social').'" data-val="'.$this->libIonix->Encode($row->sosmed_id).'" key="del-social"></button>
												</div>';
			} else {
				$buttonAction = '';
			}

			echo '<div class="col-4">
								<div class="social-source text-center mt-3">
										'.$buttonAction.'
										<div class="avatar-xs mx-auto mb-3">
												<span class="avatar-title rounded-circle font-size-16" style="background-color: #'.$row->sosprov_color.'">
														<i class="mdi mdi-'.$row->sosprov_name.' text-white"></i>
												</span>
										</div>
										<a href="'.$row->sosprov_url.$row->sosmed_key.'" target="_blank">
											<h5 class="font-size-15 mb-0">'.ucwords($row->sosprov_name).'</h5>
											<p class="text-muted mb-0">@'.$row->sosmed_key.'</p>
										</a>
								</div>
						</div>';
		} exit;
	}

	private function listActivityHTML(array $activityData)
	{
		$i = 1;
		foreach ($activityData as $row) {
			if ($row->login_success == true) {
				$activityColor 	= 'text-primary';
				$activityText 	= $row->login_message.' dengan menggunakan '.explode('|', $row->login_browser)[0].' pada perangkat <strong>'.$row->login_os.'</strong>.';
			} else {
				$activityColor 	= 'text-danger';
				$activityText = $row->login_message;
			}

			echo '<li class="event-list">
								<div class="event-timeline-dot">
										<i class="mdi mdi-arrow-right-bold-hexagon-outline font-size-18"></i>
								</div>
								<div class="media">
										<div class="me-3">
												<i class="mdi mdi-timeline-check h2 '.$activityColor.' ms-2"></i>
										</div>
										<div class="media-body">
												<p class="text-muted text-justify">'.$activityText.'</p>

												<div class="text-end">
													<small><i class="mdi mdi-clock-outline font-size-12 align-middle me-1"></i> '.parseDateDiff($row->login_created_at)->getRelative().'</small>
												</div>
										</div>
								</div>
						</li>';
		} exit;
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'profile') {
			return $this->updateProfile($this->libIonix->getUserData(NULL, 'object'));
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'password') {
			return $this->updatePassword($this->libIonix->getUserData(NULL, 'object'));
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addSocial();
			} else {
				return $this->updateSocial($this->libIonix->getQuery('social_media', NULL, ['sosmed_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getRow());
			}
		}
	}

	private function updateProfile(object $userData)
	{
		$requestUser = [
			'name'		=> ucwords($this->request->getPost('name')),
			'email'		=> strtolower($this->request->getPost('email')),
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
			'phone'								=> !empty($this->request->getPost('phone')) ? $this->request->getPost('phone') : NULL,
		];

		if (!empty($this->request->getPost('email')) && regexEmail($requestUser['email']) == false) {
			return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
		}

		if ($requestUser['email'] != $userData->email) {
			if (!empty($this->request->getPost('email')) && in_array(explode('@', $requestUser['email'])[0], $this->configIonix->blockedUsername)) {
				return requestOutput(400, '<strong>Email</strong> mengandung unsur kata-kata yang dilarang');
			}

			if ($this->libIonix->getUserData(['email' => $requestUser['email']])->getNumRows() == true) {
				return requestOutput(406, 'Email sudah digunakan oleh pengguna lain, tidak dapat menggunakan Email yang sama.');
			}
		}

		$output = [
			'updateUser'	=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], $requestUser),
			'updateInfo'	=> $this->libIonix->updateQuery('user_info', ['user_id' => $userData->user_id], $requestInfo),
		];

		return requestOutput(202, 'Berhasil merubah informasi pribadi pada Akun Anda', $output);
	}

	private function updatePassword(object $userData)
	{
		if (regexPassword($this->request->getPost('password')) == false) {
		 return requestOutput(406, 'Format Kata Sandi tidak sesuai dengan yang diizinkan');
		}

		$request = [
			'password' 					=> password_hash($this->request->getPost('password'), $this->configIonix->hashAlgorithm),
			'password_reset_at' => date('Y-m-d H:i:s'),
		];

		$output = [
			'update'   => $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], $request),
		];

		return requestOutput(202, 'Berhasil mengatur ulang <strong>Kata Sandi</strong> Anda dengan yang baru', $output);
	}

	private function addSocial()
	{
		$request = [
			'sosprov_id'	=> $this->libIonix->Decode($this->request->getPost('sosprov')),
			'user_id'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'sosmed_key'	=> strtolower($this->request->getPost('sosmed')),
		];

		if (regexUsername($request['sosmed_key']) == false) {
			return requestOutput(411, 'Format <strong>Username</strong> yang Anda gunakan tidak benar');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('social_media', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>Media Sosial</strong> baru', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Update Method
	 * --------------------------------------------------------------------
	 */

	public function update()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->updateImage($this->libIonix->getUserData(NULL, 'object'));
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'safe') {
			return $this->updateSafeMode($this->libIonix->getUserData(NULL, 'object'));
		}
	}

	private function updateImage(object $userData)
	{
		$data = [
			'path' 			=> $this->configIonix->uploadsFolder['user'].'/'.$userData->uuid,
			'fileName'	=> $this->request->getFile('image')->getRandomName(),
		];

		$output = [
			'upload'	=> $this->request->getFile('image')->move($data['path'], $data['fileName'], true),
			'update'	=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], [$this->libIonix->Decode($this->request->getGet('id')) => $data['fileName']]),
		];

		return requestOutput(202, 'Berhasil mengunggah gambar yang dipilih', $output);
	}

	private function updateSafeMode(object $userData)
	{
		if ($userData->safe_mode == false) {
			$output = [
				'message'		=> 'Berhasil <strong>menyembunyikan</strong> informasi Anda dari orang lain',
				'update'		=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], ['safe_mode' => true]),
			];
		} else {
			$output = [
				'message'		=> 'Berhasil merubah status menjadi <strong>terlihat</strong> untuk orang lain.',
				'update'		=> $this->libIonix->updateQuery('users', ['uuid' => $userData->uuid], ['safe_mode' => false]),
			];
		}

		return requestOutput(202, $output['message'], $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->deleteImage($this->libIonix->getUserData(NULL, 'array'));
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			return $this->deleteSocial();
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'activity') {
			return $this->deleteActivity($this->libIonix->getUserData(NULL, 'object'));
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'telegram') {
			return $this->deleteTelegram($this->libIonix->getUserData(NULL, 'object'));
		}
	}

	private function deleteImage(array $userData)
	{
		if (!$userData[$this->libIonix->Decode($this->request->getGet('id'))]) {
			return requestOutput(404, 'Anda tidak memiliki <strong>Gambar '.ucwords($this->libIonix->Decode($this->request->getGet('id'))).'</strong>, tidak ada yang dapat dihapus');
		}

		if (file_exists($this->configIonix->uploadsFolder['user'].$userData['uuid'].'/'.$userData[$this->libIonix->Decode($this->request->getGet('id'))])) {
			unlink($this->configIonix->uploadsFolder['user'].$userData['uuid'].'/'.$userData[$this->libIonix->Decode($this->request->getGet('id'))]);
		}

		$output = [
			'delete' 	=> $this->libIonix->updateQuery('users', ['user_id' => $userData['user_id']], [$this->libIonix->Decode($this->request->getGet('id')) => NULL]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Gambar '.ucwords($this->libIonix->Decode($this->request->getGet('id'))).'</strong> pada Akun Anda', $output);
	}

	private function deleteSocial()
	{
		$output = [
			'delete'  => $this->libIonix->deleteQuery('social_media', ['sosmed_id' => $this->libIonix->Decode($this->request->getGet('id'))]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Media Sosial</strong> yang dipilih', $output);
	}

	private function deleteActivity(object $userData)
	{
		if ($this->libIonix->getQuery('auth_login', NULL, ['user_id' => $userData->user_id])->getNumRows() == false) {
			return requestOutput(404, 'Tidak dapat menghapus <strong>Riwayat Login</strong> karena tidak ada aktivitas tercatat/kosong');
		}

		$output = [
			'delete'  => $this->libIonix->deleteQuery('auth_login', ['user_id' => $userData->user_id]),
		];

		return requestOutput(202, 'Berhasil menghapus seluruh <strong>Aktivitas Masuk</strong> Anda', $output);
	}

	private function deleteTelegram(object $userData)
	{
		$output = [
			'delete'  => $this->libIonix->deleteQuery('notification_telegrams', ['user_id' => $userData->user_id]),
		];

		return requestOutput(202, 'Berhasil menghapus konfigurasi <strong>Telegram</strong> pada Akun Anda', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ProfileController.php
 * Location: ./app/Controllers/Panel/ProfileController.php
 * -----------------------------------------------------------------------
 */
