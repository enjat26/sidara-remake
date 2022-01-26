<?php namespace App\Controllers\Panel\Youth\Scout;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\FileModel;
use App\Models\Youth\ScoutModel;

/**
 * Class YouthController
 *
 * @package App\Controllers
 */
class ScoutController extends BaseController
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
		$this->modProvince 		= new ProvinceModel();
		$this->modFile 				= New FileModel();
		$this->modScout 			= new ScoutModel();
	}

	public function index()
	{
		$data = [
			'modProvince'		=> $this->modProvince,
			'modScout'			=> $this->modScout,
		];

		return view('panels/youths/scouts/scouts', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'scout') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modScout->fetchData(['scout_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getScoutHTML($this->modScout->fetchData(['scout_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function getScoutHTML(object $scoutData)
	{
		if ($scoutData->file_id) {
			$scoutAttachment = '<div class="separator">
															<a href="'.$this->libIonix->generateFileLink($scoutData->file_id, 'view').'" target="_blank" class="btn btn-sm btn-info"><i class="mdi mdi-eye-outline align-middle me-1"></i> Lihat Dokumen</a>
													</div>';
		} else {
			$scoutAttachment = '<div class="alert alert-warning text-center" role="alert">
															<strong>Prestasi Pramuka</strong> ini belum memiliki lampiran atau berkas yang diunggah.
													</div>';
		}

		if ($scoutData->scout_created_by) {
			$userData = '<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $scoutData->scout_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $scoutData->scout_created_by], 'object')->role_color.';">
					<strong>'.$this->libIonix->getUserData(['users.user_id' => $scoutData->scout_created_by], 'object')->name.' ('.$this->libIonix->getUserData(['users.user_id' => $scoutData->scout_created_by], 'object')->role_name.')</strong>
			</a>';
		} else {
			$userData = '<i>NULL</i>';
		}

		$output = [
			'year'					=> $scoutData->year,
			'file'  				=> $scoutAttachment,
			'championship'	=> $scoutData->scout_championship_name,
			'level'					=> $scoutData->scout_championship_level,
			'organizer'			=> $scoutData->scout_championship_organizer,
			'result'				=> $scoutData->scout_championship_result ? $scoutData->scout_championship_result : '-',
			'participant'		=> $scoutData->scout_participant_name,
			'gender'				=> parseGender($scoutData->scout_participant_gender),
			'address'			=> $scoutData->scout_participant_address,
			'district'		=> $scoutData->district_type.' '.$scoutData->district_name,
			'created_by'	=> $userData,
		];

		return requestOutput(200, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Show Method
	 * --------------------------------------------------------------------
	 */

	public function show()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'chart') {
			if ($this->request->getGet('format') == 'JSON') {
				return $this->getChartJSON();
			}
		}
	}

	private function getChartJSON()
	{
		if (isStakeholder() == false) {
			$parameters = [
				'year' 													=> $this->session->year,
			];
		} else {
			$parameters = [
				'year' 													=> $this->session->year,
				'scout_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'gender') {
			$data = (object) [
				'male'			=> $this->modScout->fetchData(array_merge($parameters, ['scout_participant_gender' => 'L']))->countAllResults(),
				'female'		=> $this->modScout->fetchData(array_merge($parameters, ['scout_participant_gender' => 'P']))->countAllResults()
			];

			$output = [
				'label' 		=> ['Laki-laki ('.$data->male.')', 'Perempuan ('.$data->female.')'],
				'dataset'   => [
					'color'	=> ['#556ee6', '#e83e8c'],
					'value' => [$data->male, $data->female],
				],
			];

			return requestOutput(200, NULL, $output);
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'scout') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listScoutDT();
			}
		}
	}

	private function listScoutDT()
	{
		if (isStakeholder() == false) {
			$parameters = [
				'year' 													=> $this->session->year,
			];
		} else {
			$parameters = [
				'year' 													=> $this->session->year,
				'scout_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modScout->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			if ($row->file_id) {
				$scoutAttachment = '<i class="mdi mdi-check-circle-outline align-middle text-success font-size-18"></i>';
			} else {
				$scoutAttachment = '<i class="mdi mdi-alert-circle-outline align-middle text-warning font-size-18"></i>';
			}

			if ($row->scout_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->scout_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->scout_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->scout_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->scout_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">'.$row->scout_participant_name.'</h6>
										 <p class="text-muted mb-0">'.parseGender($row->scout_participant_gender).'</p>';
		  $subArray[] = '<h6 class="text-truncate mb-0">'.$row->scout_championship_name.'</h6>
										 <p class="text-muted mb-0">'.$row->scout_championship_level.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $scoutAttachment . '</p>';
			$subArray[] = '<div class="text-center">'.parseApproveData($row->scout_approve)->badge.'</div>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-view-scout" onclick="getScout(\'' . $this->libIonix->Encode('scout') . '\', \'' . $this->libIonix->Encode($row->scout_id) . '\');"><i class="mdi mdi-eye-outline font-size-16 align-middle text-info me-1"></i> Lihat Rincian</a>
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-scout" onclick="putScout(\'' . $this->libIonix->Encode('scout') . '\', \'' . $this->libIonix->Encode($row->scout_id) . '\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('scout') . '\', \'' . $this->libIonix->Encode($row->scout_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modScout->fetchData($parameters, false)->countAllResults(),
			"recordsFiltered"  => $this->modScout->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'scout') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addScout();
			} else {
				return $this->updateScout($this->modScout->fetchData(['scout_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addScout()
	{
		$requestScout = [
			'district_id'												=> $this->request->getPost('district'),
			'year'															=> $this->session->year,
			'scout_participant_name'						=> ucwords($this->request->getPost('name')),
			'scout_participant_gender'					=> $this->request->getPost('gender'),
			'scout_participant_address'					=> !empty($this->request->getPost('address')) ? ucwords($this->request->getPost('address')) : NULL,
			'scout_championship_name'						=> ucwords($this->request->getPost('championship')),
			'scout_championship_level'					=> $this->request->getPost('level'),
			'scout_championship_organizer'			=> !empty($this->request->getPost('organizer')) ? ucwords($this->request->getPost('organizer')) : NULL,
			'scout_championship_result'					=> !empty($this->request->getPost('result')) ? ucwords($this->request->getPost('result')) : NULL,
			'scout_approve'											=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'scout_approve'											=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'scout_approve_by'									=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'scout_created_by'									=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		$query = (object) [
			'insert'				=> $this->libIonix->insertQuery('youth_scouts', $requestScout),
		];

		$output = [
			'upload'				=> $this->request->getFile('file')->isValid() ? $this->uploadAttachment($this->modScout->fetchData(['scout_id' => $query->insert])->get()->getRow()) : NULL,
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Prestasi Pramuka</strong>baru', $output);
	}

	private function updateScout(object $scoutData)
	{
		$requestScout = [
			'district_id'												=> $this->request->getPost('district'),
			'year'															=> $this->session->year,
			'scout_participant_name'						=> ucwords($this->request->getPost('name')),
			'scout_participant_gender'					=> $this->request->getPost('gender'),
			'scout_championship_name'						=> ucwords($this->request->getPost('championship')),
			'scout_championship_level'					=> $this->request->getPost('level'),
			'scout_championship_organizer'			=> !empty($this->request->getPost('organizer')) ? $this->request->getPost('organizer') : NULL,
			'scout_championship_result'					=> !empty($this->request->getPost('result')) ? $this->request->getPost('result') : NULL,
			'scout_approve'											=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'scout_created_by'									=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		$output = [
			'update'				=> $this->libIonix->updateQuery('youth_scouts', ['scout_id' => $scoutData->scout_id], $requestScout),
			'remove'				=> $this->request->getFile('file')->isValid() ? $this->removeAttachment($scoutData) : NULL,
			'upload'				=> $this->request->getFile('file')->isValid() ? $this->uploadAttachment($scoutData) : NULL,
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Prestasi Pramuka</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Upload Method
	 * --------------------------------------------------------------------
	 */

	private function uploadAttachment(object $scoutData)
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> 'attachment',
			'file_name' 					=> 'Lampiran Prestasi Pramuka - '.$scoutData->scout_participant_name.' - '.$scoutData->scout_championship_name,
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
			'update'		=> $this->libIonix->updateQuery('youth_scouts', ['scout_id' => $scoutData->scout_id], ['file_id' => $upload->insert]),
		];

		return $output;
	}

	private function removeAttachment(object $scoutData)
	{
		if ($scoutData->file_id) {
			if ($this->modFile->fetchData(['file_id' => $scoutData->file_id])->countAllResults() == true) {
				$data = (object) [
					'file' 	=> $this->modFile->fetchData(['file_id' => $scoutData->file_id])->get()->getRow(),
				];

				if (file_exists($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source)) {
					unlink($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source);
				}
			}
		}

		$output = [
			'remove'	=> $scoutData->file_id ? $this->libIonix->deleteQuery('files', ['file_id' => $data->file->file_id]) : NULL,
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'scout') {
			return $this->deleteScout($this->modScout->fetchData(['scout_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteScout(object $scoutData)
	{
		$output = [
			'remove'	=> $this->removeAttachment($scoutData),
			'delete' 	=> $this->libIonix->deleteQuery('youth_scouts', ['scout_id' => $scoutData->scout_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Prestasi Pramuka</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Youth/YouthController.php
 * Location: ./app/Controllers/Panel/Youth/Scout/ScoutController.php
 * -----------------------------------------------------------------------
 */
