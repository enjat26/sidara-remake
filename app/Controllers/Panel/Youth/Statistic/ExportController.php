<?php

namespace App\Controllers\Panel\Youth\Statistic;

use App\Controllers\BaseController;

use App\Models\Youth\StatisticModel;
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

	protected $allowedExport = ['print', 'pdf', 'excel'];

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
		$parameters = [
			'year'		=> $this->session->year,
		];

		if ($this->modStatistic->fetchData($parameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = (object) [
			'title'				=> 'Statistik Pemuda SeProvinsi ' . $this->modProvince->fetchData(['province_id' => $this->configIonix->defaultProvince])->get()->getRow()->province_name,
			'subTitle'		=> 'Pada Tahun ' . $parameters['year'],
			'parameters'	=> $parameters,
		];

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode) . ' ' . ucwords($this->configIonix->appType) . ' - ' . $data->title . ' (' . parseDate(time()) . ')',
			'modStatistic'			=> $this->modStatistic,
			'title'							=> $data->title,
			'subTitle'					=> $data->subTitle,
			'parameters'				=> $data->parameters,
			'spCountStatistic'	=> $this->dbDefault->query('CALL sp_count_youth_statistic(' . $this->session->year . ')')->getRow(),
			'qrData'						=> core_url('youths/statistics?year=' . $this->session->year),
		];

		return $data;
	}

	private function print()
	{
		return view('panels/youths/statistics/export/print', $this->libIonix->appInit($this->data()));
	}

	private function pdf()
	{
		$options = $this->dompPdf->getOptions();
		$options->set(array('isRemoteEnabled' => true));
		$this->dompPdf->setOptions($options);
		$this->dompPdf->loadHtml(view('panels/youths/statistics/export/pdf', $this->libIonix->appInit($this->data())));
		$this->dompPdf->setPaper('A4', 'landscape');
		$this->dompPdf->render();
		$this->dompPdf->stream($this->data()['title'], array("Attachment" => false));
		exit(0);
	}

	private function excel()
	{
		$this->spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'No')
			->setCellValue('B1', 'Uraian')
			->setCellValue('C1', 'Kota/Kab')
			->setCellValue('D1', 'Laki-laki')
			->setCellValue('E1', 'Perempuan')
			->setCellValue('F1', 'Total')
			->setCellValue('G1', 'Keterangan');

		$column = 2;
		$no = 1;
		foreach ($this->data()['modStatistic']->fetchData($this->data()['parameters'])->get()->getResult() as $row) {
			$this->spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $column, $no)
				->setCellValue('B' . $column, 'Pemuda Usia 16-30 Tahun')
				->setCellValue('C' . $column, $row->district_name)
				->setCellValue('D' . $column, number_format($row->statistic_male, 0, ",", "."))
				->setCellValue('E' . $column, number_format($row->statistic_female, 0, ",", "."))
				->setCellValue('F' . $column, number_format(($row->statistic_male + $row->statistic_female), 0, ",", "."))
				->setCellValue('G' . $column, ($row->statistic_explanation) ? $row->statistic_explanation : '-');
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
 * Location: ./app/Controllers/Panel/Youth/Statistic/ExportController.php
 * -----------------------------------------------------------------------
 */
