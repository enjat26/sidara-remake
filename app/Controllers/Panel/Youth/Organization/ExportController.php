<?php namespace App\Controllers\Panel\Youth\Organization;

use App\Controllers\BaseController;

use App\Models\Area\DistrictModel;
use App\Models\Area\ProvinceModel;
use App\Models\Youth\OrganizationModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

  protected $allowedExport = ['print','pdf','excel'];

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
		$this->modDistrict 				= new DistrictModel();
		$this->modOrganization 		= new OrganizationModel();
		$this->modProvince 				= new ProvinceModel();
		$this->dompPdf 						= new Dompdf();
		$this->spreadsheet					= new Spreadsheet();
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
			case 'pdf':
				return $this->pdf();
				break;
			case 'excel':
				return $this->excel();
				break;
		}
	}

	private function data()
	{
		
		$subProvince						= ['province' => NULL];
		$subDistrict						= ['district' => NULL];
		$parameters 						= [];

		if (!empty($this->request->getGet('filter-province'))) {
			if ($this->request->getGet('filter-province') != $this->configIonix->defaultProvince) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$parameters = [
				'provinces.province_id'	=> $this->request->getGet('filter-province'),
			];

			$subProvince = [
				'province'	=> $this->modProvince->fetchData(['province_id' => $parameters['provinces.province_id']])->get()->getRow()->province_name,
			];
		}

		if (!empty($this->request->getGet('filter-district'))) {
			if ($this->modDistrict->fetchData(['district_id' => $this->request->getGet('filter-district')])->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$parameters = [
				'districts.district_id'	=> $this->request->getGet('filter-district'),
			];

			$subDistrict = [
				'district'	=> $this->modDistrict->fetchData(['district_id' => $parameters['districts.district_id']])->get()->getRow()->district_type.' '.$this->modDistrict->fetchData(['district_id' => $parameters['districts.district_id']])->get()->getRow()->district_name,
			];
		}

		// ======================================================================== Breakdown

		if (isStakeholder() == true) {
			$combineParameters = array_merge($parameters, ['youth_organization_created_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]);
		} else {
			$combineParameters = array_merge($parameters);
		}

		if ($this->modOrganization->fetchData($combineParameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Data Organisasi Pemuda ('.parseDate(time()).')',
			'modOrganization'		=> $this->modOrganization,
			'title'							=> 'Data Organisasi Pemuda',
			'subTitle'					=> (object) array_merge($subProvince, $subDistrict),
			'parameters'				=> $combineParameters,
			'qrData'						=> core_url('youths/organizations'),
		];

		return $data;
	}

	private function print()
	{
		return view('panels/youths/organizations/export/print', $this->libIonix->appInit($this->data()));
	}

	private function pdf()
	{
		$options = $this->dompPdf->getOptions();
		$options->set(array('isRemoteEnabled' => true));
		$this->dompPdf->setOptions($options);
		$this->dompPdf->loadHtml(view('panels/youths/organizations/export/pdf', $this->libIonix->appInit($this->data())));
		$this->dompPdf->setPaper('A4', 'landscape');
		$this->dompPdf->render();
		$this->dompPdf->stream($this->data()['title'], array("Attachment" => false));
		exit(0);
	}

	private function excel()
	{
		$this->spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'No')
			->setCellValue('B1', 'Nama Sarana/Prasarana')
			->setCellValue('C1', 'Kategori')
			->setCellValue('D1', 'Jenis/Tipe')
			->setCellValue('E1', 'Tahun')
			->setCellValue('F1', 'Kondisi')
			->setCellValue('G1', 'Pengelolaan')
			->setCellValue('H1', 'Oleh');

		$column = 2;
		$no = 1;
		foreach ($this->data()['modAsset']->fetchData($this->data()['parameters'])->get()->getResult() as $row) {
			$this->spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $column, $no)
				->setCellValue('B' . $column, $row->asset_name)
				->setCellValue('C' . $column, $row->asset_type)
				->setCellValue('D' . $column, $row->asset_category_name)
				->setCellValue('E' . $column, $row->asset_production_year)
				->setCellValue('F' . $column, $row->asset_condition)
				->setCellValue('G' . $column, $row->asset_management)
				->setCellValue('H' . $column, $this->libIonix->getUserData(['users.user_id' => $row->asset_created_by], 'object')->name);
			$column++;
			$no++;
		}

		$writer = new Xlsx($this->spreadsheet);
		$filename = $this->data()['title'];

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Organization/ExportController.php
 * Location: ./app/Controllers/Panel/Organization/ExportController.php
 * -----------------------------------------------------------------------
 */
