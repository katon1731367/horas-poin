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
                              <h3><?= $project['project_name']; ?></h3>
                           </li>
                           <?php if ($project['information']) : ?>
                              <li class="list-group-item">
                                 <h6>Keterangan</h6>
                                 <p><?= $project['information'] ?></p>
                              </li>
                           <?php endif; ?>
                           <li class="list-group-item"><small><a href="<?= base_url('/admin/projectList'); ?>">&laquo; Back To project List</a></small></li>
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
            <form action="<?= base_url('admin/updateProject/' . $project['id']) ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- nama project -->
               <div class="form-group">
                  <label for="poin"> Nama project </label>
                  <input type="text" name="project_name" class="form-control <?= $validation->hasError('project_name') ? 'is-invalid' : (!old('project_name') ? '' : 'is-valid'); ?>" value="<?= old('project_name') ?  old('project_name') : $project['project_name'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('project_name'); ?>
                  </div>
               </div>
               <!-- keterangan -->
               <div class="form-group">
                  <label for="poin"> keterangan </label>
                  <input type="text" name="information" class="form-control <?= $validation->hasError('information') ? 'is-invalid' : (!old('information') ? '' : 'is-valid'); ?>" value="<?= old('information') ?  old('information') : $project['information'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('information'); ?>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Update</button>
            </form>
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>