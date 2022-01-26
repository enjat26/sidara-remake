<?php namespace App\Controllers\Panel\Area\District;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\Area\DistrictModel;

/**
 * Class DistrictController
 *
 * @package App\Controllers
 */
class DistrictController extends BaseController
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
		$this->modDistrict 		= new DistrictModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modCountry'		=> $this->modCountry,
			'modDistrict'		=> $this->modDistrict,
		];

		return view('panels/area/districts/districts', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'district') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modDistrict->fetchData(['district_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'district') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listDistrictDT();
			}
		}
	}

	private function listDistrictDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modDistrict->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if (file_exists($this->configIonix->uploadsFolder['flag'].$row->country_iso3.'.jpg')) {
				$flagImage = $this->configIonix->mediaFolder['image'].'flags/'.$row->country_iso3.'.jpg';
			} else {
				$flagImage = $this->configIonix->mediaFolder['image'].'default/country-iso3.jpg';
			}

			if ($row->district_latitude && $row->district_longitude) {
				$districtMap = '<a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->district_type.' '.$row->district_name).'/@'.$row->district_latitude.','.$row->district_longitude.'" target="_blank" class="btn btn-primary waves-effect waves-light btn-sm">Lihat <i class="mdi mdi-arrow-right ms-1"></i></a>';
			} else {
				$districtMap = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->district_type.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$row->district_name.'</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->province_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">
												<img src="'.$flagImage.'" alt="'.$row->country_name.'" class="rounded me-2" height="20">'.$row->country_name.'
										</p>';
			$subArray[] = '<div class="text-center">'.$districtMap.'</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="javascript:void(0);" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-district" onclick="putDistrict(\''.$this->libIonix->Encode($row->district_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('district').'\', \''.$this->libIonix->Encode($row->district_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modDistrict->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modDistrict->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'district') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addDistrict();
			} else {
				return $this->updateDistrict($this->modDistrict->fetchData(['district_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addDistrict()
	{
		$request = [
			'province_id'						=> $this->request->getPost('province'),
			'district_type'					=> ucwords($this->request->getPost('type')),
			'district_name'					=> ucwords($this->request->getPost('name')),
			'district_latitude'			=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'district_longitude'		=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		$parameters = [
			'countrys.country_id'		=> $this->request->getPost('country'),
			'provinces.province_id' => $request['province_id'],
			'district_type' 				=> $request['district_type'],
			'district_name' 				=> $request['district_name']
		];

		if ($this->modDistrict->fetchData($parameters)->countAllResults() == true) {
			return requestOutput(406, 'Nama <strong>Daerah</strong> sudah digunakan sebelumnya dalam <strong>Provinsi</strong> dan <strong>Negara</strong> ini, tidak dapat menggunakan <strong>Daerah</strong> dengan <strong>Provinsi</strong> dan <strong>Negara</strong> yang sama');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('districts', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['district_name'].'</strong> sebagai <strong>Daerah</strong> baru', $output);
	}

	private function updateDistrict(object $districtData)
	{
		$request = [
			'province_id'						=> $this->request->getPost('province'),
			'district_name'					=> ucwords($this->request->getPost('name')),
			'district_type'					=> ucwords($this->request->getPost('type')),
			'district_latitude'			=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'district_longitude'		=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('longitude') : NULL,
		];

		$parameters = [
			'countrys.country_id'		=> $this->request->getPost('country'),
			'provinces.province_id' => $request['province_id'],
			'district_type' 				=> $request['district_type'],
			'district_name' 				=> $request['district_name']
		];

		if ($this->request->getPost('country') != $districtData->country_id || $request['province_id'] != $districtData->province_id || $request['district_type'] != $districtData->district_type || $request['district_name'] != $districtData->district_name) {
			if ($this->modDistrict->fetchData($parameters)->countAllResults() == true) {
				return requestOutput(406, 'Nama <strong>Daerah</strong> sudah digunakan sebelumnya dalam <strong>Provinsi</strong> dan <strong>Negara</strong> ini, tidak dapat menggunakan <strong>Daerah</strong> dengan <strong>Provinsi</strong> dan <strong>Negara</strong> yang sama');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('districts', ['district_id' => $districtData->district_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Daerah</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'district') {
			return $this->deleteDistrict($this->modDistrict->fetchData(['district_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteDistrict(object $districtData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('districts', ['district_id' => $districtData->district_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Daerah</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: DistrictController.php
 * Location: ./app/Controllers/Panel/Area/District/DistrictController.php
 * -----------------------------------------------------------------------
 */
