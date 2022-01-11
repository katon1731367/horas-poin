<?php namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['product_name', 'information', 'points'];
    protected $skipValidation = false;

    public function productNameId($id){
        $this->builder($this->table)->where('id', $id)->get()->getResult();
    }

    public function UpdateRule($idProduct, $idRule){
        $this->builder($this->table)->where([
            'id_product' => $idProduct,
            'id_rule' => $idRule
          ])->update([
            'id_rule' => $idRule,
          ]);
    }

    public function addRule($idProduct, $idRule){
        $data = [
            'id_rule' => $idRule,
            'id_product' => $idProduct
        ];
        
        $this->builder('product_groups_rules')->insert($data);
    }

    public function deleteRule($idProduct, $idRule){
        $data = [
            'id_rule' => $idRule,
            'id_product' => $idProduct
        ];
        
        $this->builder('product_groups_rules')->delete($data);
    }

    public function getProductRules($id){
        return $this->builder($this->table)->select('pr.*, pgr.id_product, pgr.id_rule')->join('product_groups_rules as pgr', 'pgr.id_product = product.id')->join('product_rules as pr', 'pr.id =  pgr.id_rule')->where('product.id', $id)->get()->getResult('array');
    }

    public function deleteProduct($id)
    {
      $this->builder($this->table)->delete(['id' => $id]);
    }
}