<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

   <?php if (session()->getFlashdata('pesan')) { ?>
      <div class="alert alert-success" role="alert">
         <?= session()->getflashdata('pesan'); ?>
      </div>
   <?php } else if (session()->getFlashdata('gagal')) { ?>
      <div class="alert alert-danger" role="alert">
         <?= session()->getflashdata('gagal'); ?>
      </div>
   <?php } ?>

   <!-- User Card Details -->
   <div class="row">
      <div class="col-mb-6">
         <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
               <div class="col-md-8">
                  <div class="card-body">
                     <div class="card" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item">
                              <h3><?= $product['product_name']; ?></h3>
                           </li>
                           <?php if ($product['information']) : ?>
                              <li class="list-group-item"><?= $product['information'] ?></li>
                           <?php endif; ?>
                           <li class="list-group-item">
                              <h4>rules Product</h4>
                              <?php if ($productRules) :
                                 foreach ($productRules as $p) : ?>
                                    <span class="badge badge-info">
                                       <?= $p['rule_name']; ?>
                                       <a href="<?= base_url('admin/deleteRule/' . $p['id_product'] . '/' . $p['id_rule']) ?>" class="badge badge-danger" style="cursor: pointer;">X</a>
                                    </span>
                                 <?php endforeach ?>
                              <?php endif; ?>
                           </li>
                           <li class="list-group-item">Poin Produk : <b><?= $product['points']; ?></b></li>
                           <li class="list-group-item text-center">
                           <button class="btn btn-info" data-toggle="modal" data-target="#modalRulesProduct"> Tambah Aturan Product</button>
                           </li>
                           <li class="list-group-item"><small><a href="<?= base_url('/admin/productList'); ?>">&laquo; Back To Product List</a></small></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-8">
         <div class="card-body">
            <form action="<?= base_url('admin/updateProduct/' . $product['id']) ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- nama product -->
               <div class="form-group">
               <label for="poin"> Nama Product </label>
                  <input type="text" name="product_name" class="form-control <?= $validation->hasError('product_name') ? 'is-invalid' : (!old('product_name') ? '' : 'is-valid'); ?>" value="<?= old('product_name') ?  old('product_name') : $product['product_name'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('product_name'); ?>
                  </div>
               </div>
               <!-- information -->
               <div class="form-group">
               <label for="poin"> information </label>
                  <input type="text" name="information" class="form-control <?= $validation->hasError('information') ? 'is-invalid' : (!old('information') ? '' : 'is-valid'); ?>" value="<?= old('information') ?  old('information') : $product['information'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('information'); ?>
                  </div>
               </div>
               <!-- poin -->
               <div class="form-group">
               <label for="points"> Poin </label>
                  <input type="number" name="points" class="form-control <?= $validation->hasError('points') ? 'is-invalid' : (!old('points') ? '' : 'is-valid'); ?>" value="<?= old('points') ?  old('points') : $product['points'] ?>"/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('points'); ?>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Update</button>
            </form>
         </div>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalRulesProduct" tabindex="-1" aria-labelledby="modalRulesProductLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="modalRulesProductLabel">Buat Aturan Poin</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('admin/addRule'); ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <input type="text" name="id_product" value="<?= $product['id']; ?>" hidden>
               <div class="form-group">
                  <select name="id_rule" class="form-control <?= $validation->hasError('rule_name') ? 'is-invalid' : (!old('rule_name') ? '' : 'is-valid'); ?>">
                     <?php foreach ($rules as $r) : ?>
                        <option value="<?= $r['id']; ?>"><?= $r['rule_name']; ?></option>
                     <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('rule_name'); ?>
                  </div>
               </div>
               <form class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
               </form>
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>