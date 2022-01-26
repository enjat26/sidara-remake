<?php namespace App\Models\Youth;

use Config\Services;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class AssetModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
  protected $table              = 'youth_assets';
  protected $primaryKey         = 'asset_id';

  protected $returnType         = 'object';

  protected $allowedFields      = ['*'];

  protected $allowedSearch      = [
                                    'asset_name',
                                    'asset_type',
                                    'asset_category_name',
                                    'asset_production_year',
                                    'asset_condition',
                                  ];

  protected $columnSearch       = [
                                    'type'    => ['asset_category_type'],
                                  ];
  protected $allowedOrder        = [
                                     NULL,
                                     'asset_name',
                                     'asset_type',
                                     'asset_category_name',
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
                  ->join('youth_asset_categorys', 'youth_asset_categorys.asset_category_id = '.$this->table.'.asset_category_id')
                  ->join('districts', 'districts.district_id = '.$this->table.'.district_id')
                  ->join('provinces', 'provinces.province_id = districts.province_id');

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

    if (!empty($this->construct()->request->getVar('columns')[3]['search']['value'])) {
      $i = 0;
      foreach ($this->columnSearch['type'] as $item) {
        if ($this->construct()->request->getVar('columns')[3]['search']['value']) {
          if ($i === 0) {
            $query->groupStart();
            $query->like($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
          } else {
            $query->orLike($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
          }
          if (count($this->columnSearch['type']) - 1 == $i)
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
 * Filename: AssetModel.php
 * Location: ./app/Models/Youth/AssetModel.php
 * -----------------------------------------------------------------------
 */
