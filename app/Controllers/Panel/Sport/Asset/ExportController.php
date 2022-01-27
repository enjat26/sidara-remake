<?php

namespace App\Controllers\Panel\Sport\Asset;

use App\Controllers\BaseController;

use App\Models\Sport\AssetModel;
use App\Models\Area\DistrictModel;
use App\Models\Area\ProvinceModel;
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
		$this->modAsset 					= new AssetModel();
		$this->modDistrict 					= new DistrictModel();
		$this->modProvince 					= new ProvinceModel();
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
		// dd(uri_segment(3));
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
		$typeParameters = [];

		if (!empty($this->request->getGet('filter-type'))) {
			if ($this->modAsset->fetchData(['asset_category_type' => $this->request->getGet('filter-type')])->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$typeParameters	= [
				'asset_category_type'			=> $this->request->getGet('filter-type'),
			];

			$filterData = (object) [
				'asset'		=> $this->modAsset->fetchData(['asset_category_type' => $this->request->getGet('filter-type')])->get()->getRow(),
			];
		}

		if (isStakeholder() == false) {
			$parameters = [];
		} else {
			$parameters = [
				'asset_created_by'				=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		if ($this->modAsset->fetchData(array_merge($parameters, $typeParameters))->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = (object) [
			'title'				=> !empty($this->request->getGet('filter-type')) ? 'Data Sarana & Prasarana (' . parseAssetType($filterData->asset->asset_category_type) . ')' : 'Data Sarana & Prasarana',
			'subTitle'		=> 'Pada Bidang Pemuda',
			'parameters'	=> array_merge($parameters, $typeParameters),
		];

		$data = [
			'paperSize'								=> '8.5in 13in',
			'fileName'								=> strtoupper($this->configIonix->appCode) . ' ' . ucwords($this->configIonix->appType) . ' - ' . $data->title . ' (' . parseDate(time()) . ')',
			'modAsset'								=> $this->modAsset,
			'title'									=> $data->title,
			'subTitle'								=> $data->subTitle,
			'parameters'							=> $data->parameters,
			'qrData'								=> core_url('sports/assets'),
		];

		return $data;
	}

	private function print()
	{
		return view('panels/sports/assets/export/print', $this->libIonix->appInit($this->data()));
	}

	private function pdf()
	{
		$options = $this->dompPdf->getOptions();
		$options->set(array('isRemoteEnabled' => true));
		$this->dompPdf->setOptions($options);
		$this->dompPdf->loadHtml(view('panels/sports/assets/export/pdf', $this->libIonix->appInit($this->data())));
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
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Sport/Asset/ExportController.php
 * -----------------------------------------------------------------------
 */
