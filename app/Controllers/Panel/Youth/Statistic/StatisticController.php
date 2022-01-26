<?php namespace App\Controllers\Panel\Youth\Statistic;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\Youth\StatisticModel;

/**
 * Class YouthController
 *
 * @package App\Controllers
 */
class StatisticController extends BaseController
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
		$this->modStatistic 	= new StatisticModel();
	}

	public function index()
	{
		$data = [
			'modProvince'				=> $this->modProvince,
			'modStatistic'			=> $this->modStatistic,
			'spCountStatistic'	=> $this->dbDefault->query('CALL sp_count_youth_statistic('.$this->session->year.')')->getRow(),
		];

		return view('panels/youths/statistics/statistics', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'statistic') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modStatistic->fetchData(['statistic_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
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

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'percentage') {
			if ($this->request->getGet('format') == 'HTML') {
				return $this->getPercentageHTML();
			}
		}
	}

	private function getChartJSON()
	{
		$districtData = [];

		$parameters = [
			'year' 								=> $this->session->year,
		];

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'statistic') {
			foreach ($this->libIonix->builderQuery('years')->get()->getResult() as $row) {
				$statisticYear[]						= $row->year;
				$statisticMale[]						= $this->dbDefault->query('CALL sp_count_youth_statistic('.$row->year.')')->getRow()->total_male;
				$statisticFemale[]					= $this->dbDefault->query('CALL sp_count_youth_statistic('.$row->year.')')->getRow()->total_female;
			}

			$output = [
				'label' 						=> ['Laki-laki', 'Perempuan'],
				'color'							=> ['#556ee6', '#34c38f'],
				'year'							=> $statisticYear,
				'male' 							=> $statisticMale,
				'female' 						=> $statisticFemale,
			];

			return requestOutput(200, NULL, $output);
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'district') {
			foreach ($this->modStatistic->fetchData($parameters)->get()->getResult() as $row) {
				$districtData[] = [
					'id'					=> $row->district_id,
					'name' 				=> $row->district_type . ' ' . $row->district_name,
					'value'				=> $row->statistic_male + $row->statistic_female,
					'color'				=> '#556ee6',
					'latitude'		=> floatval($row->district_latitude),
					'longitude'		=> floatval($row->district_longitude),
				];
			}

			$output = [
				'provinceData'		=> $this->modProvince->fetchData(['province_id' => $this->configIonix->defaultProvince])->get()->getRow(),
				'districtData' 		=> $districtData,
			];

			return requestOutput(200, NULL, $output);
		}
	}

	private function getPercentageHTML()
	{
		$parameters = [
			'year' 								=> $this->session->year,
		];

		$query = (object) [
			'spCountStatistic'		=> $this->dbDefault->query('CALL sp_count_youth_statistic('.$this->session->year.')')->getRow(),
		];

		foreach ($this->modStatistic->fetchData($parameters)->get()->getResult() as $row) {
			if ($row->statistic_male && $row->statistic_female) {
				$result = (($row->statistic_male + $row->statistic_female) / $query->spCountStatistic->total) * 100;
			} else {
				$result = 0;
			}

			echo '<div class="mb-3">
								<h4 class="card-title">' . $row->district_type . ' ' . $row->district_name . '</h4>
								<p class="card-title-desc mb-1">Wilayah ini memiliki <strong>' . number_format($row->statistic_male + $row->statistic_female, 0, ",", ".") . ' Pemuda</strong> dari keseluruhan.</p>

								<div class="progress progress-xl">
										<div class="progress-bar" role="progressbar" style="width: ' . floor($result) . '%;" aria-valuenow="' . floor($result) . '" aria-valuemin="0" aria-valuemax="100">' . floor($result) . '%</div>
								</div>
						</div>';
		}
		exit;
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'statistic') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listYouthDT();
			}
		}
	}

	private function listYouthDT()
	{
		$parameters = [
			'year' 	=> $this->session->year,
		];

		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modStatistic->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			if ($row->statistic_explanation) {
				$statisticExplanation = $row->statistic_explanation;
			} else {
				$statisticExplanation = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->district_type . ' ' . $row->district_name . '</h6>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . number_format($row->statistic_male, 0, ",", ".") . '</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . number_format($row->statistic_female, 0, ",", ".") . '</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . number_format($row->statistic_male + $row->statistic_female, 0, ",", ".") . '</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $statisticExplanation . '</p>';
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-statistic" onclick="putStatistic(\'' . $this->libIonix->Encode('statistic') . '\', \'' . $this->libIonix->Encode($row->statistic_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('statistic') . '\', \'' . $this->libIonix->Encode($row->statistic_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modStatistic->fetchData($parameters, false)->countAllResults(),
			"recordsFiltered"  => $this->modStatistic->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'statistic') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addStatistic();
			} else {
				return $this->updateStatistic($this->modStatistic->fetchData(['statistic_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addStatistic()
	{
		$request = [
			'district_id'								=> $this->request->getPost('district'),
			'year'											=> $this->session->year,
			'statistic_male'						=> $this->request->getPost('male'),
			'statistic_female'					=> $this->request->getPost('female'),
			'statistic_explanation'			=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
			'statistic_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		if ($this->modStatistic->fetchData(['districts.district_id' => $request['district_id'], 'year' => $request['year']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Statistik Pemuda</strong> pada kota/kab tahun ini sudah ada, tidak dapat menambahkan <strong>Statistik Pemuda</strong> pada kota/kab dan tahun yang sama.');
		}

		$output = [
			'insert'		=> $this->libIonix->insertQuery('youth_statistics', $request),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Statistik Pemuda</strong> pada tahun <strong>' . $request['year'] . '</strong> untuk daerah ini', $output);
	}

	private function updateStatistic(object $statisticData)
	{
		$request = [
			'district_id'								=> $this->request->getPost('district'),
			'year'											=> $this->session->year,
			'statistic_male'						=> $this->request->getPost('male'),
			'statistic_female'					=> $this->request->getPost('female'),
			'statistic_explanation'			=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
		];

		if ($statisticData->district_id != $request['district_id']) {
			if ($this->modStatistic->fetchData(['districts.district_id' => $request['district_id'], 'year' => $request['year']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Statistik Pemuda</strong> pada kota/kab tahun ini sudah ada, tidak dapat menambahkan <strong>Statistik Pemuda</strong> pada kota/kab dan tahun yang sama.');
			}
		}

		$output = [
			'update'		=> $this->libIonix->updateQuery('youth_statistics', ['statistic_id' => $statisticData->statistic_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Statistik Pemuda</strong> untuk daerah ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'statistic') {
			return $this->deleteYouth($this->modStatistic->fetchData(['statistic_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteYouth(object $youthData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('youth_statistics', ['statistic_id' => $youthData->statistic_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Statistik Pemuda</strong> pada <strong>Kota/Kab</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Youth/YouthController.php
 * Location: ./app/Controllers/Panel/Youth/Statistic/StatisticController.php
 * -----------------------------------------------------------------------
 */
