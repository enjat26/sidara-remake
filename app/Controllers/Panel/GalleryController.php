<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\GalleryModel;

/**
 * Class GalleryController
 *
 * @package App\Controllers
 */
class GalleryController extends BaseController
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
		$this->modGallery 		= New GalleryModel();
	}

	public function index()
	{
		$data = [
			'modGallery'			=> $this->modGallery,
			'resultGallery'		=> $this->modGallery->fetchData()->paginate(12, 'gallerys'),
			'pageGallery'			=> $this->modGallery->fetchData()->pager,
		];

		return view('panels/gallerys', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addImage();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'video') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addVideo();
			}
		}
	}

	private function addImage()
	{
		$request = [
			'user_id'					=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'gallery_title'		=> ucwords($this->request->getPost('title')),
			'gallery_tags'		=> !empty($this->request->getPost('tags')) ? implode(', ', array_column(json_decode($this->request->getPost('tags')), 'value')) : NULL,
			'gallery_type'		=> 'image',
			'gallery_link'		=> 'local',
			'gallery_source'	=> $this->request->getFile('image')->getRandomName(),
		];

		$query = [
			'insert'		=> $this->libIonix->insertQuery('gallerys', $request),
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder['gallery'].$query['insert'],
			'fileName'	=> $this->request->getFile('image')->isValid() ? $request['gallery_source'] : NULL,
		];

		$output = [
			'create'		=> !is_dir($config['directory']) ? mkdir($config['directory'], 0777, true) : NULL,
			'upload'		=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config['directory'], $config['fileName'], true) : NULL,
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '201 Created',
												'message'	=> 'Berhasil mengunggah <strong>'.$request['gallery_title'].'</strong> sebagai <strong>Foto/Gambar</strong> baru',
										 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	private function addVideo()
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'gallery_title'				=> ucwords($this->request->getPost('title')),
			'gallery_tags'				=> !empty($this->request->getPost('tags')) ? implode(', ', array_column(json_decode($this->request->getPost('tags')), 'value')) : NULL,
			'gallery_type'				=> 'video',
			'gallery_link'				=> $this->request->getFile('video')->isValid() ? 'local' : 'youtube',
			'gallery_thumbnails'	=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->getRandomName() : NULL,
			'gallery_source'			=> $this->request->getFile('video')->isValid() ? $this->request->getFile('video')->getRandomName() : 'https://www.youtube.com/embed/'.$this->request->getPost('url').'?feature=oembed',
		];

		$query = [
			'insert'			=> $this->libIonix->insertQuery('gallerys', $request),
		];

		$config = [
			'directory'		=> $this->configIonix->uploadsFolder['gallery'].$query['insert_id'],
			'imageName'		=> $this->request->getFile('image')->isValid() ? $request['gallery_thumbnails'] : NULL,
			'videoName'		=> $this->request->getFile('video')->isValid() ? $request['gallery_source'] : NULL,
		];

		$output = [
			'create'			=> !is_dir($config['directory']) ? mkdir($config['directory'], 0777, true) : NULL,
			'uploadImage'	=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config['directory'], $config['imageName'], true) : NULL,
			'uploadVideo'	=> $this->request->getFile('video')->isValid() ? $this->request->getFile('video')->move($config['directory'], $config['videoName'], true) : NULL,
			'flash'   		=> $this->session->setFlashdata('alertToastr', [
												 'type'			=> 'success',
												 'header'		=> '201 Created',
												 'message'	=> 'Berhasil mengunggah <strong>'.$request['gallery_title'].'</strong> sebagai <strong>Vidio</strong> baru',
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'gallery') {
			return $this->updateStatus($this->modGallery->fetchData(['gallery_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function updateStatus(object $galleryData)
	{
		$output = [
			'update'		=> $this->libIonix->updateQuery('gallerys', ['gallery_id' => $galleryData->gallery_id], ['gallery_views' => $fileData->gallery_views+1]),
		];
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'gallery') {
			return $this->deleteGallery($this->modGallery->fetchData(['gallery_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteGallery(object $galleryData)
	{
		if (is_dir($this->configIonix->uploadsFolder['gallery'].$galleryData->gallery_id)) {
			delete_files($this->configIonix->uploadsFolder['gallery'].$galleryData->gallery_id, TRUE);
			rmdir($this->configIonix->uploadsFolder['gallery'].$galleryData->gallery_id);
		}

		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('gallerys', ['gallery_id' => $galleryData->gallery_id]),
			'flash'   => $this->session->setFlashdata('alertSwal', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Media</strong> yang dipilih',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Galleryname: GalleryController.php
 * Location: ./app/Controllers/Panel/GalleryController.php
 * -----------------------------------------------------------------------
 */
