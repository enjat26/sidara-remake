<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\FileModel;

/**
 * Class FileController
 *
 * @package App\Controllers
 */
class FileController extends BaseController
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
		$this->modFile 		= New FileModel();
	}

	public function index()
	{
		$data = [
			'modFile'			=> $this->modFile,
			'resultFile'	=> $this->modFile->fetchData()->paginate(9, 'files'),
			'pageFile'		=> $this->modFile->fetchData()->pager,
		];

		return view('panels/files', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'file') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modFile->fetchData(['file_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'file') {
			if ($this->libIonix->Decode($this->request->getGet('id')) == 'add') {
				return $this->addFile();
			} else {
				return $this->updateFile($this->modFile->fetchData(['file_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addFile() {
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> $this->request->getPost('type'),
			'file_name' 					=> !empty($this->request->getPost('name')) ? ucwords($this->request->getPost('name')) : current(explode('.', $this->request->getFile('file')->getClientName())),
			'file_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
			'file_source'					=> $this->request->getFile('file')->getRandomName(),
			'file_size'						=> $this->request->getFile('file')->getSize('b'),
			'file_extension' 			=> $this->request->getFile('file')->getClientExtension(),
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder[$request['file_type']],
			'fileName'	=> $this->request->getFile('file')->isValid() ? $request['file_source'] : NULL,
		];

		$output = [
			'type'			=> 'success',
			'upload'		=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->move($config['directory'], $config['fileName'], true) : NULL,
			'insert'		=> $this->libIonix->insertQuery('files', $request),
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '201 Created',
												'message'	=> 'Berhasil mengunggah <strong>'.$request['file_name'].'.'.$request['file_extension'].'</strong> sebagai <strong>Berkas</strong> baru',
										 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	private function updateFile(object $fileData) {
		$request = [
			'file_name' 					=> !empty($this->request->getPost('name')) ? ucwords($this->request->getPost('name')) : $fileData->file_name,
			'file_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		$output = [
			'type'			=> 'success',
			'update'		=> $this->libIonix->updateQuery('files', ['file_id' => $fileData->file_id], $request),
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '202 Accepted',
												'message'	=> 'Berhasil merubah informasi <strong>Berkas</strong> tersebut',
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'file') {
			return $this->updateStatus($this->modFile->fetchData(['file_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function updateStatus(object $fileData)
	{
		$output = [
			'update'		=> $this->libIonix->updateQuery('files', ['file_id' => $fileData->file_id], ['file_download_attempt' => $fileData->file_download_attempt+1]),
		];
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'file') {
			return $this->deleteFile($this->modFile->fetchData(['file_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteFile(object $fileData)
	{
		if (file_exists($this->configIonix->uploadsFolder[$fileData->file_type].$fileData->file_source)) {
			unlink($this->configIonix->uploadsFolder[$fileData->file_type].$fileData->file_source);
		}

		$output = [
			'delete'	=> $this->libIonix->deleteQuery('files', ['file_id' => $fileData->file_id]),
			'flash'   => $this->session->setFlashdata('alertSwal', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Berkas</strong> yang dipilih',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: FileController.php
 * Location: ./app/Controllers/Panel/FileController.php
 * -----------------------------------------------------------------------
 */
