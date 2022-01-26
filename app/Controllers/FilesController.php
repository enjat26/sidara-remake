<?php namespace App\Controllers;

use App\Models\FileModel;

/**
 * Class FilesController
 *
 * @package App\Controllers
 */
class FilesController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
	protected $allowedFileViewerExtension = ['pdf', 'jpg', 'jpeg', 'png'];
	
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

	public function download()
	{
		$token = (object) [
			'fileData'	=> $this->libIonix->validateFileToken($this->uri->getSegment(3)),
		];

		if ($token->fileData != false) {
			$output = (object) [
				'source'		=> $this->configIonix->uploadsFolder[$token->fileData->file_type].$token->fileData->file_source,
				'name'			=> $token->fileData->file_name.'.'.$token->fileData->file_extension,
				'update'		=> $this->libIonix->updateQuery('files', ['file_id' => $token->fileData->file_id], ['file_download_attempt' => $token->fileData->file_download_attempt+1]),
			];

			if (file_exists($output->source)) {
				return $this->response->download($output->source, null)->setFileName($output->name);
			}
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	public function viewer()
	{
		$token = (object) [
			'fileData'	=> $this->libIonix->validateFileToken($this->uri->getSegment(3)),
		];

		if ($token->fileData != false) {
			$output = (object) [
				'source'		=> $this->configIonix->uploadsFolder[$token->fileData->file_type].$token->fileData->file_source,
				'name'			=> $token->fileData->file_name.'.'.$token->fileData->file_extension,
			];

			if (file_exists($output->source)) {
				header('Content-Length: '.filesize($output->source));
	      header('Content-Type: '.mime_content_type($output->source));
				if (!in_array($token->fileData->file_extension, $this->allowedFileViewerExtension)) {
					header('Content-Disposition: attachment; filename="'.$output->name.'"');
				}

				readfile($output->source);
	      die();
	      exit;
			}
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	public function image()
	{
		$output = (object) [
			'source'		=> $this->configIonix->uploadsFolder[$this->uri->getSegment(2)].$this->uri->getSegment(3),
		];

    if (file_exists($output->source)) {
			header('Content-Length: '.filesize($output->source));
      header('Content-Type: '.mime_content_type($output->source));

			readfile($output->source);
      die();
      exit;
    }

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	public function content()
	{
		$output = (object) [
			'source'	=> $this->configIonix->uploadsFolder[$this->uri->getSegment(2)].$this->libIonix->Decode($this->uri->getSegment(3)).'/'.$this->libIonix->Decode($this->uri->getSegment(4)),
		];

		if (file_exists($output->source)) {
			header('Content-Length: '.filesize($output->source));
      header('Content-Type: '.mime_content_type($output->source));

      readfile($output->source);
      die();
      exit;
    }

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: FilesController.php
 * Location: ./app/Controllers/FilesController.php
 * -----------------------------------------------------------------------
 */
