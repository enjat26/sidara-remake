<?php namespace App\Controllers\Panel\Youth\Organization;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\FileModel;
use App\Models\Youth\OrganizationModel;
use App\Models\UserModel;

/**
 * Class OrganizationController
 *
 * @package App\Controllers
 */
class OrganizationController extends BaseController
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
		$this->modOrganization 							= new OrganizationModel();
		$this->modFile 							= New FileModel();
		$this->modProvince 					= new ProvinceModel();
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
			'modOrganization'							=> $this->modOrganization,
			'modProvince' 				=> $this->modProvince,
		];

		return view('panels/youths/organizations/organizations', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'organization') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modOrganization->fetchData(['youth_organization_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'organization') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listOrganizationDT();
			}
		}
	}

	private function listOrganizationDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];
		$btnAction 		= '';
		$btnUpdate 		= '';
		$btnDelete 		= '';

		if (isStakeholder() == true) {
			$parameters = [
				'youth_organization_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
				'year' 								=> $this->session->year,
			];
		} else {
			$parameters = [
				'year' 								=> $this->session->year,
			];
		}

		foreach ($this->modOrganization->fetchData($parameters, true)->getResult() as $row) {
			$subArray 	= [];

			if ($row->youth_organization_code) {
				$organizationCode = strtoupper($row->youth_organization_code);
			} else {
				$organizationCode = '-';
			}

			if ($row->youth_organization_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->youth_organization_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->youth_organization_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->youth_organization_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->youth_organization_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			if (isStakeholder() == true) {
				if ($row->youth_organization_approve == 0) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateResub(false ,\'' . $this->libIonix->Encode('resub') . '\', \'' . $this->libIonix->Encode($row->youth_organization_id) . '\');"><i class="mdi mdi-reply font-size-16 align-middle text-info me-1"></i> Ajukan Ulang</a>';
				} elseif ($row->youth_organization_approve == 2) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" class="text-warning">Menunggu Verifikasi</a>';
				} else {
					$btnAction 		= '';
				}

				if ($row->youth_organization_approve == 1 || $row->youth_organization_approve == 3) {
					$btnUpdate  = '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-organization" onclick="putOrganization(\'' . $this->libIonix->Encode('organization') . '\', \'' . $this->libIonix->Encode($row->youth_organization_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
					$btnDelete 	= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('organization') . '\', \'' . $this->libIonix->Encode($row->youth_organization_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
				}
			} else {
				if ($row->youth_organization_approve == 2) {
					$btnAction	= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateVerify(false ,\'' . $this->libIonix->Encode('verify') . '\', \'' . $this->libIonix->Encode($row->youth_organization_id) . '\');"><i class="mdi mdi-check-circle font-size-16 align-middle text-success me-1"></i> Verifikasi</a>';
				} else {
					$btnAction 	= '';
				}

				$btnUpdate  	= '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-organization" onclick="putOrganization(\'' . $this->libIonix->Encode('organization') . '\', \'' . $this->libIonix->Encode($row->youth_organization_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
				$btnDelete 		= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('organization') . '\', \'' . $this->libIonix->Encode($row->youth_organization_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			}

			if ($row->youth_organization_created_by == $this->libIonix->getUserData(NULL, 'object')->user_id || $this->libIonix->getUserData(NULL, 'object')->role_access >= $this->configIonix->roleController) {
				if ($row->youth_organization_file_id) {
					$organizationAttachment = '<a href="'.$this->libIonix->generateFileLink($row->youth_organization_file_id).'" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-download align-middle me-1"></i> Unduh Dokumen</a>';
				} else {
					$organizationAttachment = '<i class="mdi mdi-alert-circle-outline align-middle text-warning font-size-18"></i>';
				}
			} else {
				$organizationAttachment = '<i>NULL</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->youth_organization_name . ' ('.$row->youth_organization_year_start.'-'.$row->youth_organization_year_end.')</h6>
										<p class="text-muted mb-0">' . $organizationCode . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->youth_organization_leader . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $organizationAttachment . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseStatusData($row->youth_organization_approve)->badge . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->youth_organization_number_of_member . '</p>';
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
			"recordsTotal"     => $this->modOrganization->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modOrganization->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'organization') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addOrganization();
			} else {
				return $this->updateOrganization($this->modOrganization->fetchData(['youth_organization_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			return $this->addResub($this->modOrganization->fetchData(['youth_organization_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			return $this->addVerify($this->modOrganization->fetchData(['youth_organization_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function addOrganization()
	{
		$requestOrganization = [
			'youth_organization_district_id'		=> $this->request->getPost('district'),
			'youth_organization_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : NULL,
			'year' 									=> $this->session->year,
			'youth_organization_year_start'						=> strtolower($this->request->getPost('year_start')),
			'youth_organization_year_end'						=> strtolower($this->request->getPost('year_end')),
			'youth_organization_name'						=> ucwords($this->request->getPost('name')),
			'youth_organization_leader'					=> ucwords($this->request->getPost('leader')),
			'youth_organization_number_of_member'					=> ucwords($this->request->getPost('number_of_member')),
			'youth_organization_address'				=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'youth_organization_approve'				=> isStakeholder() == false ? 3 : 2,
			'youth_organization_approve_by'			=> isStakeholder() == false ? $this->libIonix->getUserData(NULL, 'object')->user_id : NULL,
			'youth_organization_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		if (!empty($this->request->getPost('code'))) {
			if (regexUsername($requestOrganization['youth_organization_code']) == false) {
				return requestOutput(411, 'Format <strong>Kode Organisasi Pemuda</strong> yang Anda gunakan tidak benar');
			}

			if ($this->modOrganization->fetchData(['youth_organization_code' => $requestOrganization['youth_organization_code']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Kode Organisasi Pemuda</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
			}
		}

		$query = (object) [
			'insert'		=> $this->libIonix->insertQuery('youth_organizations', $requestOrganization),
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'organization',
				'notification_title'		=> 'Pengajuan Penambahan Data Organisasi Pemuda',
				'notification_slug'			=> 'youth_organizations',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data '.$requestOrganization['youth_organization_name'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'upload'						=> $this->request->getFile('file')->isValid() ? $this->uploadOrganizationAttachment($this->modOrganization->fetchData(['youth_organization_id' => $query->insert])->get()->getRow()) : NULL,
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Organisasi Pemuda</strong> baru', $output);
	}

	private function addResub(object $organizationData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id], ['youth_organization_approve' => $organizationData->youth_organization_approve+1]),
		];

		return requestOutput(202, 'Berhasil <strong>mendaftarkan ulang</strong> <strong>'.$organizationData->youth_organization_name.'</strong> untuk diperbaiki dan diajukan kembali', $output);
	}

	private function addVerify(object $organizationData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id], ['youth_organization_approve' => $organizationData->youth_organization_approve+1, 'youth_organization_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id], ['youth_organization_approve' => $organizationData->youth_organization_approve-2, 'youth_organization_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $organizationData->youth_organization_created_by,
			'notification_type'			=> 'organization',
			'notification_title'		=> 'Verifikasi Data Organisasi Pemuda',
			'notification_slug'			=> 'youth_organizations',
			'notification_content'	=> 'Data Organisasi Pemuda dengan nama <strong>'.$organizationData->youth_organization_name.'</strong> yang Anda ajukan telah '.$action->title.'. '.$action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil <strong>'.$action->message.'</strong> <strong>Data Organisasi Pemuda</strong> yang diajukan', $output);
	}

	private function updateOrganization(object $organizationData)
	{
		$request = [
			'youth_organization_district_id'		=> $this->request->getPost('district'),
			'youth_organization_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : $organizationData->youth_organization_code,
			'youth_organization_year_start'						=> strtolower($this->request->getPost('year_start')),
			'youth_organization_year_end'						=> strtolower($this->request->getPost('year_end')),
			'youth_organization_name'						=> ucwords($this->request->getPost('name')),
			'youth_organization_leader'					=> ucwords($this->request->getPost('leader')),
			'youth_organization_number_of_member'					=> ucwords($this->request->getPost('number_of_member')),
			'youth_organization_address'				=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'youth_organization_approve'				=> isStakeholder() == false ? 3 : 2,
			'youth_organization_approve_by'			=> isStakeholder() == false ? $this->libIonix->getUserData(NULL, 'object')->user_id : NULL,
		];

		if (!empty($this->request->getPost('code'))) {
			if ($organizationData->youth_organization_code != $request['youth_organization_code']) {
				if (regexUsername($request['youth_organization_code']) == false) {
					return requestOutput(411, 'Format <strong>Kode Organisasi Pemuda</strong> yang Anda gunakan tidak benar');
				}

				if ($this->modOrganization->fetchData(['youth_organization_code' => $request['youth_organization_code']])->countAllResults() == true) {
					return requestOutput(406, '<strong>Kode Organisasi Pemuda</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
				}
			}
		}

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'asset',
				'notification_title'		=> 'Pengajuan Perubahan Data Organisasi Pemuda',
				'notification_slug'			=> 'youth_organizations',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data Organisasi Pemuda dengan nama '.$request['youth_organization_name'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'update'							=> $this->libIonix->updateQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id], $request),
			'upload'							=> $this->request->getFile('file')->isValid() ? $this->uploadOrganizationAttachment($organizationData) : NULL,
			'pushNotification'		=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Organisasi Pemuda</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Upload Method
	 * --------------------------------------------------------------------
	 */

	private function uploadOrganizationAttachment(object $organizationData)
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> 'attachment',
			'file_name' 					=> 'Dokumen Pendukung Organisasi Pemuda '.$organizationData->youth_organization_name.' Periode '.$organizationData->youth_organization_year_start.'-'.$organizationData->youth_organization_year_end,
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
			'update'		=> $this->libIonix->updateQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id], ['youth_organization_file_id' => $upload->insert]),
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
		 if ($this->libIonix->Decode($this->request->getGet('scope')) == 'organization') {
 	 		return $this->deleteOrganization($this->modOrganization->fetchData(['youth_organization_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
 	 	 }
	 }

	 private function deleteOrganization(object $organizationData)
	 {
		 if (isStakeholder() == true) {
 	 		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
 	 			$request	= [
 	 				'user_id'								=> $row->user_id,
 	 				'notification_type'			=> 'organization',
 	 				'notification_title'		=> 'Pengajuan Penghapusan Data Organisasi Pemuda',
 	 				'notification_slug'			=> 'youth_organizations',
 	 				'notification_content'	=> 'Anda mendapatkan pengajuan penghapusan Data '.$organizationData->youth_organization_name.' dari Organisasi Pemuda yang diajukan '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dihapus sepenuhnya',
 	 			];

 	 			$this->libIonix->insertQuery('notifications', $request);
 	 		}

 	 		$output = [
 	 			'delete' 						=> $this->libIonix->updateQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id], ['youth_organization_approve' => -1, 'youth_organization_deleted_at' => date('Y-m-d h:m:s')]),
 	 			'pushNotification'	=> $this->libIonix->pushNotification(),
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Organisasi Pemuda</strong> yang dipilih, Anda harus menunggu Data ini dihapus sepenuhnya', $output);
 	 	} else {
 	 		$output = [
				'remove'	=> $this->removeAttachment($organizationData),
 	 			'delete' 	=> $this->libIonix->deleteQuery('youth_organizations', ['youth_organization_id' => $organizationData->youth_organization_id]),
 	 			'url'			=> !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? panel_url('youth_organizations') : NULL,
 	 			'flash'   => !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? $this->session->setFlashdata('alertSwal', [
 	 											'type'		=> 'success',
 	 											'header'	=> '202 Accepted',
 	 											'message'	=> 'Berhasil menghapus <strong>Data</strong> dari sistem',
 	 									 ]) : NULL,
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Organisasi Pemuda</strong> yang dipilih', $output);
 	 	}
	 }

	 private function removeAttachment(object $organizationData)
	 {
		 if ($organizationData->youth_organization_file_id) {
			 if ($this->modFile->fetchData(['file_id' => $organizationData->youth_organization_file_id])->countAllResults() == true) {
				 $data = (object) [
					 'file' 	=> $this->modFile->fetchData(['file_id' => $organizationData->youth_organization_file_id])->get()->getRow(),
				 ];

				 if (file_exists($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source)) {
					 unlink($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source);
				 }
			 }
		 }

		 $output = [
			 'remove'	=> $organizationData->youth_organization_file_id ? $this->libIonix->deleteQuery('files', ['file_id' => $data->file->file_id]) : NULL,
		 ];

		 return $output;
	 }

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: OrganizationController.php
 * Location: ./app/Controllers/Panel/Youth/Organization/OrganizationController.php
 * -----------------------------------------------------------------------
 */
