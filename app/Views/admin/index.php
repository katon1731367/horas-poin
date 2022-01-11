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

   <div class="card">
      <div class="card-header">
         <!-- Button trigger modal -->
         <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#formTambahUser">
            Tambah User
         </button>
         <a href="<?= base_url('admin/exportPoinUser'); ?>" type="button" class="btn btn-info mb-2">
            Export Poin User
         </a>
         <div class="row">
            <div class="col-md-10">
               <form action="" method="POST">
                  <div class="input-group is-invalid">
                     <div class="custom-file">
                        <input type="text" class="form-control" placeholder="<?= $keyword; ?>" name="keyword" autocomplete="off">
                     </div>
                     <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" name="submit">cari user</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="card-body">
   <!-- Looping User List -->
   <div class="row">
      <div class="col-lg-12 table-responsive">
         <table class="table table-hover">
            <thead>
               <tr class="table-primary">
                  <th scope="col">#</th>
                  <th scope="col">NIP</th>
                  <th scope="col">Unit Kerja</th>
                  <th scope="col">Username</th>
                  <th scope="col">Email</th>
                  <th scope="col">Role</th>
                  <th scope="col">Action</th>
               </tr>
            </thead>
            <tbody>
               <?php $i = 1 + (50 * ($currentPage - 1));
               foreach ($users as $user) : ?>
                  <tr>
                     <th scope="col"><?= $i++; ?></th>
                     <th><?= $user['nip']; ?></th>
                     <th><?= $user['id_unit']; ?></th>
                     <th><?= $user['username']; ?></th>
                     <th><?= $user['email']; ?></th>
                     <th>
                        <?= $user['name']; ?>
                        <?php if ($user['active'] == 1) { ?>
                           <span class="badge badge-success">Active</span>
                        <?php } else { ?>
                           <span class="badge badge-danger">Deactivate</span>
                        <?php } ?>
                     </th>
                     <th style="cursor: pointer;">
                        <a href="<?= base_url('admin/' . $user['user_id']); ?>" class="badge badge-info">Detail</a>
                        <a href="<?= base_url('admin/deleteUser/' . $user['user_id']); ?>" class="badge badge-danger" onclick="return confirm('Yakin hapus user <?= $user['username'] ?> ?')">Hapus</a>
                     </th>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
   <div class="row">
      <div class="col text-center">
         <?= $pager->links('users', 'pagination') ?>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="formTambahUser" tabindex="-1" aria-labelledby="formTambahUserLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="formTambahUserLabel">Tambah User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('/register/') ?>" method="post" class="user" autocomplete="off">
               <?= csrf_field() ?>
               <div class="form-group">
                  <input type="text" class="form-control <?php if (session('errors.nip')) : ?>is-invalid<?php endif ?>" name="nip" placeholder="NIP" value="<?= old('nip'); ?>" required> 
                  <small id="NIPhelp" class="form-text text-muted">Password must exact 9 characters</small>
               </div>
               <div class="form-group">
                  <select name="id_unit" class="form-control <?= session('errors.id_unit') ? 'is-invalid' : (!old('id_unit') ? '' : 'is-valid'); ?>" required>
                     <option value="">ID Cabang</option>
                     <?php foreach ($units as $uk) : ?>
                        <?php if (old('id_unit') == $uk['id']) { ?>
                           <option value="<?= $uk['id']; ?>" selected><?= $uk['unit_name']; ?></option>
                        <?php } else { ?>
                           <option value="<?= $uk['id']; ?>"><?= $uk['unit_name']; ?></option>
                     <?php }
                     endforeach; ?>
                  </select>
               </div>
               <div class="form-group">
                  <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username'); ?>" required>
               </div>
               <div class="form-group">
                  <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email'); ?>" required>
                  <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
               </div>
               <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                     <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off" name="password" required>
                  </div>
                  <div class="col-sm-6">
                     <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off" required>
                  </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-user">
               <?= lang('Auth.register'); ?>
            </button>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection(); ?>