<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800 text-center">
      <img src="<?= base_url('/img/horas_poin.svg') ?>" alt="Horas Poin" style="max-width: 15rem">
   </h1>

   <!--Flashdata message -->
   <?php if (session()->getFlashdata('pesan')) :
      if ($validation->getErrors()) : ?>
         <div class="alert alert-danger" role="alert">
            <?= session()->getflashdata('pesan'); ?>
         </div>
      <?php else : ?>
         <div class="alert alert-success" role="alert">
            <?= session()->getflashdata('pesan'); ?>
         </div>
   <?php endif;
   endif; ?>

   <!--Patriot Day message-->
   <?php foreach ($patriotDay as $p) :
      if ($p['dates'] == date("Y-m-d")) { ?>
         <div class="alert alert-info" role="alert">
            <h3>Patriot Day!</h3>
            <h5>Input akuisisi double poin</h5>
         </div>
   <?php }
   endforeach ?>

   <!-- User Card Details -->
   <div class="row">
      <!--card user-->
      <div class="col-lg-4">
         <div class="card mb-3" style="max-width: 540px;">
            <div class="row no-gutters">
               <!-- user image -->
               <div class="col-md-3 ml-auto mr-auto">
                  <img src="<?= base_url('/img/user_image/' . user()->user_image); ?>" class="img-responsive ml-4 mt-4" alt="<?php user()->username ?>'s profile picture" style="max-width: 120px;">
               </div>
               <!-- user info -->
               <div class="col-md-8">
                  <div class="card-body">
                     <div class="card">
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item">
                              <h4><?= user()->username; ?></h4>
                           </li>
                           <?php if (user()->fullname) : ?>
                              <li class="list-group-item"><?= user()->fullname ?> </li>
                           <?php endif; ?>
                           <li class="list-group-item"><?= user()->email; ?> </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!--header card-->
      <div class="col-lg-8">
         <div class="row">
            <!-- card poin sementara -->
            <div class="col col-md-6 mb-4">
               <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                     <div class="row no-gutters align-items-center">
                        <div class="col">
                           <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                        <div class="col mr-2">
                           <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Point Sementara</div>
                           <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $countPoint; ?></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- card jumlah akuisisi -->
            <div class="col col-md-6 mb-4">
               <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                     <div class="row no-gutters align-items-center">
                        <div class="col mr-4">
                           <i class="fas fa-archive fa-2x text-gray-300"></i>
                        </div>
                        <div class="col">
                           <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Upload Akuisisi</div>
                           <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $countAcquisitions; ?></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <!-- Button Modal -->
            <button type="button" class="btn btn-primary ml-3 mb-3" data-toggle="modal" data-target="#uploadAkuisisi">
               Input Akuisisi
            </button>
         </div>

      </div>
   </div>

   <!-- filter -->
   <button class="btn btn-info mb-3" style="width: 10rem;" onclick="showFilter()"> Filter Pencarian </button>

   <!-- filter hidden -->
   <div class="row ml-2 mb-3 bg-light" id="filter" hidden>
      <div class="col-md-12">
         <!-- form group pencarian -->
         <div class="form-row">
            <!-- form dengan nama dll -->
            <div class="col-md mb-1 mr-1">
               <label for="pencarian1">Pencarian</label>
               <input type="text" class="form-control" placeholder="<?= $keyword ? $keyword : 'Nama Nasabah / CIF / No Rekening'; ?>" name="keyword" id="keyword">
            </div>
            <!-- form product -->
            <div class="col-md mb-1 mr-1 ml-1">
               <label for="pencarian_produk">Product</label>
               <select class="custom-select" id="product_name" required>
                  <option selected disabled value="">Choose...</option>
                  <?php foreach ($product as $i => $p) : ?>
                     <option value="<?= $p['id']; ?>"><?= $p['product_name']; ?></option>
                  <?php endforeach ?>
               </select>
            </div>
            <!-- form tanggal dari -->
            <div class="col-md mb-1 mr-1 ml-1">
               <label for="pencarianTanggalDari">Dari</label>
               <input type="date" class="form-control" id="from">
            </div>
            <!-- form tanggal sampai -->
            <div class="col-md mb-1 mr-1 ml-1">
               <label for="pencarianTanggalSampai">Sampai</label>
               <input type="date" class="form-control" id="to">
            </div>

         </div>
         <!-- button group pemcarian  -->
         <div class="row">
            <div class="col-md-3 mb-3">
               <button class="btn btn-outline-primary mt-1" type="button" id="terapkan" >Terapkan</button>
               <button class="btn btn-outline-danger mt-1" type="button" id="clear">Clear</button>
            </div>
         </div>
      </div>
   </div>

   <!-- table akuisisi -->
   <div class="row" id="get_data" onload="fetch_data()"></div>

   <!-- Modal upload akuisisi-->
   <div class="modal fade" id="uploadAkuisisi" tabindex="-1" aria-labelledby="uploadAcquisitionsLabel" aria-hidden="true">
      <div class="modal-dialog  modal-dialog-scrollable">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="uploadAkuisisiLabel">Upload Akusisi</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <form action="<?= base_url('user/submitAcquisitions') ?>" method="post" autocomplete="off">
                  <?= csrf_field() ?>
                  <!-- product-->
                  <div class="form-group">
                     <label for="product">Product</label>
                     <select type="text" name="product" class="form-control <?= $validation->hasError('product') ? 'is-invalid' : (!old('product') ? '' : 'is-valid'); ?>" id="product" required>
                        <option value="">pilih product</option>
                        <?php foreach ($product as $i => $p) : ?>
                           <?php if (old('project') == $p['id']) { ?>
                              <option value="<?= $p['id']; ?>" selected><?= $p['product_name']; ?></option>
                           <?php } else { ?>
                              <option value="<?= $p['id']; ?>"><?= $p['product_name']; ?></option>
                        <?php }
                        endforeach ?>
                     </select>
                     <div class="invalid-feedback">
                        <?= $validation->getError('product'); ?>
                     </div>
                  </div>
                  <!-- project -->
                  <div class="form-group">
                     <label for="project">Project</label>
                     <select type="text" name="project" class="form-control <?= $validation->hasError('project') ? 'is-invalid' : (!old('project') ? '' : 'is-valid'); ?>" id="project" required>
                        <option value="">pilih product</option>
                        <?php foreach ($project as $i => $p) : ?>
                           <?php if (old('project') == $p['id']) { ?>
                              <option value="<?= $p['id']; ?>" selected><?= $p['project_name']; ?></option>
                           <?php } else { ?>
                              <option value="<?= $p['id']; ?>"><?= $p['project_name']; ?></option>
                        <?php }
                        endforeach ?>
                     </select>
                     <div class="invalid-feedback">
                        <?= $validation->getError('project'); ?>
                     </div>
                  </div>
                  <!-- Nama Nasabah -->
                  <div class="form-group">
                     <label for="customer_name">Nama Nasabah</label>
                     <input type="text" name="customer_name" class="form-control <?= $validation->hasError('customer_name') ? 'is-invalid' : (!old('customer_name') ? '' : 'is-valid'); ?>" value="<?= old('customer_name'); ?>" required />
                     <div class="invalid-feedback">
                        <?= $validation->getError('customer_name'); ?>
                     </div>
                  </div>
                  <!-- Visita/Akuisisi -->
                  <div class="form-group">
                     <label for="visitation">Visita/Akuisisi</label>
                     <select type="text" name="visitation" class="form-control <?= $validation->hasError('visitation') ? 'is-invalid' : (!old('visitation') ? '' : 'is-valid'); ?>" id="visitation" required>
                        <option value="">pilih visitasi</option>
                        <?php foreach ($visitation as $v) : ?>
                           <?php if (old('visitation') == $v) { ?>
                              <option value="<?= $v; ?>" selected><?= $v ?></option>
                           <?php } else { ?>
                              <option value="<?= $v; ?>"><?= $v ?></option>
                        <?php }
                        endforeach ?>
                     </select>
                     <div class="invalid-feedback">
                        <?= $validation->getError('visit_akuisisi'); ?>
                     </div>
                  </div>
                  <!-- Sumber Pipeline -->
                  <div class="form-group">
                     <label for="lead_sources">Sumber Pipeline</label>
                     <select type="text" name="lead_sources" class="form-control <?= $validation->hasError('lead_sources') ? 'is-invalid' : (!old('lead_sources') ? '' : 'is-valid'); ?>" id="lead_sources" required>
                        <option value="">pilih salah satu</option>
                        <?php foreach ($leadSources as $l) : ?>
                           <?php if (old('lead_sources') == $l) { ?>
                              <option value="<?= $l; ?>" selected><?= $l ?></option>
                           <?php } else { ?>
                              <option value="<?= $l; ?>"><?= $l ?></option>
                        <?php }
                        endforeach ?>
                     </select>
                     <div class="invalid-feedback">
                        <?= $validation->getError('sumber_lead'); ?>
                     </div>
                  </div>
                  <!-- Jenis Nasabah -->
                  <div class="form-group">
                     <label for="costumer_type">Jenis Nasabah</label>
                     <select type="text" name="costumer_type" class="form-control <?= $validation->hasError('costumer_type') ? 'is-invalid' : (!old('costumer_type') ? '' : 'is-valid'); ?>" id="costumer_type" required>
                        <option value="">pilih salah satu</option>
                        <?php foreach ($costumerType as $j) : ?>
                           <?php if (old('costumer_type') == $j) { ?>
                              <option value="<?= $j; ?>" selected><?= $j ?></option>
                           <?php } else { ?>
                              <option value="<?= $j; ?>"><?= $j ?></option>
                        <?php }
                        endforeach ?>
                     </select>
                     <div class="invalid-feedback">
                        <?= $validation->getError('jenis_nasabah'); ?>
                     </div>
                  </div>
                  <!-- Nomor Rekening -->
                  <div class="form-group">
                     <label for="rekening">No. Rekening</label>
                     <input type="text" name="rekening" class="form-control <?= $validation->hasError('rekening') ? 'is-invalid' : (!old('rekening') ? '' : 'is-valid'); ?>" value="<?= old('rekening'); ?>" required />
                     <div class="invalid-feedback">
                        <?= $validation->getError('rekening'); ?>
                     </div>
                  </div>
                  <!-- Nominal -->
                  <div class="form-group">
                     <label for="nominal">Nominal</label>
                     <input type="text" name="nominal" class="form-control" value="<?= old('nominal'); ?>" />
                  </div>
                  <!-- Nomor CIF -->
                  <div class="form-group">
                     <label for="cif">No. CIF</label>
                     <input type="text" name="cif" class="form-control <?= $validation->hasError('cif') ? 'is-invalid' : (!old('cif') ? '' : 'is-valid'); ?>" value="<?= old('cif'); ?>" required />
                     <div class="invalid-feedback">
                        <?= $validation->getError('cif'); ?>
                     </div>
                  </div>
                  <!-- Nomor Handphone -->
                  <div class="form-group">
                     <label for="handphone">No. Handphone</label>
                     <input type="text" name="handphone" class="form-control <?= $validation->hasError('handphone') ? 'is-invalid' : (!old('handphone') ? '' : 'is-valid'); ?>" value="<?= old('handphone'); ?>" required />
                     <div class="invalid-feedback">
                        <?= $validation->getError('handphone'); ?>
                     </div>
                  </div>
                  <!-- Status -->
                  <div class="form-group">
                     <label for="status">Jenis Nasabah</label>
                     <select type="text" name="status" class="form-control <?= $validation->hasError('status') ? 'is-invalid' : (!old('status') ? '' : 'is-valid'); ?>" id="status" required>
                        <option value="">pilih salah satu</option>
                        <?php foreach ($status as $s) : ?>
                           <?php if (old('status') == $s) { ?>
                              <option value="<?= $s; ?>" selected><?= $s ?></option>
                           <?php } else { ?>
                              <option value="<?= $s; ?>"><?= $s ?></option>
                        <?php }
                        endforeach ?>
                     </select>
                     <div class="invalid-feedback">
                        <?= $validation->getError('status'); ?>
                     </div>
                  </div>
                  <!-- tanggal_akuisisi -->
                  <div class="form-group">
                     <label for="acquisitions_dates">Tanggal Akuisisi</label>
                     <input type="date" name="acquisitions_dates" class="form-control <?= $validation->hasError('acquisitions_dates') ? 'is-invalid' : (!old('acquisitions_dates') ? '' : 'is-valid'); ?>" value="<?= old('acquisitions_dates'); ?>" required />
                     <div class="invalid-feedback">
                        <?= $validation->getError('acquisitions_dates'); ?>
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
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    var url_fetch = "<?= base_url('user/paginationAcquisitions') ?>";
    var url_filter = "<?= base_url('user/applyfilters') ?>";

    $( document ).ready(function() {
        fetch_data();
    });
        
    $(document).on("click", ".page-item", function() {
        filterAcquisitationUser();
    });
    
    $(document.body).on('click', '#clear', function() {
        clearForm();
    });
    
    $(document.body).on('click', '#terapkan', function() {
        pagePaginationUser();
    });
</script>


<?= $this->endSection(); ?>