<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
   
    <!-- Flash Massage -->
   <?php if (session()->getFlashdata('pesan')) { ?>
      <div class="alert alert-success" role="alert">
         <?= session()->getflashdata('pesan'); ?>
      </div>
   <?php } else if (session()->getFlashdata('gagal')) { ?>
      <div class="alert alert-danger" role="alert">
         <?= session()->getflashdata('gagal'); ?>
      </div>
   <?php } ?>

   <div class="row">
   <!-- User Card Details -->
      <div class="col-mb-6">
         <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
               <div class="col-md-8">
                  <div class="card-body">
                     <div class="card" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item">
                              <h3><?= $unit['unit_name']; ?></h3>
                           </li>
                           <li class="list-group-item"><small><a href="<?= base_url('/admin/units'); ?>">&laquo; Back To Units List</a></small></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!--Update Project Info-->
      <div class="col-md-8">
         <div class="card-body">
            <form action="<?= base_url('admin/updateUnit/' . $unit['id']) ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- nama project -->
               <div class="form-group">
                  <label for="id"> ID Unit Cabang </label>
                  <input type="text" name="id" class="form-control <?= $validation->hasError('id') ? 'is-invalid' : (!old('id') ? '' : 'is-valid'); ?>" value="<?= old('id') ?  old('id') : $unit['id'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('id'); ?>
                  </div>
               </div>
               <!-- nama project -->
               <div class="form-group">
                  <label for="unit_name"> Nama Unit Cabang </label>
                  <input type="text" name="unit_name" class="form-control <?= $validation->hasError('unit_name') ? 'is-invalid' : (!old('unit_name') ? '' : 'is-valid'); ?>" value="<?= old('unit_name') ?  old('unit_name') : $unit['unit_name'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('unit_name'); ?>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Update</button>
            </form>
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>