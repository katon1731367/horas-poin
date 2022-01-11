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
   <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal" data-target="#addUnitsModal">
      Tambah Unit Cabang
   </button>

   <!-- Looping User List -->
   <div class="row">
      <div class="col-lg-8 table-responsive">
         <table class="table table-hover">
            <thead>
                <tr class="table-primary">
               <th scope="col">NO</th>
               <th scope="col">id</th>
               <th scope="col">Nama Unit Cabang</th>
               <th scope="col">Action</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($units as $i => $u) : ?>
                  <tr>
                     <th><?= ++$i; ?></th>
                     <th><?= $u['id']; ?></th>
                     <th><?= $u['unit_name']; ?></th>
                     <th>
                        <a href="<?= base_url('/admin/detailUnit/' . $u['id']); ?>" class="badge badge-info">Detail</a>
                        <a href="<?= base_url('/admin/deleteUnit/' . $u['id']); ?>" class="badge badge-danger">Delete</a>
                     </th>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUnitsModal" tabindex="-1" aria-labelledby="addUnitsModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id=addUnitsModalLabel">Tambah Project</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('admin/addUnit') ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <div class="form-group">
                  <label for="id_unit"> ID Unit cabang </label>
                  <input type="text" name="id" class="form-control <?= $validation->hasError('id') ? 'is-invalid' : (!old('id') ? '' : 'is-valid'); ?>" value="<?= old('id') ?>" required/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('id'); ?>
                  </div>
               </div>
               <!-- nama project -->
               <div class="form-group">
                  <label for="unit_name"> Nama Unit Cabang </label>
                  <input type="text" name="unit_name" class="form-control <?= $validation->hasError('unit_name') ? 'is-invalid' : (!old('unit_name') ? '' : 'is-valid'); ?>" value="<?= old('unit_name') ?>" required/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('unit_name'); ?>
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