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
      <div class="col-md-6">
         <div class="card mb-3 overflow-hidden" style="max-width: 800px;">
            <div class="row">
               <div class="col-md-4">
                  <img src="<?= base_url('/img/user_image/' . $user->user_image); ?>" class="img-responsive ml-2 mt-4" alt="<?php $user->username ?>'s Profile Picture" style="max-width: 150px;">
               </div>
               <div class="col-md-8">
                  <div class="card-body">
                     <div class="card-body">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item">
                              <h4><?= $user->username; ?> <h4>
                                    <span class="badge badge-<?= ($user->name == 'admin') ? 'success' : 'warning'; ?>"><?= $user->name; ?></span>
                           </li>
                           <?php if ($user->fullname) : ?>
                              <li class="list-group-item"><?= $user->fullname ?></li>
                           <?php endif; ?>
                           <li class="list-group-item">
                              <h5><?= $user->email; ?></h5>
                           </li>
                           <li class="list-group-item">
                              <h5>Poin sementara : <?= $points; ?> <h4>
                           </li>
                           <?php if ($user->name == 'user') : ?>
                              <li class="list-group-item text-center">
                                 <?php if ($user->active == 1) : ?>
                                    <a href="<?= base_url('admin/makeAdmin/' . $user->userid . '/'); ?>" class="btn btn-success">Make Admin</a>
                                 <?php endif;
                              else :
                                 if ($makeUser == 1) : ?>
                                    <a href="<?= base_url('admin/makeuser/' . $user->userid . '/'); ?>" class="btn btn-success">Make User</a>
                                 <?php endif; ?>
                              </li>
                           <?php endif; ?>
                           <?php if ($user->name == 'user') : ?>
                              <li class="list-group-item text-center">
                                 <?php if ($user->active == 0) : ?>
                                    <a href="<?= base_url('admin/activate/' . $user->userid . '/'); ?>" class="btn btn-success">Activate</a>
                                 <?php else : ?>
                                    <a href="<?= base_url('admin/deactivate/' . $user->userid . '/'); ?>" class="btn btn-danger">Deactivate</a>
                                 <?php endif; ?>
                              </li>
                           <?php endif; ?>
                           </li>
                           <li class="list-group-item text-center">
                              <button class="btn btn-info" data-toggle="modal" data-target="#changePassModal"> Ganti Password</button>
                           </li>
                           <li class="list-group-item"><small><a href="<?= base_url('/admin'); ?>">&laquo; Back To User List</a></small></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Update User Info-->
      <div class="col-md-6">
         <form action="<?= base_url('admin/updateUser/' . $user->userid) ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <?= csrf_field() ?>
            <input type="hidden" name="image_user_old" value="<?= $user->user_image; ?>">
            <!-- Username -->
            <div class="form-group">
               <input type="text" name="username" class="form-control <?= $validation->hasError('username') ? 'is-invalid' : (!old('username') ? '' : 'is-valid'); ?>" value="<?= old('username') ?  old('username') : $user->username ?>" />
               <div class="invalid-feedback">
                  <?= $validation->getError('username'); ?>
               </div>
            </div>
            <!-- NIP -->
            <div class="form-group">
               <input type="text" name="nip" class="form-control <?= $validation->hasError('nip') ? 'is-invalid' : (!old('nip') ? '' : 'is-valid'); ?>" placeholder="NIP" value="<?= old('nip') ?  old('nip') : $user->nip ?>" />
               <div class="invalid-feedback">
                  <?= $validation->getError('nip'); ?>
               </div>
            </div>
            <!-- Fullname -->
            <div class="form-group">
               <input type="text" name="fullname" class="form-control <?= $validation->hasError('fullname') ? 'is-invalid' : (!old('fullname') ? '' : 'is-valid'); ?>" placeholder="Fullname" value="<?= old('fullname') ?  old('fullname') : $user->fullname ?>" />
               <div class="invalid-feedback">
                  <?= $validation->getError('fullname'); ?>
               </div>
            </div>
            <!-- Id Unit Kerja -->
            <div class="form-group">
               <select name="id_unit" class="form-control <?= $validation->hasError('id_unit') ? 'is-invalid' : (!old('id_unit') ? '' : 'is-valid'); ?>">
                  <?php foreach ($units as $uk) : ?>
                     <option value="<?= $uk['id']; ?>"><?= $uk['unit_name']; ?></option>
                  <?php endforeach; ?>
               </select>
               <div class="invalid-feedback">
                  <?= $validation->getError('id_unit'); ?>
               </div>
            </div>
            <!-- Email -->
            <div class="form-group">
               <input type="text" name="email" class="form-control <?= $validation->hasError('email') ? 'is-invalid' : (!old('email') ? '' : 'is-valid'); ?>" value="<?= old('email') ?  old('email') : $user->email ?>" placeholder="Email" />
               <div class="invalid-feedback">
                  <?= $validation->getError('email'); ?>
               </div>
            </div>
            <!-- File Upload -->
            <div class="form-group row">
               <div class="col-sm-2">
                  <img src="<?= base_url('/img/user_image/' . $user->user_image); ?>" class="img-thumbnail img-preview">
               </div>
               <div class="col-sm-10">
                  <div class="custom-file mb-2">
                     <input type="file" class="form-control custom-file-input <?= $validation->hasError('user_image') ? 'is-invalid' : (!old('user_image') ? '' : 'is-valid'); ?>" id="user_image" name="user_image" value="<?= old('user_image'); ?>" onchange="previewImg()">
                     <label class="custom-file-label overflow-hidden" for="customFile">Choose file</label>
                     <div class="invalid-feedback">
                        <?= $validation->getError('user_image'); ?>
                     </div>
                  </div>
               </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
         </form>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="changePassModalLabel">Upload Akusisi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('admin/updatePass/' . $user->userid . '/') ?>" method="post">
               <?= csrf_field() ?>
               <!-- Password -->
               <div class="form-group">
                  <label for="exampleFormControlInput1">Password</label>
                  <input type="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : (!old('password') ? '' : 'is-valid'); ?>" placeholder="Password Baru" autocomplete="off" name="password">
                  <div class="invalid-feedback">
                     <?= $validation->getError('password'); ?>
                  </div>
               </div>
               <!-- Repeat Password -->
               <div class="form-group">
                  <label for="exampleFormControlInput1">Repeat Password</label>
                  <input type="password" class="form-control <?= $validation->hasError('pass_confirm') ? 'is-invalid' : (!old('pass_confirm') ? '' : 'is-valid'); ?>" name="pass_confirm" placeholder="Ulangi Password" autocomplete="off">
                  <div class="invalid-feedback">
                     <?= $validation->getError('pass_confirm'); ?>
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