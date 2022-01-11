<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitsModel extends Model
{
    protected $table      = 'units';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id', 'unit_name'];
    protected $skipValidation = false;

    public function deleteUnit($id)
    {
      $this->builder($this->table)->delete(['id' => $id]);
    }
}
