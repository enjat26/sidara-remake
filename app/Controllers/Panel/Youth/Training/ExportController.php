<?php namespace App\Controllers\Panel\Youth\Training;

use App\Controllers\BaseController;

use App\Models\Youth\TrainingModel;
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
		$this->modTraining 		= new TrainingModel();
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

	public function detail()
	{
		if (!in_array(uri_segment(3), $this->allowedExport) || $this->modTraining->fetchData(['youth_training_id' => $this->libIonix->Decode(uri_segment(4))])->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		if ($this->libIonix->builderQuery('youth_training_participants')->where(['youth_training_id' => $this->libIonix->Decode(uri_segment(4))])->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
		}

		switch (uri_segment(3)) {
			case 'print':
				return $this->printDetail($this->modTraining->fetchData(['youth_training_id' => $this->libIonix->Decode(uri_segment(4))])->get()->getRow());
				break;
		}
	}

	private function print()
	{
		if (!empty($this->request->getGet('filter-year'))) {
			$parameters = [
				'youth_training_year'	=> $this->request->getGet('filter-year'),
			];

			if ($this->modTraining->fetchData($parameters)->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}

			$data = (object) [
				'title'				=> 'Data Pelatihan Pemuda pada Tahun '.$parameters['youth_training_year'],
				'parameters'	=> $parameters,
			];
		} else {
			$data = (object) [
				'title'				=> 'Data Pelatihan Pemuda',
				'parameters'	=> NULL,
			];
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modTraining'				=> $this->modTraining,
			'title'							=> $data->title,
			'parameters'				=> $data->parameters,
			'qrData'						=> core_url('youths/trainings'),
		];

		return view('panels/youths/trainings/export/print', $this->libIonix->appInit($data));
	}

	private function printDetail(object $trainingData)
	{
		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Rincian Data Pelatihan Pemuda ('.parseDate(time()).')',
			'modTraining'				=> $this->modTraining,
			'title'							=> 'Rincian Data Pelatihan Pemuda',
			'trainingData'			=> $trainingData,
			'parameters'				=> ['youth_training_id' => $trainingData->youth_training_id],
		];

		return view('panels/youths/trainings/export/print-detail', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Youth/Training/ExportController.php
 * -----------------------------------------------------------------------
 */
