<?php namespace App\Controllers\Panel\Area;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\Area\VillageModel;

/**
 * Class VillageController
 *
 * @package App\Controllers
 */
class VillageController extends BaseController
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
		$this->modVillage 			= new VillageModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modCountry'   			=> $this->modCountry,
		];

		return view('panels/area/villages', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'village') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modVillage->fetchData(['village_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'village') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listVillageDT();
			}
		}
	}

	private function listVillageDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modVillage->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if (file_exists($this->configIonix->uploadsFolder['flag'].$row->country_iso3.'.jpg')) {
				$flagImage = $this->configIonix->mediaFolder['image'].'flags/'.$row->country_iso3.'.jpg';
			} else {
				$flagImage = $this->configIonix->mediaFolder['image'].'default/country-iso3.jpg';
			}

			if ($row->village_latitude && $row->village_longitude) {
				$villageMap = '<a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->village_type.' '.$row->village_name).'/@'.$row->village_latitude.','.$row->village_longitude.'" target="_blank" class="btn btn-primary waves-effect waves-light btn-sm">Lihat <i class="mdi mdi-arrow-right ms-1"></i></a>';
			} else {
				$villageMap = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$row->village_type.' '.$row->village_name.'</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->sub_district_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->district_type.' '.$row->district_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->province_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">
												<img src="'.$flagImage.'" alt="'.$row->country_name.'" class="rounded me-2" height="20">'.$row->country_name.'
										</p>';
			$subArray[] = '<div class="text-center">'.$villageMap.'</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="javascript:void(0);" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-village" onclick="putVillage(\''.$this->libIonix->Encode($row->village_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('village').'\', \''.$this->libIonix->Encode($row->village_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modVillage->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modVillage->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'village') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addVillage();
			} else {
				return $this->updateVillage($this->modVillage->fetchData(['village_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addVillage()
	{
		$request = [
			'sub_district_id'				=> $this->request->getPost('subdistrict'),
			'village_type'					=> $this->request->getPost('type'),
			'village_name'					=> ucwords($this->request->getPost('name')),
			'village_latitude'			=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'village_longitude'			=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		$parameters = [
			'countrys.country_id' 					=> $this->request->getPost('country'),
			'provinces.province_id' 				=> $this->request->getPost('province'),
			'districts.district_id' 				=> $this->request->getPost('district'),
			'sub_districts.sub_district_id' => $request['sub_district_id'],
			'village_type' 									=> $request['village_type'],
			'village_name' 									=> $request['village_name'],
		];

		if ($this->modVillage->fetchData($parameters)->countAllResults() == true) {
			return requestOutput(406, 'Nama <strong>Desa/Kelurahan</strong> sudah digunakan sebelumnya dalam <strong>Daerah</strong> dan <strong>Wilayah</strong> ini, tidak dapat menggunakan <strong>Desa/Kelurahan</strong> dengan <strong>Daerah</strong> dan <strong>Wilayah</strong> yang sama');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('villages', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['village_name'].'</strong> sebagai <strong>Desa/Kelurahan</strong> baru', $output);
	}

	private function updateVillage(object $villageData)
	{
		$request = [
			'sub_district_id'				=> $this->request->getPost('subdistrict'),
			'village_type'					=> $this->request->getPost('type'),
			'village_name'					=> ucwords($this->request->getPost('name')),
			'village_latitude'			=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'village_longitude'			=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		$parameters = [
			'countrys.country_id' 					=> $this->request->getPost('country'),
			'provinces.province_id' 				=> $this->request->getPost('province'),
			'districts.district_id' 				=> $this->request->getPost('district'),
			'sub_districts.sub_district_id' => $request['sub_district_id'],
			'village_type' 									=> $request['village_type'],
			'village_name' 									=> $request['village_name'],
		];

		if ($this->request->getPost('country') != $villageData->country_id || $this->request->getPost('province') != $villageData->province_id || $this->request->getPost('district') != $villageData->district_id || $this->request->getPost('subdistrict') != $villageData->sub_district_id || $request['village_type'] != $villageData->village_type || $request['village_name'] != $villageData->village_name) {
			if ($this->modVillage->fetchData($parameters)->countAllResults() == true) {
				return requestOutput(406, 'Nama <strong>Desa/Kelurahan</strong> sudah digunakan sebelumnya dalam <strong>Daerah</strong> dan <strong>Wilayah</strong> ini, tidak dapat menggunakan <strong>Desa/Kelurahan</strong> dengan <strong>Daerah</strong> dan <strong>Wilayah</strong> yang sama');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('villages', ['village_id' => $villageData->village_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Desa/Kelurahan</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'village') {
			return $this->deleteVillage($this->modVillage->fetchData(['village_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteVillage(object $villageData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('villages', ['village_id' => $villageData->village_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Desa/Kelurahan</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: VillageController.php
 * Location: ./app/Controllers/Panel/VillageController.php
 * -----------------------------------------------------------------------
 */
