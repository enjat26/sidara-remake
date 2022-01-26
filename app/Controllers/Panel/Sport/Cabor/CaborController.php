<?php namespace App\Controllers\Panel\Sport\Cabor;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\FileModel;
use App\Models\Sport\CaborModel;
use App\Models\UserModel;

/**
 * Class CaborController
 *
 * @package App\Controllers
 */
class CaborController extends BaseController
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
		$this->modCabor 			= new CaborModel();
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
			'modCabor'							=> $this->modCabor,
			'modProvince' 				=> $this->modProvince,
		];

		return view('panels/sports/cabors/cabors', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listCaborDT();
			}
		}
	}

	private function listCaborDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];
		$btnAction 		= '';
		$btnUpdate 		= '';
		$btnDelete 		= '';

		if (isStakeholder() == true) {
			$parameters = [
				'sport_cabor_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
				'sport_cabor_id !=' 	=> '1',
				'year' 													=> $this->session->year,
			];
		} else {
			$parameters = [
				'sport_cabor_id !=' => 1,
				'year' 													=> $this->session->year,
			];
		}

		foreach ($this->modCabor->fetchData($parameters, true)->getResult() as $row) {
			$subArray 	= [];

			if ($row->sport_cabor_code) {
				$caborCode = strtoupper($row->sport_cabor_code);
			} else {
				$caborCode = '-';
			}

			if ($row->sport_cabor_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->sport_cabor_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->sport_cabor_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->sport_cabor_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->sport_cabor_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			if (isStakeholder() == true) {
				if ($row->sport_cabor_approve == 0) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateResub(false ,\'' . $this->libIonix->Encode('resub') . '\', \'' . $this->libIonix->Encode($row->sport_cabor_id) . '\');"><i class="mdi mdi-reply font-size-16 align-middle text-info me-1"></i> Ajukan Ulang</a>';
				} elseif ($row->sport_cabor_approve == 2) {
					$btnAction		= '<a class="dropdown-item" href="javascript:void(0);" class="text-warning">Menunggu Verifikasi</a>';
				} else {
					$btnAction 		= '';
				}

				if ($row->sport_cabor_approve == 1 || $row->sport_cabor_approve == 3) {
					$btnUpdate  = '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-cabor" onclick="putCabor(\'' . $this->libIonix->Encode('cabor') . '\', \'' . $this->libIonix->Encode($row->sport_cabor_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
					$btnDelete 	= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('cabor') . '\', \'' . $this->libIonix->Encode($row->sport_cabor_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
				}
			} else {
				if ($row->sport_cabor_approve == 2) {
					$btnAction	= '<a class="dropdown-item" href="javascript:void(0);" onclick="updateVerify(false ,\'' . $this->libIonix->Encode('verify') . '\', \'' . $this->libIonix->Encode($row->sport_cabor_id) . '\');"><i class="mdi mdi-check-circle font-size-16 align-middle text-success me-1"></i> Verifikasi</a>';
				} else {
					$btnAction 	= '';
				}

				$btnUpdate  	= '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-cabor" onclick="putCabor(\'' . $this->libIonix->Encode('cabor') . '\', \'' . $this->libIonix->Encode($row->sport_cabor_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
				$btnDelete 		= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('cabor') . '\', \'' . $this->libIonix->Encode($row->sport_cabor_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			}

			if ($row->sport_cabor_created_by == $this->libIonix->getUserData(NULL, 'object')->user_id || $this->libIonix->getUserData(NULL, 'object')->role_access >= $this->configIonix->roleController) {
				if ($row->sport_cabor_file_id) {
					$caborAttachment = '<a href="'.$this->libIonix->generateFileLink($row->sport_cabor_file_id).'" target="_blank" class="btn btn-sm btn-primary"><i class="mdi mdi-download align-middle me-1"></i> Unduh Dokumen</a>';
				} else {
					$caborAttachment = '<i class="mdi mdi-alert-circle-outline align-middle text-warning font-size-18"></i>';
				}
			} else {
				$caborAttachment = '<i>NULL</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->sport_cabor_name . ' ('.$row->sport_cabor_year_start.'-'.$row->sport_cabor_year_end.')</h6>
										<p class="text-muted mb-0">' . $caborCode . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_cabor_leader . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $caborAttachment . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseStatusData($row->sport_cabor_approve)->badge . '</p>';
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
			"recordsTotal"     => $this->modCabor->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modCabor->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addCabor();
			} else {
				return $this->updateCabor($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			return $this->addResub($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			return $this->addVerify($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function addCabor()
	{
		$requestCabor = [
			'sport_cabor_district_id'		=> $this->request->getPost('district'),
			'sport_cabor_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : NULL,
			'sport_cabor_year_start'						=> strtolower($this->request->getPost('year_start')),
			'sport_cabor_year_end'						=> strtolower($this->request->getPost('year_end')),
			'sport_cabor_name'						=> ucwords($this->request->getPost('name')),
			'sport_cabor_leader'					=> ucwords($this->request->getPost('leader')),
			'sport_cabor_address'				=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'sport_cabor_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'sport_cabor_approve_by'			=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'year' 								=> $this->session->year,
			'sport_cabor_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			
		];

		if (!empty($this->request->getPost('code'))) {
			if (regexUsername($requestCabor['sport_cabor_code']) == false) {
				return requestOutput(411, 'Format <strong>Kode Cabang Olahraga</strong> yang Anda gunakan tidak benar');
			}

			if ($this->modCabor->fetchData(['sport_cabor_code' => $requestCabor['sport_cabor_code']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Kode Cabang Olahraga</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
			}
		}

		$query = (object) [
			'insert'		=> $this->libIonix->insertQuery('sport_cabors', $requestCabor),
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'cabor',
				'notification_title'		=> 'Pengajuan Penambahan Data Cabang Olahraga',
				'notification_slug'			=> 'sport_cabors',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data '.$requestCabor['sport_cabor_name'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'upload'						=> $this->request->getFile('file')->isValid() ? $this->uploadCaborAttachment($this->modCabor->fetchData(['sport_cabor_id' => $query->insert])->get()->getRow()) : NULL,
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Cabang Olahraga</strong> baru', $output);
	}

	private function addResub(object $caborData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], ['sport_cabor_approve' => $caborData->sport_cabor_approve+1]),
		];

		return requestOutput(202, 'Berhasil <strong>mendaftarkan ulang</strong> <strong>'.$caborData->sport_cabor_name.'</strong> untuk diperbaiki dan diajukan kembali', $output);
	}

	private function addVerify(object $caborData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], ['sport_cabor_approve' => $caborData->sport_cabor_approve+1, 'sport_cabor_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], ['sport_cabor_approve' => $caborData->sport_cabor_approve-2, 'sport_cabor_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $caborData->sport_cabor_created_by,
			'notification_type'			=> 'cabor',
			'notification_title'		=> 'Verifikasi Data Cabang Olahraga',
			'notification_slug'			=> 'sport_cabors',
			'notification_content'	=> 'Data Cabang Olahraga dengan nama <strong>'.$caborData->sport_cabor_name.'</strong> yang Anda ajukan telah '.$action->title.'. '.$action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil <strong>'.$action->message.'</strong> <strong>Data Cabang Olahraga</strong> yang diajukan', $output);
	}

	private function updateCabor(object $caborData)
	{
		$request = [
			'sport_cabor_district_id'		=> $this->request->getPost('district'),
			'sport_cabor_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : $caborData->sport_cabor_code,
			'sport_cabor_year_start'						=> strtolower($this->request->getPost('year_start')),
			'sport_cabor_year_end'						=> strtolower($this->request->getPost('year_end')),
			'sport_cabor_name'						=> ucwords($this->request->getPost('name')),
			'sport_cabor_leader'					=> ucwords($this->request->getPost('leader')),
			'sport_cabor_address'				=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'sport_cabor_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
		];

		if (!empty($this->request->getPost('code'))) {
			if ($caborData->sport_cabor_code != $request['sport_cabor_code']) {
				if (regexUsername($request['sport_cabor_code']) == false) {
					return requestOutput(411, 'Format <strong>Kode Cabang Olahraga</strong> yang Anda gunakan tidak benar');
				}

				if ($this->modCabor->fetchData(['sport_cabor_code' => $request['sport_cabor_code']])->countAllResults() == true) {
					return requestOutput(406, '<strong>Kode Cabang Olahraga</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
				}
			}
		}

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'asset',
				'notification_title'		=> 'Pengajuan Perubahan Data Cabang Olahraga',
				'notification_slug'			=> 'sport_cabors',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data Cabang Olahraga dengan nama '.$request['sport_cabor_name'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'update'							=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], $request),
			'upload'							=> $this->request->getFile('file')->isValid() ? $this->uploadCaborAttachment($caborData) : NULL,
			'pushNotification'		=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Cabang Olahraga</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Upload Method
	 * --------------------------------------------------------------------
	 */

	private function uploadCaborAttachment(object $caborData)
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> 'attachment',
			'file_name' 					=> 'Dokumen Pendukung Cabang Olahraga '.$caborData->sport_cabor_name.' Periode '.$caborData->sport_cabor_year_start.'-'.$caborData->sport_cabor_year_end,
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
			'update'		=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], ['sport_cabor_file_id' => $upload->insert]),
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
		 if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
 	 		return $this->deleteCabor($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
 	 	 }
	 }

	 private function deleteCabor(object $caborData)
	 {
		 if (isStakeholder() == true) {
 	 		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
 	 			$request	= [
 	 				'user_id'								=> $row->user_id,
 	 				'notification_type'			=> 'cabor',
 	 				'notification_title'		=> 'Pengajuan Penghapusan Data Cabang Olahraga',
 	 				'notification_slug'			=> 'sport_cabors',
 	 				'notification_content'	=> 'Anda mendapatkan pengajuan penghapusan Data '.$caborData->sport_cabor_name.' dari Cabang Olahraga yang diajukan '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dihapus sepenuhnya',
 	 			];

 	 			$this->libIonix->insertQuery('notifications', $request);
 	 		}

 	 		$output = [
 	 			'delete' 						=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], ['sport_cabor_approve' => -1, 'sport_cabor_deleted_at' => date('Y-m-d h:m:s')]),
 	 			'pushNotification'	=> $this->libIonix->pushNotification(),
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Cabang Olahraga</strong> yang dipilih, Anda harus menunggu Data ini dihapus sepenuhnya', $output);
 	 	} else {
 	 		$output = [
				'remove'	=> $this->removeAttachment($caborData),
 	 			'delete' 	=> $this->libIonix->deleteQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id]),
 	 			'url'			=> !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? panel_url('sport_cabors') : NULL,
 	 			'flash'   => !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? $this->session->setFlashdata('alertSwal', [
 	 											'type'		=> 'success',
 	 											'header'	=> '202 Accepted',
 	 											'message'	=> 'Berhasil menghapus <strong>Data</strong> dari sistem',
 	 									 ]) : NULL,
 	 		];

 	 		return requestOutput(202, 'Berhasil menghapus <strong>Cabang Olahraga</strong> yang dipilih', $output);
 	 	}
	 }

	 private function removeAttachment(object $caborData)
	 {
		 if ($caborData->sport_cabor_file_id) {
			 if ($this->modFile->fetchData(['file_id' => $caborData->sport_cabor_file_id])->countAllResults() == true) {
				 $data = (object) [
					 'file' 	=> $this->modFile->fetchData(['file_id' => $caborData->sport_cabor_file_id])->get()->getRow(),
				 ];

				 if (file_exists($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source)) {
					 unlink($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source);
				 }
			 }
		 }

		 $output = [
			 'remove'	=> $caborData->sport_cabor_file_id ? $this->libIonix->deleteQuery('files', ['file_id' => $data->file->file_id]) : NULL,
		 ];

		 return $output;
	 }

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CaborController.php
 * Location: ./app/Controllers/Panel/Sport/Cabor/CaborController.php
 * -----------------------------------------------------------------------
 */
