<?php

namespace App\Controllers\Panel\Sport\Achievement;

use App\Controllers\BaseController;

use App\Models\Sport\AchievementModel;

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
		$this->modAchievement 		= new AchievementModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		if (!in_array(uri_segment(3), $this->allowedExport)) {
			throw \CodeIgniter\Exceptions\pageNotFoundException::forPageNotFound();
		}

		switch (uri_segment(3)) {
			case 'print':
				return $this->print();
				break;
		}
	}

	private function print()
	{
		// dd($this->modAchievement);
		$data = [
			'paperSize'				=> 'legal landscape',
			'modAchievement'	=> $this->modAchievement,
			'qrData'					=> base_url('sports/achievements'),
			'like'						=> [
				'sport_championship_year'		=> $this->request->getGet('filter-year'),
			],
		];

		return view('panels/sports/achievements/export/print', $this->libIonix->appInit($data));
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Achievement/ExportController.php
 * Location: ./app/Controllers/Panel/Achievement/ExportController.php
 * -----------------------------------------------------------------------
 */
