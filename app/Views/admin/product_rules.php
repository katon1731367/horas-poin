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
   <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal" data-target="#exampleModal">
      Tambah Rule Product
   </button>

   <!-- Looping product List -->
   <div class="row">
      <div class="col-lg table-responsive">
      <table class="table table-hover">
            <thead>
               <tr class="table-primary">
                  <th scope="col" class="align-middle" rowspan="2">id</th>
                  <th scope="col" class="align-middle" rowspan="2" >Nama</th>
                  <th scope="col" class="align-middle" rowspan="2" >keterangan</th>
                  <th scope="col" class="align-middle" rowspan="2" >Batas Nominal</th>
                  <th scope="col" class="align-middle" rowspan="2" >Poin Tambahan</th>
                  <th scope="col" class="text-center" colspan="4">Rules</th>
                  <th scope="col" class="align-middle" rowspan="2">Action</th>
               </tr>
               <tr class="table-primary">
                  <th scope="col">Double</th>
                  <th scope="col">minimal</th>
                  <th scope="col">Lebih Kurang</th>
                  <th scope="col">Kelipatan</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($rules as $i => $p) : ?>
                  <tr>
                     <th><?= $p['id']; ?></th>
                     <th><?= $p['rule_name']; ?></th>
                     <th><?= $p['information']; ?></th>
                     <th>Rp. <?= number_format($p['limit_nominal'],2,',','.') ?></th>
                     <th><?= $p['limit_points']; ?></th>
                     <th><?= $p['double']; ?></th>
                     <th><?= $p['minimal']; ?></th>
                     <th><?= $p['limit_amount_nominal']; ?></th>
                     <th><?= $p['multiple']; ?></th>
                     <th>
                        <a href="<?= base_url('/admin/detailRulesProduct/' . $p['id']); ?>" class="badge badge-info">Detail</a>
                        <a href="<?= base_url('/admin/deleteRulesProduct/' . $p['id']); ?>" class="badge badge-danger">Delete</a>
                     </th>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Rule Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('admin/addProductRule') ?>" method="post" autocomplete="off">
               <?= csrf_field() ?>
               <!-- rule_name -->
               <div class="form-group">
                  <label for="rule_name"> Nama Rule </label>
                  <input type="text" name="rule_name" class="form-control <?= $validation->hasError('rule_name') ? 'is-invalid' : (!old('rule_name') ? '' : 'is-valid'); ?>" value="<?= old('rule_name') ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('rule_name'); ?>
                  </div>
               </div>
               <!-- keterangan -->
               <div class="form-group">
                  <label for="information"> Keterangan </label>
                  <input type="text" name="information" class="form-control <?= $validation->hasError('information') ? 'is-invalid' : (!old('information') ? '' : 'is-valid'); ?>" value="<?= old('information') ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('information'); ?>
                  </div>
               </div>
               <!-- batas_nominal -->
               <div class="form-group">
                  <label for="limit_nominal"> Batas Nominal </label>
                  <input type="number" name="limit_nominal" class="form-control <?= $validation->hasError('limit_nominal') ? 'is-invalid' : (!old('limit_nominal') ? '' : 'is-valid'); ?>" value="<?= old('limit_nominal') ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('limit_nominal'); ?>
                  </div>
               </div>
               <!-- poin_batas -->
               <div class="form-group">
                  <label for="limit_points"> Batas Poin </label>
                  <input type="number" name="limit_points" class="form-control <?= $validation->hasError('limit_points') ? 'is-invalid' : (!old('limit_points') ? '' : 'is-valid'); ?>" value="<?= old('limit_points') ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('limit_points'); ?>
                  </div>
               </div>
               <!-- double-->
               <div class="form-group">
                  <label for="double">Double</label>
                  <select type="text" name="double" class="form-control <?= $validation->hasError('double') ? 'is-invalid' : (!old('double') ? '' : 'is-valid'); ?>" id="double">
                     <!-- <option value="">1 = True | 0 = False</option> -->
                     <option value="">1 = True | 0 = False</option>
                     <?php foreach ($double as $d) : ?>
                        <?php if (old('double') != null) { ?>
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
                        <?php } else { ?>
                           <option value="<?= $k; ?>"><?= $k ?></option>
                     <?php }
                     endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('multiple'); ?>
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