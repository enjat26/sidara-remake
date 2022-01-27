<?php namespace App\Models\Sport;

use Config\Services;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class CertificationModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
  protected $table              = 'sport_certifications';
  protected $primaryKey         = 'sport_certification_id';

  protected $returnType         = 'object';

  protected $allowedFields      = ['*'];

  protected $allowedSearch      = [
                                    'sport_certification_name',
                                    'sport_certification_gender',
                                    'cabor_name',
                                    'cabors.cabor_id',
                                    'sport_certification_number',
                                    'sport_certification_category',
                                    'sport_certification_level',
                                    'sport_certification_year',
                                  ];

  protected $columnSearch        = [
                                     'cabor'      => ['cabors.cabor_id'],
                                     'category'   => ['sport_certification_category'],
                                     'year'       => ['sport_certification_year'],
                                   ];

  protected $allowedOrder        = [
                                     NULL,
                                     'sport_certification_name',
                                     'sport_certification_gender',
                                     'cabor_id',
                                     NULL,
                                     NULL,
                                     NULL,
                                     'sport_certification_year',
                                   ];

  /**
   * __construct ()
   * --------------------------------------------------------------------
   *
   * Constructor
   *
   * NOTE: Not needed if not setting values or extending a Class.
   *
   */
  private function construct()
  {
    //--------------------------------------------------------------------
    // Preload here.
    //--------------------------------------------------------------------
    // E.g.: session = Services::session();

    return (object) [
      'libIonix'      => new Ionix(),
      'dbDefault' 	  => \Config\Database::connect('default'),
      'session'       => Services::session(),
      'request' 			=> Services::request(),
      'response' 		  => Services::response(),
      'agent' 			  => Services::request()->getUserAgent(),
    ];
  }

  public function fetchData(array $where = NULL, bool $limit = false, string $order = 'DESC')
  {
    $query = $this->table($this->table)
                  ->select($this->allowedFields)
                  ->join('cabors', 'cabors.cabor_id = '.$this->table.'.cabor_id');

    if (isset($where)) {
      $query->where($where);
    }

    $this->filterData($query);

    if ($order != 'CUSTOM') {
      if(!empty($this->construct()->request->getVar('order'))) {
        $query->orderBy($this->allowedOrder[$this->construct()->request->getVar('order')['0']['column']], $this->construct()->request->getVar('order')['0']['dir']);
      } else {
        $query->orderBy($this->table.'.'.$this->primaryKey, $order);
      }
    }

    if ($limit == true && !empty($this->construct()->request->getVar('length'))) {
      if ($this->construct()->request->getVar('length') != -1) {
        return $query->get($this->construct()->request->getVar('length'), $this->construct()->request->getVar('start'));
      } else {
        return $query->get();
      }
    }

    return $query;
  }

  private function filterData($query)
  {
    if(!empty($this->construct()->request->getVar('search')['value'])) {
       $i = 0;
       foreach ($this->allowedSearch as $item)
       {
         if($this->construct()->request->getVar('search')['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('search')['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('search')['value']);
           }
           if(count($this->allowedSearch) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[3]['search']['value'])) {
       $i = 0;
       foreach ($this->columnSearch['cabor'] as $item)
       {
         if($this->construct()->request->getVar('columns')[3]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
           }
           if(count($this->columnSearch['cabor']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[4]['search']['value'])) {
       $i = 0;
       foreach ($this->columnSearch['category'] as $item)
       {
         if($this->construct()->request->getVar('columns')[4]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
           }
           if(count($this->columnSearch['category']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[6]['search']['value'])) {
       $i = 0;
       foreach ($this->columnSearch['year'] as $item)
       {
         if($this->construct()->request->getVar('columns')[6]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[6]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[6]['search']['value']);
           }
           if(count($this->columnSearch['year']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }
  }

  // -------------------------------------------------------------------

} // End of Name Model Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CertificationModel.php
 * Location: ./app/Models/Sport/CertificationModel.php
 * -----------------------------------------------------------------------
 */
