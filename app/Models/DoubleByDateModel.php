<?php

namespace App\Models;

use CodeIgniter\Model;

class DoubleByDateModel extends Model
{
  protected $table = 'double_by_date';
  protected $primaryKey = 'id';
  protected $returnType = 'array';
  protected $allowedFields = ['dates', 'information'];

  public function getAllDate() {
    return $this->builder($this->table)->select('dates')->get()->getResult('array');
  }

  public function deleteDate($id)
  {
    $this->builder($this->table)->delete(['id' => $id]);
  }
}
