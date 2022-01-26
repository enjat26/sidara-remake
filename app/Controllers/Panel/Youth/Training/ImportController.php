<?php namespace App\Controllers\Panel\Youth\Training;

use App\Controllers\BaseController;

use App\Models\Youth\TrainingModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * Class ImportController
 *
 * @package App\Controllers
 */
class ImportController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
	 protected $allowedImport = ['participant'];

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

		$this->spreadSheet  	= new Spreadsheet();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		if (!in_array($this->libIonix->Decode($this->request->getGet('scope')), $this->allowedImport)) {
			throw \CodeIgniter\Exceptions\pageNotFoundException::forPageNotFound();
		}

		switch ($this->libIonix->Decode($this->request->getGet('scope'))) {
			case 'participant':
				return $this->importParticipant();
				break;
		}
	}

	private function importParticipant()
	{
		if ($this->request->getFile('file')->getClientExtension() == 'xls') {
			$sheetReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		} elseif ($this->request->getFile('file')->getClientExtension() == 'xlsx') {
			$sheetReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

		foreach ($sheetReader->load($this->request->getFile('file'))->getActiveSheet()->toArray() as $row) {
			$data = [
				'youth_training_id'												=> $this->libIonix->Decode($this->request->getGet('params')),
				'youth_training_participant_name'					=> ucwords($row[1]),
				'youth_training_participant_location'			=> ucwords($row[2]),
				'youth_training_participant_explanation'	=> $row[3] ? $row[3] : NULL,
			];

			if (!is_int($row[0])) {
				continue;
			}

			$import = [
				'insert'	=> $this->libIonix->insertQuery('youth_training_participants', $data),
			];
		}

		$output = [
			'flash'   => $this->session->setFlashdata('alertToastr', [
											'type'		=> 'success',
											'header'	=> '201 Created',
											'message'	=> 'Berhasil mengimpor <strong>Data Peserta</strong> pada <strong>Pelatihan</strong> ini',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ImportController.php
 * Location: ./app/Controllers/Panel/Youth/Training/ImportController.php
 * -----------------------------------------------------------------------
 */
