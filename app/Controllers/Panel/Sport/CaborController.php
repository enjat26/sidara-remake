<?php namespace App\Controllers\Panel\Sport;

use App\Controllers\BaseController;

use App\Models\Sport\CaborModel;

/**
 * Class CaborController
 *
 * @package App\Controllers
 */
class CaborController extends BaseController
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
		$this->modCabor 		= new CaborModel();
	}

	public function index()
	{
		$data = [
			'modCabor'			=> $this->modCabor,
			'resultCabor'		=> $this->modCabor->fetchData()->paginate(12, 'sport_cabors'),
			'pageCabor'			=> $this->modCabor->fetchData()->pager,
		];

		return view('panels/sports/cabors/cabors', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		$parameters = [
			'sport_cabor_id'			=> $this->libIonix->Decode(uri_segment(2)),
		];

		if ($this->modCabor->fetchData($parameters)->countAllResults() == true) {
			$data = [
				'modCabor'				=> $this->modCabor,
				'caborData'				=> $this->modCabor->fetchData($parameters)->get()->getRow(),
			];

			return view('panels/sports/cabors/cabor-detail', $this->libIonix->appInit($data));
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getCaborHTML($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function getCaborHTML(object $caborData)
	{
		$output = [
			'avatar'				=> $caborData->sport_cabor_avatar ? core_url('content/cabor/'.$this->libIonix->Encode($caborData->sport_cabor_id).'/'.$this->libIonix->Encode($caborData->sport_cabor_avatar)) : $this->configIonix->mediaFolder['image'].'default/logo.jpg',
			'name'					=> $caborData->sport_cabor_name,
			'code'					=> strtoupper($caborData->sport_cabor_code),
			'description'		=> $caborData->sport_cabor_description ? $caborData->sport_cabor_description : '<i>Cabang Olahraga ini tidak memiliki deskripsi</i>',
			'content'				=> $caborData->sport_cabor_content ? $caborData->sport_cabor_content : '<p class="text-center mb-0"><i>Cabang Olahraga ini tidak memiliki <strong>Konten</strong></i></p>',
		];

		return requestOutput(200, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addCabor();
			} else {
				return $this->updateCabor($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'type') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addType($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}
	}

	private function addCabor()
	{
		$request = [
			'sport_cabor_code'		=> strtolower($this->request->getPost('code')),
			'sport_cabor_name'		=> ucwords($this->request->getPost('name')),
			'sport_cabor_avatar'	=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->getRandomName() : NULL,
		];

		if (regexUsername($request['sport_cabor_code']) == false) {
			return requestOutput(411, 'Format <strong>Kode Cabang Olahraga</strong> yang Anda gunakan tidak benar');
		}

		if ($this->modCabor->fetchData(['sport_cabor_code' => $request['sport_cabor_code']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Kode Cabang Olahraga</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode</strong> yang sama');
		}

		$query = (object) [
			'insert'		=> $this->libIonix->insertQuery('sport_cabors', $request),
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder['cabor'].$query->insert,
			'filename'	=> $request['sport_cabor_avatar'],
		];

		$output = [
			'create'		=> !is_dir($config['directory']) ? mkdir($config['directory'], 0777, true) : NULL,
			'upload'		=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config['directory'], $config['filename'], true) : NULL,
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '201 Created',
												'message'	=> 'Berhasil menambahkan <strong>Cabang Olahraga</strong> baru',
										 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	private function updateCabor(object $caborData)
	{
		$request = [
			'sport_cabor_name'						=> ucwords($this->request->getPost('name')),
			'sport_cabor_description'			=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
			'sport_cabor_content'					=> !empty($this->request->getPost('content')) ? $this->request->getPost('content') : NULL,
		];

		$output = [
			'update'		=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id], $request),
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '202 Accepted',
												'message'	=> 'Berhasil merubah informasi <strong>Cabang Olahraga</strong> ini',
										 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addType(object $caborData)
	{
		$request = [
			'sport_cabor_id'					=> $caborData->sport_cabor_id,
			'sport_cabor_type_name'		=> ucwords($this->request->getPost('type')),
		];

		if ($this->libIonix->builderQuery('sport_cabor_types')->where(['sport_cabor_id' => $request['sport_cabor_id'], 'sport_cabor_type_name' => $request['sport_cabor_type_name']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Jenis</strong> ini sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Jenis</strong> pada <strong>Cabang Olahraga</strong> yang sama');
		}

		$output = [
			'insert'		=> $this->libIonix->insertQuery('sport_cabor_types', $request),
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '201 Created',
												'message'	=> 'Berhasil menambahkan <strong>Jenis '.$request['sport_cabor_type_name'].'</strong> pada <strong>Cabang Olahraga</strong> ini',
										 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Update Method
	 * --------------------------------------------------------------------
	 */

	public function update()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->updateImage($this->modCabor->fetchData(['sport_cabor_id' => explode('|', $this->libIonix->Decode($this->request->getGet('id')))[1]])->get()->getRowArray());
		}
	}

	private function updateImage(array $caborData)
	{
		$parameters = (object) [
			'image'		=> $caborData['sport_cabor_'.explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0]],
		];

		$data = [
			'directory' 	=> $this->configIonix->uploadsFolder['cabor'].$caborData['sport_cabor_id'],
			'fileName'		=> $this->request->getFile('image')->getRandomName(),
		];

		if (isset($parameters->image) && file_exists($this->configIonix->uploadsFolder['cabor'].$caborData['sport_cabor_id'].'/'.$parameters->image)) {
			unlink($this->configIonix->uploadsFolder['cabor'].$caborData['sport_cabor_id'].'/'.$parameters->image);
		}

		$output = [
			'upload'	=> $this->request->getFile('image')->move($data['directory'], $data['fileName'], true),
			'update'	=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData['sport_cabor_id']], ['sport_cabor_'.explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0] => $data['fileName']]),
		];

		return requestOutput(202, 'Berhasil mengunggah <strong>Gambar</strong> yang dipilih sebagai <strong>'.ucwords(explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0]).'</strong>', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			return $this->deleteCabor($this->modCabor->fetchData(['sport_cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->deleteImage($this->modCabor->fetchData(['sport_cabor_id' => explode('|', $this->libIonix->Decode($this->request->getGet('id')))[1]])->get()->getRowArray());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'type') {
			return $this->deleteType($this->libIonix->getQuery('sport_cabor_types', NULL, ['sport_cabor_type_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getRow());
		}
	}

	private function deleteCabor(object $caborData)
	{
		if (is_dir($this->configIonix->uploadsFolder['cabor'].$caborData->sport_cabor_id)) {
			delete_files($this->configIonix->uploadsFolder['cabor'].$caborData->sport_cabor_id, TRUE);
			rmdir($this->configIonix->uploadsFolder['cabor'].$caborData->sport_cabor_id);
		}

		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sport_cabors', ['sport_cabor_id' => $caborData->sport_cabor_id]),
			'flash'   => $this->session->setFlashdata('alertSwal', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Cabang Olahraga</strong> yang dipilih',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function deleteImage(array $caborData)
	{
		$parameters = (object) [
			'image'		=> explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0],
		];

		if (!$caborData['sport_cabor_'.$parameters->image]) {
			return requestOutput(406, '<strong>Cabang Olahraga</strong> ini tidak memiliki <strong>'.ucwords($parameters->image).'</strong>, tidak ada yang dapat dihapus');
		}

		if (file_exists($this->configIonix->uploadsFolder['cabor'].$caborData['sport_cabor_id'].'/'.$caborData['sport_cabor_'.$parameters->image])) {
			unlink($this->configIonix->uploadsFolder['cabor'].$caborData['sport_cabor_id'].'/'.$caborData['sport_cabor_'.$parameters->image]);
		}

		$output = [
			'delete' 	=> $this->libIonix->updateQuery('sport_cabors', ['sport_cabor_id' => $caborData['sport_cabor_id']], ['sport_cabor_'.$parameters->image => NULL]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>'.ucwords($parameters->image).'</strong> pada <strong>Cabang Olahraga</strong> ini', $output);
	}

	private function deleteType(object $typeData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sport_cabor_types', ['sport_cabor_type_id' => $typeData->sport_cabor_type_id]),
			'flash'   => $this->session->setFlashdata('alertSwal', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Jenis</strong> pada <strong>Cabang Olahraga</strong> ini.',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CaborController.php
 * Location: ./app/Controllers/Panel/Sport/Cabor/CaborController.php
 * -----------------------------------------------------------------------
 */
