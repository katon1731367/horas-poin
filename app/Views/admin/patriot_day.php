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
   <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal" data-target="#formTambahTanggalPatriotDay">
      Tambah Tanggal Patriot Day
   </button>

   <!-- Looping list tanggal -->
   <div class="row">
      <div class="col-lg-8 table-responsive">
      <table class="table table-hover">
            <thead>
               <tr class="table-primary">
                  <th scope="col" class="align-middle" rowspan="2">No</th>
                  <th scope="col" class="align-middle" rowspan="2">id</th>
                  <th scope="col" class="align-middle" rowspan="2">tangal</th>
                  <th scope="col" class="align-middle" rowspan="2">keterangan</th>
                  <th scope="col" class="align-middle" rowspan="2">Action</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($date as $i => $t) : ?>
                  <tr>
                     <th><?= ++$i?></th>
                     <th><?= $t['id']; ?></th>
                     <th><?= $t['dates']; ?></th>
                     <th><?= $t['information']; ?></th>
                     <th>
                        <a href="<?= base_url('/admin/deletePatriotDay/' . $t['id']); ?>" class="badge badge-danger">Delete</a>
                     </th>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="formTambahTanggalPatriotDay" tabindex="-1" aria-labelledby="formTambahTanggalPatriotDayLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="formTambahTanggalPatriotDayLabel">Tambah Tanggal Patriot Day</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('admin/addPatriotDay') ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- Tanggal -->
               <div class="form-group">
                  <label for="date"> Tanggal </label>
                  <input type="date" name="date" class="form-control <?= $validation->hasError('date') ? 'is-invalid' : (!old('date') ? '' : 'is-valid'); ?>" value="<?= old('date') ?>" required/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('date'); ?>
                  </div>
               </div>
               <!-- keterangan -->
               <div class="form-group">
                  <label for="information"> keterangan </label>
                  <input type="text" name="information" class="form-control <?= $validation->hasError('information') ? 'is-invalid' : (!old('information') ? '' : 'is-valid'); ?>" value="<?= old('information') ?>" required/>
                  <div class="invalid-feedback">
                     <?= $validation->getError('information'); ?>
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