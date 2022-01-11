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
                  <div class="col-lg-12">
                     <div class="p-5">
                        <div class="text-center mb-3">
                           <img src="<?= base_url('/img/horas_poin.svg') ?>" alt="horas poin" class="img-fluid">
                        </div>
                        <?= view('Myth\Auth\Views\_message_block') ?>
                        <form action="<?= route_to('login') ?>" method="post" class="user" autocomplete="off">
                           <?= csrf_field() ?>
                           <?php if ($config->validFields === ['email']) : ?>
                              <div class="form-group">
                                 <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.email') ?>">
                                 <div class="invalid-feedback" autocomplate="off">
                                    <?= session('errors.login') ?>
                                 </div>
                              </div>
                           <?php else : ?>
                              <div class="form-group">
                                 <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>">
                                 <div class="invalid-feedback" autocomplate="off">
                                    <?= session('errors.login') ?>
                                 </div>
                              </div>
                           <?php endif; ?>

                           <div class="input-group mb-3">
                              <input type="password" name="password" id="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                              <div class="input-group-append">
                                 <span class="input-group-text" id="basic-addon2" onclick="togglePw()"><i class="fa fa-eye-slash" id="icon"></i></span>
                              </div>
                              <div class="invalid-feedback">
                                 <?= session('errors.password') ?>
                              </div>
                           </div>

                           <?php if ($config->allowRemembering) : ?>
                              <div class="form-group form-check" style="margin-top: 0 !important">
                                 <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="form-check-input" name="remembering" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                    <label class="form-check-label" for="remembering"><?= lang('Auth.rememberMe') ?></label>
                                 </div>
                              </div>
                           <?php endif; ?>

                           <button type="submit" class="btn btn-primary btn-user btn-block">
                              <?= lang('Auth.loginAction') ?>
                           </button>
                        </form>
                        <hr>
                        <?php if ($config->allowRegistration) : ?>
                           <div class="text-center">
                              <a class="small" href="<?= route_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a>
                           </div>
                        <?php endif; ?>
                        <?php if ($config->activeResetter) : ?>
                           <div class="text-center">
                              <a class="small" href="<?= route_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   function togglePw() {
      var icon = document.getElementById("icon");
      var x = document.getElementById("password");
      if (x.type === "password") {
         x.type = "text";
         icon.classList.toggle("fa");
         icon.classList.toggle("fa-eye");
         icon.classList.add("fa");
         icon.classList.toggle("fa-eye-slash");
      } else {
         x.type = "password";
         icon.classList.toggle("fa");
         icon.classList.toggle("fa-eye-slash");
         icon.classList.add("fa");
         icon.classList.toggle("fa-eye");
      }
   }
</script>
<?= $this->endSection(); ?>