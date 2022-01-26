<?php namespace App\Controllers\Panel\Sport\Club;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\FileModel;
use App\Models\Sport\ClubModel;
use App\Models\Sport\OrganizationModel;
use App\Models\UserModel;

/**
 * Class ClubController
 *
 * @package App\Controllers
 */
class ClubController extends BaseController
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
		$this->modClub 							= new ClubModel();
		$this->modFile 							= New FileModel();
		$this->modProvince 					= new ProvinceModel();
		$this->modOrganization 					= new OrganizationModel();
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
			'modClub'							=> $this->modClub,
			'modOrganization' 					=> $this->modOrganization,
			'modProvince' 						=> $this->modProvince,
		];

		return view('panels/sports/clubs/clubs', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'club') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modClub->fetchData(['sport_club_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'club') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listClubDT();
			}
		}
	}

	private function listClubDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];
		$btnAction 		= '';
		$btnUpdate 		= '';
		$btnDelete 		= '';

		if (isStakeholder() == true) {
			$parameters = [
				'sport_club_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
				'year' 								=> $this->session->year,
			];
		} else {
			$parameters = [
				'year' 								=> $this->session->year,
			];
		}

		foreach ($this->modClub->fetchData($parameters, true)->getResult() as $row) {
			$subArray 	= [];

			if ($row->sport_club_code) {
				$clubCode = strtoupper($row->sport_club_code);
			} else {
				$clubCode = '-';
			}

			if ($row->sport_club_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->sport_club_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->sport_club_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->sport_club_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->sport_club_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			if (isStakeholder() == true) {
				if ($row->sport_club_approve == 0) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateResub(false ,\'' . $this->libIonix->Encode('resub') . '\', \'' . $this->libIonix->Encode($row->sport_club_id) . '\');"><i class="mdi mdi-reply font-size-16 align-middle text-info me-1"></i> Ajukan Ulang</a>';
				} elseif ($row->sport_club_approve == 2) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" class="text-warning">Menunggu Verifikasi</a>';
				} else {
					$btnAction 		= '';
				}

				if ($row->sport_club_approve == 1 || $row->sport_club_approve == 3) {
					$btnUpdate  = '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-club" onclick="putClub(\'' . $this->libIonix->Encode('club') . '\', \'' . $this->libIonix->Encode($row->sport_club_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
					$btnDelete 	= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('club') . '\', \'' . $this->libIonix->Encode($row->sport_club_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
				}
			} else {
				if ($row->sport_club_approve == 2) {
					$btnAction	= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateVerify(false ,\'' . $this->libIonix->Encode('verify') . '\', \'' . $this->libIonix->Encode($row->sport_club_id) . '\');"><i class="mdi mdi-check-circle font-size-16 align-middle text-success me-1"></i> Verifikasi</a>';
				} else {
					$btnAction 	= '';
				}

				$btnUpdate  	= '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-club" onclick="putClub(\'' . $this->libIonix->Encode('club') . '\', \'' . $this->libIonix->Encode($row->sport_club_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
				$btnDelete 		= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('club') . '\', \'' . $this->libIonix->Encode($row->sport_club_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			}
			
			if ($row->sport_club_created_by == $this->libIonix->getUserData(NULL, 'object')->user_id || $this->libIonix->getUserData(NULL, 'object')->role_access >= $this->configIonix->roleController) {
				if ($row->sport_club_file_id) {
					$clubAttachment = '<a href="'.$this->libIonix->generateFileLink($row->sport_club_file_id).'" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-download align-middle me-1"></i> Unduh Dokumen</a>';
				} else {
					$clubAttachment = '<i class="mdi mdi-alert-circle-outline align-middle text-warning font-size-18"></i>';
				}
			} else {
				$clubAttachment = '<i>NULL</i>';
			}
			$organizationName = $this->modOrganization->fetchData(['sport_organization_id' => $row->sport_organization_id])->get()->getRow();
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->sport_club_name . ' ('.$row->sport_club_year.')</h6>
										<p class="text-muted mb-0">' . $clubCode . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_club_leader . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$organizationName->sport_organization_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $clubAttachment . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseStatusData($row->sport_club_approve)->badge . '</p>';
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
			"recordsTotal"     => $this->modClub->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modClub->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'club') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addClub();
			} else {
				return $this->updateClub($this->modClub->fetchData(['sport_club_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			return $this->addResub($this->modClub->fetchData(['sport_club_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			return $this->addVerify($this->modClub->fetchData(['sport_club_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function addClub()
	{
		$requestClub = [
			'sport_organization_id'				=> $this->request->getPost('sport_organization_id'),
			'sport_club_district_id'			=> $this->request->getPost('district'),
			'sport_club_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : NULL,
			'sport_club_year'						=> strtolower($this->request->getPost('year')),
			'sport_club_name'						=> ucwords($this->request->getPost('name')),
			'sport_club_leader'					=> ucwords($this->request->getPost('leader')),
			'sport_club_address'				=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'sport_club_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'sport_club_approve_by'			=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'sport_club_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'year' 								=> $this->session->year,
		];

		if (!empty($this->request->getPost('code'))) {
			if (regexUsername($requestClub['sport_club_code']) == false) {
				return requestOutput(411, 'Format <strong>Kode Klub Olahraga</strong> yang Anda gunakan tidak benar');
			}

			if ($this->modClub->fetchData(['sport_club_code' => $requestClub['sport_club_code']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Kode Klub Olahraga</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
			}
		}

		$query = (object) [
			'insert'		=> $this->libIonix->insertQuery('sport_clubs', $requestClub),
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'club',
				'notification_title'		=> 'Pengajuan Penambahan Data Klub Olahraga',
				'notification_slug'			=> 'sport_clubs',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data '.$requestClub['sport_club_name'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'upload'						=> $this->request->getFile('file')->isValid() ? $this->uploadClubAttachment($this->modClub->fetchData(['sport_club_id' => $query->insert])->get()->getRow()) : NULL,
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Klub Olahraga</strong> baru', $output);
	}

	private function addResub(object $clubData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id], ['sport_club_approve' => $clubData->sport_club_approve+1]),
		];

		return requestOutput(202, 'Berhasil <strong>mendaftarkan ulang</strong> <strong>'.$clubData->sport_club_name.'</strong> untuk diperbaiki dan diajukan kembali', $output);
	}

	private function addVerify(object $clubData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id], ['sport_club_approve' => $clubData->sport_club_approve+1, 'sport_club_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id], ['sport_club_approve' => $clubData->sport_club_approve-2, 'sport_club_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $clubData->sport_club_created_by,
			'notification_type'			=> 'club',
			'notification_title'		=> 'Verifikasi Data Klub Olahraga',
			'notification_slug'			=> 'sport_clubs',
			'notification_content'	=> 'Data Klub Olahraga dengan nama <strong>'.$clubData->sport_club_name.'</strong> yang Anda ajukan telah '.$action->title.'. '.$action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil <strong>'.$action->message.'</strong> <strong>Data Klub Olahraga</strong> yang diajukan', $output);
	}

	private function updateClub(object $clubData)
	{
		$request = [
			'sport_organization_id'				=> $this->request->getPost('sport_organization_id'),
			'sport_club_district_id'		=> $this->request->getPost('district'),
			'sport_club_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : $clubData->sport_club_code,
			'sport_club_year'						=> strtolower($this->request->getPost('year')),
			'sport_club_name'						=> ucwords($this->request->getPost('name')),
			'sport_club_leader'					=> ucwords($this->request->getPost('leader')),
			'sport_club_address'				=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'sport_club_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
		];

		if (!empty($this->request->getPost('code'))) {
			if ($clubData->sport_club_code != $request['sport_club_code']) {
				if (regexUsername($request['sport_club_code']) == false) {
					return requestOutput(411, 'Format <strong>Kode Klub Olahraga</strong> yang Anda gunakan tidak benar');
				}

				if ($this->modClub->fetchData(['sport_club_code' => $request['sport_club_code']])->countAllResults() == true) {
					return requestOutput(406, '<strong>Kode Klub Olahraga</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
				}
			}
		}

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'asset',
				'notification_title'		=> 'Pengajuan Perubahan Data Klub Olahraga',
				'notification_slug'			=> 'sport_clubs',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data Klub Olahraga dengan nama '.$request['sport_club_name'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'update'							=> $this->libIonix->updateQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id], $request),
			'upload'							=> $this->request->getFile('file')->isValid() ? $this->uploadClubAttachment($clubData) : NULL,
			'pushNotification'		=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Klub Olahraga</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Upload Method
	 * --------------------------------------------------------------------
	 */

	private function uploadClubAttachment(object $clubData)
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> 'attachment',
			'file_name' 					=> 'Dokumen Pendukung Klub Olahraga '.$clubData->sport_club_name.' Periode '.$clubData->sport_club_year,
			'file_source'					=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->getRandomName() : NULL,
			'file_size'						=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->getSize('b') : NULL,
			'file_extension' 			=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->getClientExtension() : NULL,
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder['attachment'],
			'fileName'	=> $this->request->getFile('file')->isValid() ? $request['file_source'] : NULL,
		];

		$upload = (object) [
			'move'			=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->move($config['directory'], $config['fileName'], true) : NULL,
			'insert'		=> $request['file_source'] ? $this->libIonix->insertQuery('files', $request) : NULL,
		];

		$output = [
			'update'		=> $this->libIonix->updateQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id], ['sport_club_file_id' => $upload->insert]),
		];

		return $output;
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	 public function delete()
	 {
		 if ($this->libIonix->Decode($this->request->getGet('scope')) == 'club') {
 	 		return $this->deleteClub($this->modClub->fetchData(['sport_club_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
 	 	 }
	 }

	 private function deleteClub(object $clubData)
	 {
		 if (isStakeholder() == true) {
 	 		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
 	 			$request	= [
 	 				'user_id'								=> $row->user_id,
 	 				'notification_type'			=> 'club',
 	 				'notification_title'		=> 'Pengajuan Penghapusan Data Klub Olahraga',
 	 				'notification_slug'			=> 'sport_clubs',
 	 				'notification_content'	=> 'Anda mendapatkan pengajuan penghapusan Data '.$clubData->sport_club_name.' dari Klub Olahraga yang diajukan '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dihapus sepenuhnya',
 	 			];

 	 			$this->libIonix->insertQuery('notifications', $request);
 	 		}

 	 		$output = [
 	 			'delete' 						=> $this->libIonix->updateQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id], ['sport_club_approve' => -1, 'sport_club_deleted_at' => date('Y-m-d h:m:s')]),
 	 			'pushNotification'	=> $this->libIonix->pushNotification(),
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Klub Olahraga</strong> yang dipilih, Anda harus menunggu Data ini dihapus sepenuhnya', $output);
 	 	} else {
 	 		$output = [
				'remove'	=> $this->removeAttachment($clubData),
 	 			'delete' 	=> $this->libIonix->deleteQuery('sport_clubs', ['sport_club_id' => $clubData->sport_club_id]),
 	 			'url'			=> !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? panel_url('sport_clubs') : NULL,
 	 			'flash'   => !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? $this->session->setFlashdata('alertSwal', [
 	 											'type'		=> 'success',
 	 											'header'	=> '202 Accepted',
 	 											'message'	=> 'Berhasil menghapus <strong>Data</strong> dari sistem',
 	 									 ]) : NULL,
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Klub Olahraga</strong> yang dipilih', $output);
 	 	}
	 }

	 private function removeAttachment(object $clubData)
	 {
		 if ($clubData->sport_club_file_id) {
			 if ($this->modFile->fetchData(['file_id' => $clubData->sport_club_file_id])->countAllResults() == true) {
				 $data = (object) [
					 'file' 	=> $this->modFile->fetchData(['file_id' => $clubData->sport_club_file_id])->get()->getRow(),
				 ];

				 if (file_exists($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source)) {
					 unlink($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source);
				 }
			 }
		 }

		 $output = [
			 'remove'	=> $clubData->sport_club_file_id ? $this->libIonix->deleteQuery('files', ['file_id' => $data->file->file_id]) : NULL,
		 ];

		 return $output;
	 }

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ClubController.php
 * Location: ./app/Controllers/Panel/Sport/Club/ClubController.php
 * -----------------------------------------------------------------------
 */
