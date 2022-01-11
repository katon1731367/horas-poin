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

   <div class="row">
   <!-- Card Details -->
      <div class="col-mb-6">
         <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
               <div class="col-md-8">
                  <div class="card-body">
                     <div class="card" style="width: 18rem;">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item">
                              <h3><?= $rule['rule_name']; ?></h3>
                           </li>
                           <?php if ($rule['information']) : ?>
                              <li class="list-group-item"><?= $rule['information'] ?></li>
                           <?php endif; ?>
                           <li class="list-group-item"><small><a href="<?= base_url('/admin/productRule'); ?>">&laquo; Back To Product Rule</a></small></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Update Rule Info -->
      <div class="col-md-8">
         <div class="card-body">
            <form action="<?= base_url('admin/updateRuleProduct/' . $rule['id']) ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- rule_name -->
               <div class="form-group">
                  <label for="rule_name"> Nama Rule </label>
                  <input type="text" name="rule_name" class="form-control <?= $validation->hasError('rule_name') ? 'is-invalid' : (!old('rule_name') ? '' : 'is-valid'); ?>" value="<?= old('rule_name') ?  old('rule_name') : $rule['rule_name'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('rule_name'); ?>
                  </div>
               </div>
               <!-- keterangan -->
               <div class="form-group">
                  <label for="information"> keterangan </label>
                  <input type="text" name="information" class="form-control <?= $validation->hasError('information') ? 'is-invalid' : (!old('information') ? '' : 'is-valid'); ?>" value="<?= old('information') ?  old('information') : $rule['information'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('information'); ?>
                  </div>
               </div>
               <!-- batas_nominal -->
               <div class="form-group">
                  <label for="limit_nominal"> Batas Nominal </label>
                  <input type="number" name="limit_nominal" class="form-control <?= $validation->hasError('limit_nominal') ? 'is-invalid' : (!old('limit_nominal') ? '' : 'is-valid'); ?>" value="<?= old('limit_nominal') ?  old('limit_nominal') : $rule['limit_nominal'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('limit_nominal'); ?>
                  </div>
               </div>
               <!-- poin_batas -->
               <div class="form-group">
                  <label for="limit_points"> Batas Poin </label>
                  <input type="number" name="limit_points" class="form-control <?= $validation->hasError('limit_points') ? 'is-invalid' : (!old('limit_points') ? '' : 'is-valid'); ?>" value="<?= old('limit_points') ?  old('limit_points') : $rule['limit_points'] ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('limit_points'); ?>
                  </div>
               </div>
               <!-- double-->
               <div class="form-group">
                  <label for="double">Double</label>
                  <select type="text" name="double" class="form-control <?= $validation->hasError('double') ? 'is-invalid' : (!old('double') ? '' : 'is-valid'); ?>" id="double">
                     <!-- <option value="">1 = True | 0 = False</option> -->
                     <?php foreach ($double as $d) : ?>
                        <?= d($d == $rule['double']); ?>
                        <?php if (old('double') != null) { ?>
                           <option value="<?= $d; ?>" selected><?= $d ?></option>
                        <?php } elseif ($d == $rule['double']) { ?>
                           <option value="<?= $d; ?>" selected><?= $d ?></option>
                        <?php } else { ?>
                           <option value="<?= $d; ?>"><?= $d ?></option>
                     <?php }
                     endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('double'); ?>
                  </div>
               </div>
               <!-- minimal-->
               <div class="form-group">
                  <label for="minimal">Minimal</label>
                  <select type="text" name="minimal" class="form-control <?= $validation->hasError('minimal') ? 'is-invalid' : (!old('minimal') ? '' : 'is-valid'); ?>" id="minimal">
                     <option value="">1 = True | 0 = False</option>
                     <?php foreach ($minimal as $m) : ?>
                        <?php if (old('minimal') != null) { ?>
                           <option value="<?= $m; ?>" selected><?= $m ?></option>
                        <?php } elseif ($m == $rule['minimal']) { ?>
                           <option value="<?= $m; ?>" selected><?= $m ?></option>
                        <?php } else { ?>
                           <option value="<?= $m; ?>"><?= $m ?></option>
                     <?php }
                     endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('minimal'); ?>
                  </div>
               </div>
               <!-- batas_besar_kecil-->
               <div class="form-group">
                  <label for="limit_amount_nominal">Batas kecil Besar</label>
                  <select type="text" name="limit_amount_nominal" class="form-control <?= $validation->hasError('limit_amount_nominal') ? 'is-invalid' : (!old('limit_amount_nominal') ? '' : 'is-valid'); ?>" id="limit_amount_nominal">
                     <option value="">1 = True | 0 = False</option>
                     <?php foreach ($limit_amount_nominal as $b) : ?>
                        <?php if (old('limit_amount_nominal') != null) { ?>
                           <option value="<?= $b; ?>" selected><?= $b ?></option>
                        <?php } elseif ($b == $rule['limit_amount_nominal']) { ?>
                           <option value="<?= $b; ?>" selected><?= $b ?></option>
                        <?php } else { ?>
                           <option value="<?= $b; ?>"><?= $b ?></option>
                     <?php }
                     endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('limit_amount_nominal'); ?>
                  </div>
               </div>
               <!-- kelipatan-->
               <div class="form-group">
                  <label for="multiple">kelipatan</label>
                  <select type="text" name="multiple" class="form-control <?= $validation->hasError('multiple') ? 'is-invalid' : (!old('multiple') ? '' : 'is-valid'); ?>" id="multiple">
                     <option value="">1 = True | 0 = False</option>
                     <?php foreach ($multiple as $k) : ?>
                        <?php if (old('multiple') != null) { ?>
                           <option value="<?= $k; ?>" selected><?= $k ?></option>
                        <?php } elseif ($k == $rule['multiple']) { ?>
                           <option value="<?= $k; ?>" selected><?= $k ?></option>
                        <?php } else { ?>
                           <option value="<?= $k; ?>"><?= $k ?></option>
                     <?php }
                     endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('multiple'); ?>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary">Update</button>
            </form>
         </div>
      </div>
   </div>
</div>


<?= $this->endSection(); ?>