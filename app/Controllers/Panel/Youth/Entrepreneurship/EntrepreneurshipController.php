<?php namespace App\Controllers\Panel\Youth\Entrepreneurship;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\FileModel;
use App\Models\Youth\EntrepreneurshipModel;

/**
 * Class YouthController
 *
 * @package App\Controllers
 */
class EntrepreneurshipController extends BaseController
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
		$this->modProvince 					= new ProvinceModel();
		$this->modFile 							= New FileModel();
		$this->modEntrepreneurship 	= new EntrepreneurshipModel();
	}

	public function index()
	{
		$data = [
			'modProvince'							=> $this->modProvince,
			'modEntrepreneurship'			=> $this->modEntrepreneurship,
		];

		return view('panels/youths/entrepreneurships/entrepreneurships', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'entrepreneurship') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modEntrepreneurship->fetchData(['entrepreneurship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getEntrepreneurshipHTML($this->modEntrepreneurship->fetchData(['entrepreneurship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function getEntrepreneurshipHTML(object $entrepreneurshipData)
	{
		if ($entrepreneurshipData->file_id) {
			$entrepreneurshipAttachment = '<div class="separator">
																				<a href="'.$this->libIonix->generateFileLink($entrepreneurshipData->file_id, 'view').'" target="_blank" class="btn btn-sm btn-info"><i class="mdi mdi-eye-outline align-middle me-1"></i> Lihat Dokumen</a>
																		</div>';
		} else {
			$entrepreneurshipAttachment = '<div class="alert alert-warning text-center" role="alert">
																				<strong>Prestasi Kewirausahaan</strong> ini belum memiliki lampiran atau berkas yang diunggah.
																		</div>';
		}

		if ($entrepreneurshipData->entrepreneurship_created_by) {
			$userData = '<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $entrepreneurshipData->entrepreneurship_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $entrepreneurshipData->entrepreneurship_created_by], 'object')->role_color.';">
					<strong>'.$this->libIonix->getUserData(['users.user_id' => $entrepreneurshipData->entrepreneurship_created_by], 'object')->name.' ('.$this->libIonix->getUserData(['users.user_id' => $entrepreneurshipData->entrepreneurship_created_by], 'object')->role_name.')</strong>
			</a>';
		} else {
			$userData = '<i>NULL</i>';
		}

		$output = [
			'year'				=> $entrepreneurshipData->year,
			'file'  			=> $entrepreneurshipAttachment,
			'name'				=> $entrepreneurshipData->entrepreneurship_business_name,
			'type'				=> $entrepreneurshipData->entrepreneurship_business_type,
			'ownership'		=> $entrepreneurshipData->entrepreneurship_ownership,
			'gender'			=> parseGender($entrepreneurshipData->entrepreneurship_ownership_gender),
			'employee'		=> number_format($entrepreneurshipData->entrepreneurship_total_employee, 0, ",", ".").' Orang',
			'address'			=> $entrepreneurshipData->entrepreneurship_address,
			'district'		=> $entrepreneurshipData->district_type.' '.$entrepreneurshipData->district_name,
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
				'entrepreneurship_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'gender') {
			$data = (object) [
				'male'			=> $this->modEntrepreneurship->fetchData(array_merge($parameters, ['entrepreneurship_ownership_gender' => 'L']))->countAllResults(),
				'female'		=> $this->modEntrepreneurship->fetchData(array_merge($parameters, ['entrepreneurship_ownership_gender' => 'P']))->countAllResults()
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'entrepreneurship') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listEntrepreneurshipDT();
			}
		}
	}

	private function listEntrepreneurshipDT()
	{
		if (isStakeholder() == false) {
			$parameters = [
				'year' 													=> $this->session->year,
			];
		} else {
			$parameters = [
				'year' 													=> $this->session->year,
				'entrepreneurship_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modEntrepreneurship->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			if ($row->file_id) {
				$entrepreneurshipAttachment = '<i class="mdi mdi-check-circle-outline align-middle text-success font-size-18"></i>';
			} else {
				$entrepreneurshipAttachment = '<i class="mdi mdi-alert-circle-outline align-middle text-warning font-size-18"></i>';
			}

			if ($row->entrepreneurship_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->entrepreneurship_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->entrepreneurship_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->entrepreneurship_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->entrepreneurship_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">'.$row->entrepreneurship_business_name.'</h6>
										 <p class="text-muted mb-0">'.$row->entrepreneurship_business_type.'</p>';
		  $subArray[] = '<h6 class="text-truncate mb-0">'.$row->entrepreneurship_ownership.'</h6>
										 <p class="text-muted mb-0">'.parseGender($row->entrepreneurship_ownership_gender).'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $entrepreneurshipAttachment . '</p>';
			$subArray[] = '<div class="text-center">'.parseApproveData($row->entrepreneurship_approve)->badge.'</div>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-view-entrepreneurship" onclick="getEntrepreneurship(\'' . $this->libIonix->Encode('entrepreneurship') . '\', \'' . $this->libIonix->Encode($row->entrepreneurship_id) . '\');"><i class="mdi mdi-eye-outline font-size-16 align-middle text-info me-1"></i> Lihat Rincian</a>
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-entrepreneurship" onclick="putEntrepreneurship(\'' . $this->libIonix->Encode('entrepreneurship') . '\', \'' . $this->libIonix->Encode($row->entrepreneurship_id) . '\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('entrepreneurship') . '\', \'' . $this->libIonix->Encode($row->entrepreneurship_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modEntrepreneurship->fetchData($parameters, false)->countAllResults(),
			"recordsFiltered"  => $this->modEntrepreneurship->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'entrepreneurship') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addEntrepreneurship();
			} else {
				return $this->updateEntrepreneurship($this->modEntrepreneurship->fetchData(['entrepreneurship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addEntrepreneurship()
	{
		$requestEntrepreneurship = [
			'district_id'												=> $this->request->getPost('district'),
			'year'															=> $this->session->year,
			'entrepreneurship_business_name'		=> ucwords($this->request->getPost('name')),
			'entrepreneurship_business_type'		=> ucwords($this->request->getPost('type')),
			'entrepreneurship_ownership'				=> ucwords($this->request->getPost('ownership')),
			'entrepreneurship_ownership_gender'	=> $this->request->getPost('gender'),
			'entrepreneurship_total_employee'		=> !empty($this->request->getPost('employee')) ? $this->request->getPost('employee') : false,
			'entrepreneurship_address'					=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'entrepreneurship_approve'					=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'entrepreneurship_approve_by'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'entrepreneurship_created_by'				=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		$query = (object) [
			'insert'				=> $this->libIonix->insertQuery('youth_entrepreneurships', $requestEntrepreneurship),
		];

		$output = [
			'upload'				=> $this->request->getFile('file')->isValid() ? $this->uploadAttachment($this->modEntrepreneurship->fetchData(['entrepreneurship_id' => $query->insert])->get()->getRow()) : NULL,
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Prestasi Kewirausahaan</strong>baru', $output);
	}

	private function updateEntrepreneurship(object $entrepreneurshipData)
	{
		$requestEntrepreneurship = [
			'district_id'												=> $this->request->getPost('district'),
			'year'															=> $this->session->year,
			'entrepreneurship_business_name'		=> ucwords($this->request->getPost('name')),
			'entrepreneurship_business_type'		=> ucwords($this->request->getPost('type')),
			'entrepreneurship_ownership'				=> ucwords($this->request->getPost('ownership')),
			'entrepreneurship_ownership_gender'	=> $this->request->getPost('gender'),
			'entrepreneurship_total_employee'		=> !empty($this->request->getPost('employee')) ? $this->request->getPost('employee') : false,
			'entrepreneurship_address'					=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'entrepreneurship_approve'					=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'entrepreneurship_created_by'				=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		$output = [
			'update'				=> $this->libIonix->updateQuery('youth_entrepreneurships', ['entrepreneurship_id' => $entrepreneurshipData->entrepreneurship_id], $requestEntrepreneurship),
			'remove'				=> $this->request->getFile('file')->isValid() ? $this->removeAttachment($entrepreneurshipData) : NULL,
			'upload'				=> $this->request->getFile('file')->isValid() ? $this->uploadAttachment($entrepreneurshipData) : NULL,
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Pelaku Usaha</strong> untuk <strong>Prestasi Kewirausahaan</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Upload Method
	 * --------------------------------------------------------------------
	 */

	private function uploadAttachment(object $entrepreneurshipData)
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> 'attachment',
			'file_name' 					=> 'Lampiran Prestasi Kewirausahaan - '.$entrepreneurshipData->entrepreneurship_business_name,
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
			'update'		=> $this->libIonix->updateQuery('youth_entrepreneurships', ['entrepreneurship_id' => $entrepreneurshipData->entrepreneurship_id], ['file_id' => $upload->insert]),
		];

		return $output;
	}

	private function removeAttachment(object $entrepreneurshipData)
	{
		if ($entrepreneurshipData->file_id) {
			if ($this->modFile->fetchData(['file_id' => $entrepreneurshipData->file_id])->countAllResults() == true) {
				$data = (object) [
					'file' 	=> $this->modFile->fetchData(['file_id' => $entrepreneurshipData->file_id])->get()->getRow(),
				];

				if (file_exists($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source)) {
					unlink($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source);
				}
			}
		}

		$output = [
			'remove'	=> $entrepreneurshipData->file_id ? $this->libIonix->deleteQuery('files', ['file_id' => $data->file->file_id]) : NULL,
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'entrepreneurship') {
			return $this->deleteEntrepreneurship($this->modEntrepreneurship->fetchData(['entrepreneurship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteEntrepreneurship(object $entrepreneurshipData)
	{
		$output = [
			'remove'	=> $this->removeAttachment($entrepreneurshipData),
			'delete' 	=> $this->libIonix->deleteQuery('youth_entrepreneurships', ['entrepreneurship_id' => $entrepreneurshipData->entrepreneurship_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Prestasi Kewirausahaan</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Youth/YouthController.php
 * Location: ./app/Controllers/Panel/Youth/Entrepreneurship/EntrepreneurshipController.php
 * -----------------------------------------------------------------------
 */
