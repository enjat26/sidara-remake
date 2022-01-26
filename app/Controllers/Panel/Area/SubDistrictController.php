<?php namespace App\Controllers\Panel\Area;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\Area\SubDistrictModel;

/**
 * Class SubDistrictController
 *
 * @package App\Controllers
 */
class SubDistrictController extends BaseController
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
		$this->modCountry 			= new CountryModel();
		$this->modSubDistrict 	= new SubDistrictModel();
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
		];

		return view('panels/area/subdistricts', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'subdistrict') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modSubDistrict->fetchData(['sub_district_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'subdistrict') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listSubDistrictDT();
			}
		}
	}

	private function listSubDistrictDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modSubDistrict->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if (file_exists($this->configIonix->uploadsFolder['flag'].$row->country_iso3.'.jpg')) {
				$flagImage = $this->configIonix->mediaFolder['image'].'flags/'.$row->country_iso3.'.jpg';
			} else {
				$flagImage = $this->configIonix->mediaFolder['image'].'default/country-iso3.jpg';
			}

			if ($row->sub_district_latitude && $row->sub_district_longitude) {
				$subdistrictMap = '<a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->sub_district_name).'/@'.$row->sub_district_latitude.','.$row->sub_district_longitude.'" target="_blank" class="btn btn-primary waves-effect waves-light btn-sm">Lihat <i class="mdi mdi-arrow-right ms-1"></i></a></div>';
			} else {
				$subdistrictMap = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$row->sub_district_name.'</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->district_type.' '.$row->district_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->province_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">
												<img src="'.$flagImage.'" alt="'.$row->country_name.'" class="rounded me-2" height="20">'.$row->country_name.'
										</p>';
			$subArray[] = '<div class="text-center">'.$subdistrictMap.'</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="javascript:void(0);" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-subdistrict" onclick="putSubDistrict(\''.$this->libIonix->Encode($row->sub_district_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('subdistrict').'\', \''.$this->libIonix->Encode($row->sub_district_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modSubDistrict->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modSubDistrict->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'subdistrict') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addSubDistrict();
			} else {
				return $this->updateSubDistrict($this->modSubDistrict->fetchData(['sub_district_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addSubDistrict()
	{
		$request = [
			'district_id'								=> $this->request->getPost('district'),
			'sub_district_name'					=> ucwords($this->request->getPost('name')),
			'sub_district_latitude'			=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'sub_district_longitude'		=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		$parameters = [
			'countrys.country_id'			=> $this->request->getPost('country'),
			'provinces.province_id' 	=> $this->request->getPost('province'),
			'districts.district_id' 	=> $request['district_id'],
			'sub_district_name' 			=> $request['sub_district_name']
		];

		if ($this->modSubDistrict->fetchData($parameters)->countAllResults() == true) {
			return requestOutput(406, 'Nama <strong>Kecamatan</strong> sudah digunakan sebelumnya dalam <strong>Daerah</strong> ini, tidak dapat menggunakan <strong>Kecamatan</strong> dengan <strong>Daerah</strong> yang sama');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('sub_districts', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['sub_district_name'].'</strong> sebagai <strong>Kecamatan</strong> baru', $output);
	}

	private function updateSubDistrict(object $subDistrictData)
	{
		$request = [
			'district_id'								=> $this->request->getPost('district'),
			'sub_district_name'					=> ucwords($this->request->getPost('name')),
			'sub_district_latitude'			=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'sub_district_longitude'		=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		$parameters = [
			'countrys.country_id'		=> $this->request->getPost('country'),
			'provinces.province_id' => $this->request->getPost('province'),
			'districts.district_id' => $request['district_id'],
			'sub_district_name' 		=> $request['sub_district_name']
		];

		if ($this->request->getPost('country') != $subDistrictData->country_id || $this->request->getPost('province') != $subDistrictData->province_id || $request['district_id'] != $subDistrictData->district_id || $request['sub_district_name'] != $subDistrictData->sub_district_name) {
			if ($this->modSubDistrict->fetchData($parameters)->countAllResults() == true) {
				return requestOutput(406, 'Nama <strong>Kecamatan</strong> sudah digunakan sebelumnya dalam <strong>Daerah</strong> ini, tidak dapat menggunakan <strong>Kecamatan</strong> dengan <strong>Daerah</strong> yang sama');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('sub_districts', ['sub_district_id' => $subDistrictData->sub_district_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Kecamatan</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'subdistrict') {
			return $this->deleteSubDistrict($this->modSubDistrict->fetchData(['sub_district_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteSubDistrict(object $subDistrictData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sub_districts', ['sub_district_id' => $subDistrictData->sub_district_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Kecamatan</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: SubDistrictController.php
 * Location: ./app/Controllers/Panel/Area/SubDistrictController.php
 * -----------------------------------------------------------------------
 */
