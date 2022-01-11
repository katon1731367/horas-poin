<?php namespace App\Models;

use CodeIgniter\Model;

class ProductRulesModel extends Model
{
    protected $table      = 'product_rules';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['rule_name', 'information', 'limit_points', 'limit_nominal', 'double', 'limit_amount_nominal', 'multiple', 'minimal'];

    protected $skipValidation = false;

    public function deleteRuleProduct($id)
    {
      $this->builder($this->table)->delete(['id' => $id]);
    }
}