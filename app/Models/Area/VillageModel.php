<?php namespace App\Models\Area;

use Config\Services;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class VillageModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
  protected $table              = 'villages';
  protected $primaryKey         = 'village_id';

  protected $allowedFields      = ['*'];

  protected $allowedSearch      = [
                                    'village_type',
                                    'village_name',
                                    'sub_district_name',
                                    'district_name',
                                    'province_name',
                                    'country_name',
                                  ];

  protected $columnSearch        = [
                                     'subdistrict'    => ['sub_districts.sub_district_id'],
                                     'district'       => ['districts.district_id'],
                                     'province'       => ['provinces.province_id'],
                                     'country'        => ['countrys.country_id'],
                                   ];

  protected $allowedOrder         = [
                                      NULL,
                                      'village_type',
                                      'village_name',
                                      'sub_district_name',
                                      'district_name',
                                      'province_name',
                                      'country_name',
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
                  ->join('sub_districts', 'sub_districts.sub_district_id = '.$this->table.'.sub_district_id')
                  ->join('districts', 'districts.district_id = sub_districts.district_id')
                  ->join('provinces', 'provinces.province_id = districts.province_id')
                  ->join('countrys', 'countrys.country_id = provinces.country_id');

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

    if(!empty($this->construct()->request->getVar('columns')[2]['search']['value']))
    {
       $i = 0;
       foreach ($this->columnSearch['subdistrict'] as $item)
       {
         if($this->construct()->request->getVar('columns')[2]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[2]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[2]['search']['value']);
           }
           if(count($this->columnSearch['subdistrict']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[3]['search']['value']))
    {
       $i = 0;
       foreach ($this->columnSearch['district'] as $item)
       {
         if($this->construct()->request->getVar('columns')[3]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
           }
           if(count($this->columnSearch['district']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[4]['search']['value']))
    {
       $i = 0;
       foreach ($this->columnSearch['province'] as $item)
       {
         if($this->construct()->request->getVar('columns')[4]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
           }
           if(count($this->columnSearch['province']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[5]['search']['value']))
    {
       $i = 0;
       foreach ($this->columnSearch['country'] as $item)
       {
         if($this->construct()->request->getVar('columns')[5]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[5]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[5]['search']['value']);
           }
           if(count($this->columnSearch['country']) - 1 == $i)
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
 * Filename: Area/VillageModel.php
 * Location: ./app/Models/Area/VillageModel.php
 * -----------------------------------------------------------------------
 */
