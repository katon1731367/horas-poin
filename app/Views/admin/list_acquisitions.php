<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
   
   <!-- Flash Massage -->
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

   <div class="row">
   <!-- User Card Details -->
      <div class="col-lg-8">
         <div class="row">
            <div class="col col-md-6 mb-4">
               <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                     <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                           <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Upload Akuisisi</div>
                           <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $countAcquisitions; ?></div>
                        </div>
                        <div class="col-auto">
                           <i class="fas fa-archive fa-2x text-gray-300"></i>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col col-md-6 mb-4">
                <a href="<?= base_url('admin/exportAcquisitions'); ?>" class="btn btn-success mb-3 mr-3 mt-3" style="width: 10rem;"> Ekport Semua Akuisisi </a>
                <a href="<?= base_url('admin/deleteAllAcquisitions'); ?>" class="btn btn-danger mb-3 mr-3 mt-3" style="width: 10rem;" onclick="return confirm('Yakin hapus seluruh data akuisisi?')"> Hapus Semua Akuisisi </a>
            </div>
         </div>
      </div>
   </div>
   

    <!-- filter -->
    <button class="btn btn-info mb-3 mr-3" style="width: 10rem;" onclick="showFilter()"> Filter Pencarian </button>
   
   <!-- filter hidden -->
   <div class="row ml-4" id="filter" hidden>
      <div class="col-md-10 mb-3">
         <div class="row">
            <div class="form-row">
               <div class="col-md-3 mb-1">
                  <label for="keyword">Pencarian</label>
                  <input type="text" class="form-control" placeholder="<?= $keyword ? $keyword : 'Nama Nasabah / CIF / No Rekening'; ?>" name="keyword" id="keyword">
               </div>
               <div class="col-md-3 mb-1">
                  <label for="product_name">Product</label>
                  <select class="custom-select" id="product_name" required>
                     <option selected disabled value="">Choose...</option>
                     <?php foreach ($product as $i => $p) : ?>
                        <option value="<?= $p['id']; ?>"><?= $p['product_name']; ?></option>
                     <?php endforeach ?>
                  </select>
               </div>
               <div class="col-md-3 mb-1">
                  <label for="from">Dari</label>
                  <input type="date" class="form-control" id="from">
               </div>
               <div class="col-md-3 mb-1">
                  <label for="to">Sampai</label>
                  <input type="date" class="form-control" id="to">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-3 mb-3">
               <button class="btn btn-outline-primary mt-1" type="button" id="terapkan">Terapkan</button>
               <button class="btn btn-outline-danger mt-1" type="button" id="clear">Clear</button>
            </div>
         </div>
      </div>
   </div>
   <!-- table akuisisi -->
   <div class="row" id="get_data"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload Akusisi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
        <form action="<?= base_url('user/submitAkuisisi') ?>" method="post" autocomplete="off">
            <div class="modal-body">
               <?= csrf_field() ?>
               <!-- product-->
               <div class="form-group">
                  <label for="product">Product</label>
                  <select type="text" name="product" class="form-control <?= $validation->hasError('product') ? 'is-invalid' : (!old('product') ? '' : 'is-valid'); ?>" id="product">
                     <?php foreach ($product as $i => $p) : ?>
                        <option value="<?= $p['id']; ?>"><?= $p['product_name']; ?></option>
                     <?php endforeach ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('product'); ?>
                  </div>
               </div>
               <!-- project -->
               <div class="form-group">
                  <label for="project">Project</label>
                  <select type="text" name="project" class="form-control <?= $validation->hasError('project') ? 'is-invalid' : (!old('project') ? '' : 'is-valid'); ?>" id="project">
                     <?php foreach ($project as $i => $p) : ?>
                        <option value="<?= $p['id']; ?>"><?= $p['project_name']; ?></option>
                     <?php endforeach ?>
                  </select>
                  <div class="invalid-feedback">
                     <?= $validation->getError('project'); ?>
                  </div>
               </div>
               <!-- Nominal -->
               <div class="form-group">
                  <label for="nominal">Nominal</label>
                  <input type="text" name="nominal" class="form-control" value="<?= old('nominal'); ?>" />
               </div>
               <!-- Nama Nasabah -->
               <div class="form-group">
                  <label for="customer_name">Nama Nasabah</label>
                  <input type="text" name="customer_name" class="form-control <?= $validation->hasError('customer_name') ? 'is-invalid' : (!old('customer_name') ? '' : 'is-valid'); ?>" value="<?= old('customer_name'); ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('customer_name'); ?>
                  </div>
               </div>
               <!-- Visita/Akuisisi -->
               <div class="form-group">
                  <label for="visitation">Visita/Akuisisi</label>
                  <input type="text" name="visitation" class="form-control <?= $validation->hasError('visitation') ? 'is-invalid' : (!old('visitation') ? '' : 'is-valid'); ?>" value="<?= old('visitation'); ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('visitation'); ?>
                  </div>
               </div>
               <!-- Nomor Rekening -->
               <div class="form-group">
                  <label for="rekening">No. Rekening</label>
                  <input type="text" name="rekening" class="form-control <?= $validation->hasError('rekening') ? 'is-invalid' : (!old('rekening') ? '' : 'is-valid'); ?>" value="<?= old('rekening'); ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('rekening'); ?>
                  </div>
               </div>
               <!-- Nomor CIF -->
               <div class="form-group">
                  <label for="cif">No. CIF</label>
                  <input type="text" name="cif" class="form-control <?= $validation->hasError('cif') ? 'is-invalid' : (!old('cif') ? '' : 'is-valid'); ?>" value="<?= old('cif'); ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('cif'); ?>
                  </div>
               </div>
               <!-- Nomor Handphone -->
               <div class="form-group">
                  <label for="handphone">No. Handphone</label>
                  <input type="text" name="handphone" class="form-control <?= $validation->hasError('handphone') ? 'is-invalid' : (!old('handphone') ? '' : 'is-valid'); ?>" value="<?= old('handphone'); ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('handphone'); ?>
                  </div>
               </div>
               <!-- tanggal_akuisisi -->
               <div class="form-group">
                  <label for="acquisition_date">Tanggal Akuisisi</label>
                  <input type="date" name="acquisition_date" class="form-control <?= $validation->hasError('acquisition_date') ? 'is-invalid' : (!old('acquisition_date') ? '' : 'is-valid'); ?>" value="<?= old('acquisition_date'); ?>" />
                  <div class="invalid-feedback">
                     <?= $validation->getError('acquisition_date'); ?>
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

<script>
   function showFilter() {
      filter = document.getElementById('filter');
      if (filter.hidden == false) {
         document.getElementById('filter').hidden = true;
      } else {
         document.getElementById('filter').hidden = false;
      }
   }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    var url_fetch = "<?= base_url('admin/paginationAcquisitions') ?>";
    var url_filter = "<?= base_url('admin/applyfilters') ?>";

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