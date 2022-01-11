<?= $this->extend('auth/templates/index'); ?>

<?= $this->section('content'); ?>
<div class="container">
   <!-- Outer Row -->
   <div class="row justify-content-center">

      <div class="col-md-10">
         <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
               <!-- Nested Row within Card Body -->
               <div class="row">
                  <div class="col-lg">
                     <div class="p-5">
                        <div class="text-center">
                           <h1 class="h4 text-gray-900 mb-4"><?= lang('Auth.register') ?></h1>
                        </div>
                        <?= view('Myth\Auth\Views\_message_block') ?>
                        <form action="<?= route_to('register') ?>" method="post" class="user" autocomplete="off">
                           <?= csrf_field() ?>
                           <div class="form-group">
                              <input type="text" class="form-control <?= session('errors.nip') ? 'is-invalid' : (!old('nip') ? '' : 'is-valid'); ?>" name="nip" placeholder="NIP" value="<?= old('nip'); ?>" required>
                           </div>
                           <div class="form-group">
                               <select name="id_unit" class="form-control <?= session('errors.id_unit') ? 'is-invalid' : (!old('id_unit') ? '' : 'is-valid'); ?>" required>
                                  <option value="">ID Cabang</option>
                                  <?php foreach ($units as $uk) : ?>
                                    <?php if (old('id_unit_kerja') == $uk['id']) { ?>
                                     <option value="<?= $uk['id']; ?>" selected><?= $uk['unit_name']; ?></option>
                                    <?php } else { ?>
                                     <option value="<?= $uk['id']; ?>"><?= $uk['unit_name']; ?></option>
                                  <?php } endforeach; ?>
                               </select>
                           </div>
                           <div class="form-group">
                              <input type="text" name="username" class="form-control <?= session('errors.username') ? 'is-invalid' : (!old('username') ? '' : 'is-valid'); ?>" value="<?= old('username'); ?>" placeholder="Username" required>
                           </div>
                           <div class="form-group">
                              <input type="email" name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : (!old('email') ? '' : 'is-valid'); ?>" value="<?= old('email'); ?>" placeholder="Email" required>
                              <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                           </div>
                           <div class="form-group row">
                              <div class="col-sm-6 mb-3 mb-sm-0">
                                 <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" name="pass_confirm" placeholder="<?= lang('Auth.password') ?>" autocomplete="off" name="password" required>
                              </div>
                              <div class="col-sm-6">
                                 <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off" required>
                              </div>
                           </div>
                           <button type="submit" class="btn btn-primary btn-user btn-block">
                              <?= lang('Auth.register'); ?>
                           </button>
                        </form>
                        <hr>
                        <?php if ($config->activeResetter) : ?>
                           <div class="text-center">
                              <a class="small" href="<?= route_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a>
                           </div>
                        <?php endif; ?>
                        <div class="text-center">
                           <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= route_to('login') ?>"><?= lang('Auth.signIn') ?></a></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
   <?= $this->endSection(); ?>