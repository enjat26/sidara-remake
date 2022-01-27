<?php namespace App\Controllers\Panel\Sport\Achievement;

use App\Controllers\BaseController;

use App\Models\Sport\AchievementModel;
use App\Models\Sport\AtletModel;
use App\Models\Sport\Championship\ChampionshipModel;
use App\Models\Sport\Championship\ParticipantModel;
use App\Models\MedalModel;
use App\Models\UserModel;

/**
 * Class AchievementController
 *
 * @package App\Controllers
 */
class AchievementController extends BaseController
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
		$this->modAchievement 			= new AchievementModel();
		$this->modAtlet 						= new AtletModel();
		$this->modChampionship 			= new ChampionshipModel();
		$this->modMedal 						= new MedalModel();
		$this->modParticipant 			= new ParticipantModel();
		$this->modUser 						  = new UserModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modAchievement'			=> $this->modAchievement,
			'modChampionship'			=> $this->modChampionship,
			'modMedal' 						=> $this->modMedal,
		];

		return view('panels/sports/achievements/achievements', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'achievement') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modAchievement->fetchData(['sport_achievement_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'achievement') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listAchievementDT();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			if ($this->request->getGet('format') == 'Dropdown') {
				return $this->listAtletDropdown();
			}
		}
	}

	private function listAtletDropdown()
	{
		if (isStakeholder() == true) {
			$parameters = [
				'sport_championships.sport_championship_id' => $this->request->getGet('id'),
				'sport_atlets.sport_atlet_created_by' 			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = [
				'sport_championships.sport_championship_id' => $this->request->getGet('id'),
			];
		}

		foreach ($this->modParticipant->fetchData($parameters)->get()->getResult() as $row) {
			echo '<option value="' . $row->sport_atlet_id . '">' . $row->sport_atlet_name . ' ('.$row->cabor_name.')</option>';
		}
		exit;
	}

	private function listAchievementDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];
		$btnAction 		= '';
		$btnUpdate 		= '';
		$btnDelete 		= '';

		if (isStakeholder() == true) {
			$parameters = [
				'sport_achievement_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = NULL;
		}

		foreach ($this->modAchievement->fetchData($parameters, true)->getResult() as $row) {
			$subArray 	= [];

			if ($row->sport_atlet_avatar) {
				$atletAvatar	= core_url('content/atlet/' . $this->libIonix->Encode($row->sport_atlet_id) . '/' . $this->libIonix->Encode($row->sport_atlet_avatar));
			} else {
				$atletAvatar = $this->configIonix->mediaFolder['image'] . 'default/avatar.jpg';
			}

			if ($row->sport_championship_code) {
				$championshipCode = strtoupper($row->sport_championship_code);
			} else {
				$championshipCode = '-';
			}

			if ($row->sport_achievement_number) {
				$achievementNumber = strtoupper($row->sport_achievement_number);
			} else {
				$achievementNumber = '-';
			}

			if ($row->sport_achievement_result) {
				$achievementResult = strtoupper($row->sport_achievement_result);
			} else {
				$achievementResult = '-';
			}

			if ($row->sport_achievement_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->sport_achievement_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->sport_achievement_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->sport_achievement_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->sport_achievement_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			if (isStakeholder() == true) {
				if ($row->sport_achievement_approve == 0) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateResub(false ,\'' . $this->libIonix->Encode('resub') . '\', \'' . $this->libIonix->Encode($row->sport_achievement_id) . '\');"><i class="mdi mdi-reply font-size-16 align-middle text-info me-1"></i> Ajukan Ulang</a>';
				} elseif ($row->sport_achievement_approve == 2) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" class="text-warning">Menunggu Verifikasi</a>';
				} else {
					$btnAction 		= '';
				}

				if ($row->sport_achievement_approve == 1 || $row->sport_achievement_approve == 3) {
					$btnUpdate  = '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-achievement" onclick="putAchievement(\'' . $this->libIonix->Encode('achievement') . '\', \'' . $this->libIonix->Encode($row->sport_achievement_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
					$btnDelete 	= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('achievement') . '\', \'' . $this->libIonix->Encode($row->sport_achievement_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
				}
			} else {
				if ($row->sport_achievement_approve == 2) {
					$btnAction	= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateVerify(false ,\'' . $this->libIonix->Encode('verify') . '\', \'' . $this->libIonix->Encode($row->sport_achievement_id) . '\');"><i class="mdi mdi-check-circle font-size-16 align-middle text-success me-1"></i> Verifikasi</a>';
				} else {
					$btnAction 	= '';
				}

				$btnUpdate  	= '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-achievement" onclick="putAchievement(\'' . $this->libIonix->Encode('achievement') . '\', \'' . $this->libIonix->Encode($row->sport_achievement_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
				$btnDelete 		= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('achievement') . '\', \'' . $this->libIonix->Encode($row->sport_achievement_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<div class="media">
												<div class="align-self-center me-3">
													<img src="' . $atletAvatar . '" alt="' . $row->sport_atlet_name . '" class="rounded avatar-sm" style="height: 4rem!important">
												</div>
                        <div class="media-body overflow-hidden my-auto">
                            <h5 class="text-truncate font-size-14 mb-1">' . $row->sport_atlet_name . '</h5>
                        </div>
                    </div>';
			$subArray[] = '<div class="media-body overflow-hidden my-auto">
			<h5 class="text-truncate font-size-14 mb-1">' . $row->cabor_name . '</h5>
			<p class="text-muted mb-0">Kode: ' . $row->cabor_code . '</p>
		</div>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->sport_championship_name . ' ('.$championshipCode.')</h6>
										<p class="text-muted mb-0">' . $achievementNumber . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $achievementResult . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_championship_year . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_medal_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseStatusData($row->sport_achievement_approve)->badge . '</p>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																'.$btnAction.'
																'.$btnUpdate.'
																'.$btnDelete.'
														</div>
												</div>
										</div>';
			$data[] 	= $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modAchievement->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modAchievement->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'achievement') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addAchievement();
			} else {
				return $this->updateAchievement($this->modAchievement->fetchData(['sport_achievement_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			return $this->addResub($this->modAchievement->fetchData(['sport_achievement_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			return $this->addVerify($this->modAchievement->fetchData(['sport_achievement_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function addAchievement()
	{
		$requestAchievement = [
			'sport_championship_id'						=> $this->request->getPost('championship'),
			'sport_atlet_id'									=> $this->request->getPost('atlet'),
			'sport_medal_id'									=> $this->request->getPost('medal'),
			'sport_achievement_number'				=> !empty($this->request->getPost('number')) ? ucwords($this->request->getPost('number')) : NULL,
			'sport_achievement_result'				=> !empty($this->request->getPost('result')) ? ucwords($this->request->getPost('result')) : NULL,
			'sport_achievement_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'sport_achievement_approve_by'			=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'sport_achievement_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'achievement',
				'notification_title'		=> 'Pengajuan Penambahan Data Prestasi Olahraga',
				'notification_slug'			=> 'sport_achievements',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data Prestasi Olahraga dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'insert'						=> $this->libIonix->insertQuery('sport_achievements', $requestAchievement),
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Prestasi Olahraga</strong> baru', $output);
	}

	private function addResub(object $achievementData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('sport_achievements', ['sport_achievement_id' => $achievementData->sport_achievement_id], ['sport_achievement_approve' => $achievementData->sport_achievement_approve+1]),
		];

		return requestOutput(202, 'Berhasil <strong>mendaftarkan ulang</strong> <strong>Prestasi</strong> ini untuk diperbaiki dan diajukan kembali', $output);
	}

	private function addVerify(object $achievementData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('sport_achievements', ['sport_achievement_id' => $achievementData->sport_achievement_id], ['sport_achievement_approve' => $achievementData->sport_achievement_approve+1, 'sport_achievement_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('sport_achievements', ['sport_achievement_id' => $achievementData->sport_achievement_id], ['sport_achievement_approve' => $achievementData->sport_achievement_approve-2, 'sport_achievement_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $achievementData->sport_achievement_created_by,
			'notification_type'			=> 'achievement',
			'notification_title'		=> 'Verifikasi Data Prestasi Olahraga',
			'notification_slug'			=> 'sport_achievements',
			'notification_content'	=> 'Data Prestasi Olahraga atas nama <strong>'.$achievementData->sport_atlet_name.'</strong> yang Anda ajukan telah '.$action->title.'. '.$action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil <strong>'.$action->message.'</strong> <strong>Data Prestasi Olahraga</strong> yang diajukan', $output);
	}

	private function updateAchievement(object $achievementData)
	{
		$request = [
			'sport_championship_id'						=> $this->request->getPost('championship'),
			'sport_atlet_id'									=> $this->request->getPost('atlet'),
			'sport_medal_id'									=> $this->request->getPost('medal'),
			'sport_achievement_number'				=> !empty($this->request->getPost('number')) ? ucwords($this->request->getPost('number')) : NULL,
			'sport_achievement_result'				=> !empty($this->request->getPost('result')) ? ucwords($this->request->getPost('result')) : NULL,
			'sport_achievement_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'asset',
				'notification_title'		=> 'Pengajuan Perubahan Data Prestasi Olahraga',
				'notification_slug'			=> 'sport_achievements',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data Prestasi Olahraga dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'update'							=> $this->libIonix->updateQuery('sport_achievements', ['sport_achievement_id' => $achievementData->sport_achievement_id], $request),
			'pushNotification'		=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Prestasi Olahraga</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	 public function delete()
	 {
		 if ($this->libIonix->Decode($this->request->getGet('scope')) == 'achievement') {
 	 		return $this->deleteAchievement($this->modAchievement->fetchData(['sport_achievement_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
 	 	 }
	 }

	 private function deleteAchievement(object $achievementData)
	 {
		 if (isStakeholder() == true) {
 	 		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
 	 			$request	= [
 	 				'user_id'								=> $row->user_id,
 	 				'notification_type'			=> 'achievement',
 	 				'notification_title'		=> 'Pengajuan Penghapusan Data Prestasi Olahraga',
 	 				'notification_slug'			=> 'sport_achievements',
 	 				'notification_content'	=> 'Anda mendapatkan pengajuan penghapusan Data atas nama '.$achievementData->sport_atlet_name.' dari Prestasi Olahraga yang diajukan '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dihapus sepenuhnya',
 	 			];

 	 			$this->libIonix->insertQuery('notifications', $request);
 	 		}

 	 		$output = [
 	 			'delete' 						=> $this->libIonix->updateQuery('sport_achievements', ['sport_achievement_id' => $achievementData->sport_achievement_id], ['sport_achievement_approve' => -1, 'sport_achievement_deleted_at' => date('Y-m-d h:m:s')]),
 	 			'pushNotification'	=> $this->libIonix->pushNotification(),
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Prestasi Olahraga</strong> yang dipilih, Anda harus menunggu Data ini dihapus sepenuhnya', $output);
 	 	} else {
 	 		$output = [
 	 			'delete' 	=> $this->libIonix->deleteQuery('sport_achievements', ['sport_achievement_id' => $achievementData->sport_achievement_id]),
 	 			'url'			=> !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? panel_url('sport_achievements') : NULL,
 	 			'flash'   => !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? $this->session->setFlashdata('alertSwal', [
 	 											'type'		=> 'success',
 	 											'header'	=> '202 Accepted',
 	 											'message'	=> 'Berhasil menghapus <strong>Data</strong> dari sistem',
 	 									 ]) : NULL,
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Prestasi Olahraga</strong> yang dipilih', $output);
 	 	}
	 }

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: AchievementController.php
 * Location: ./app/Controllers/Panel/Sport/Achievement/AchievementController.php
 * -----------------------------------------------------------------------
 */
