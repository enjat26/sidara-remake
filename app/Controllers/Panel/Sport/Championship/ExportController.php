<?php namespace App\Controllers\Panel\Sport\Championship;

use App\Controllers\BaseController;

use App\Models\Sport\Championship\ChampionshipModel;

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
		$this->modChampionship 		= new ChampionshipModel();
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
		if (!empty($this->request->getGet('filter-year'))) {
			$parameters = [
				'sport_championship_year'	=> $this->request->getGet('filter-year'),
			];

			if ($this->modChampionship->fetchData($parameters)->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}

			$data = (object) [
				'title'				=> 'Data Kejuaraan Olahraga pada Tahun '.$parameters['sport_championship_year'],
				'parameters'	=> $parameters,
			];
		} else {
			$data = (object) [
				'title'				=> 'Data Kejuaraan Olahraga',
				'parameters'	=> [],
			];
		}

		if (isStakeholder() == true) {
			$combineParameters = array_merge($data->parameters, ['sport_championship_created_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]);
		} else {
			$combineParameters = array_merge($data->parameters);
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modChampionship'		=> $this->modChampionship,
			'title'							=> $data->title,
			'parameters'				=> $combineParameters,
			'qrData'						=> core_url('sports/championships'),
		];

		return view('panels/sports/championships/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Championship/ExportController.php
 * Location: ./app/Controllers/Panel/Championship/ExportController.php
 * -----------------------------------------------------------------------
 */
