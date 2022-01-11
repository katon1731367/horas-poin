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

   <!-- Button trigger modal -->
   <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal" data-target="#addProductModal">
      Tambah Product
   </button>

   <!-- Looping product List -->
   <div class="row">
      <div class="col-lg-8 table-responsive">
      <table class="table table-hover">
            <thead>
                <tr class="table-primary">
               <th scope="col">id</th>
               <th scope="col">Nama Product</th>
               <th scope="col">Keterangan</th>
               <th scope="col">Poin</th>
               <th scope="col">Action</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($product as $i => $p) : ?>
                  <tr>
                     <th><?= $p['id']; ?></th>
                     <th><?= $p['product_name']; ?></th>
                     <th><?= $p['information']; ?></th>
                     <th><?= $p['points']; ?></th>
                     <th>
                        <a href="<?= base_url('/admin/detailProduct/' . $p['id']); ?>" class="badge badge-info">Detail</a>
                        <a href="<?= base_url('/admin/deleteProduct/' . $p['id']); ?>" class="badge badge-danger">Delete</a>
                     </th>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Tambah Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('admin/addProduct') ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- nama_product -->
               <div class="form-group">
                  <label for="product_name"> Nama Product </label>
                  <input type="text" name="product_name" class="form-control <?= $validation->hasError('product_name') ? 'is-invalid' : (!old('product_name') ? '' : 'is-valid'); ?>" value="<?=old('product_name')?>" required/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('product_name'); ?>
                  </div>
               </div>
               <!-- keterangan -->
               <div class="form-group">
                  <label for="information"> keterangan </label>
                  <input type="text" name="information" class="form-control <?= $validation->hasError('information') ? 'is-invalid' : (!old('information') ? '' : 'is-valid'); ?>" value="<?= old('information')?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('information'); ?>
                  </div>
               </div>
               <!-- poin -->
               <div class="form-group">
                  <label for="points"> poin </label>
                  <input type="number" name="points" class="form-control <?= $validation->hasError('points') ? 'is-invalid' : (!old('points') ? '' : 'is-valid'); ?>" value="<?=old('points')?>" required/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('points'); ?>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
         </div>
         </form>
      </div>
   </div>
</div>

<?= $this->endSection(); ?>