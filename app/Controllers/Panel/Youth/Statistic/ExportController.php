<?php namespace App\Controllers\Panel\Youth\Statistic;

use App\Controllers\BaseController;

use App\Models\Youth\StatisticModel;
use App\Models\Area\ProvinceModel;

/**
 * Class ExportController
 *
 * @package App\Controllers
 */
class ExportController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */

	 protected $allowedExport = ['print'];

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
		$this->modStatistic 	= new StatisticModel();
		$this->modProvince 		= new ProvinceModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		if (!in_array(uri_segment(3), $this->allowedExport)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		switch (uri_segment(3)) {
			case 'print':
				return $this->print();
				break;
		}
	}

	private function print()
	{
		$parameters = [
			'year'		=> $this->session->year,
		];

		if ($this->modStatistic->fetchData($parameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = (object) [
			'title'				=> 'Statistik Pemuda SeProvinsi '.$this->modProvince->fetchData(['province_id' => $this->configIonix->defaultProvince])->get()->getRow()->province_name,
			'subTitle'		=> 'Pada Tahun '.$parameters['year'],
			'parameters'	=> $parameters,
		];

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modStatistic'			=> $this->modStatistic,
			'title'							=> $data->title,
			'subTitle'					=> $data->subTitle,
			'parameters'				=> $data->parameters,
			'spCountStatistic'	=> $this->dbDefault->query('CALL sp_count_youth_statistic('.$this->session->year.')')->getRow(),
			'qrData'						=> core_url('youths/statistics?year='.$this->session->year),
		];

		return view('panels/youths/statistics/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Youth/Statistic/ExportController.php
 * -----------------------------------------------------------------------
 */
