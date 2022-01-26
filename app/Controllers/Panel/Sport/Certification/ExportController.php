<?php namespace App\Controllers\Panel\Sport\Certification;

use App\Controllers\BaseController;

use App\Models\Sport\CaborModel;
use App\Models\Sport\CertificationModel;

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
		$this->modCabor 						= new CaborModel();
		$this->modCertification 		= new CertificationModel();
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
		$subTitle								= '';
		$subPrefix							= '';
		$filterYear 					  = [];
		$filterCabor						= [];
		$filterCategory					= [];

		if (!empty($this->request->getGet('filter-year'))) {
			$filterYear = [
				'sport_certification_year'	=> $this->request->getGet('filter-year'),
			];

			$subTitle = '(Tahun '.$filterYear['sport_certification_year'].')';
		}

		if (!empty($this->request->getGet('filter-cabor'))) {
			$filterCabor = [
				'sport_cabors.sport_cabor_code'	=> $this->request->getGet('filter-cabor'),
			];

			if ($this->modCabor->fetchData($filterCabor)->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$subPrefix = ' '.$this->modCabor->fetchData($filterCabor)->get()->getRow()->sport_cabor_name;
		}

		if (!empty($this->request->getGet('filter-category'))) {
			$filterCategory = [
				'sport_certification_category'	=> $this->request->getGet('filter-category'),
			];

			if (!empty($this->request->getGet('filter-year'))) {
				$subTitle = '(Kategori <strong>'.ucwords($filterCategory['sport_certification_category']).'</strong> pada Tahun '.$this->request->getGet('filter-year').')';
			} else {
				$subTitle = '(Kategori <strong>'.ucwords($filterCategory['sport_certification_category']).'</strong>)';
			}
		}

		$combineParameters = array_merge($filterYear, $filterCategory);

		if ($this->modCertification->fetchData($combineParameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Data Peserta Sertifikasi'.$subPrefix.' ('.parseDate(time()).')',
			'modCertification'	=> $this->modCertification,
			'title'							=> 'Data Peserta Sertifikasi'.$subPrefix,
			'subTitle'					=> $subTitle,
			'parameters'				=> $combineParameters,
			'qrData'						=> core_url('sports/certifications'),
		];

		return view('panels/sports/certifications/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Sport/Certification/ExportController.php
 * -----------------------------------------------------------------------
 */
