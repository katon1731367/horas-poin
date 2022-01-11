<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

   <!-- Flash Massage -->
   <?php if (session()->getFlashdata('pesan')) : ?>
      <div class="alert alert-success" role="alert">
         <?= session()->getflashdata('pesan'); ?>
      </div>
   <?php endif ?>

   <!--Form Update-->
   <form action="<?= base_url('user/updateAcquisitions/' . $acquisitions['id']) ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>
      <!-- product-->
      <div class="form-group">
         <label for="product">Product</label>
         <select type="text" name="product" class="form-control <?= $validation->hasError('product') ? 'is-invalid' : (!old('product') ? '' : 'is-valid'); ?>" id="product">
            <option value="">pilih product</option>
            <?php foreach ($product as $i => $p) : ?>
               <?php if (old('project') == $p['id']) { ?>
                  <option value="<?= $p['id']; ?>" selected><?= $p['product_name']; ?></option>
               <?php } elseif ($p['id'] == $acquisitions['id_product']) { ?>
                  <option value="<?= $p['id']; ?>" selected><?= $p['product_name']; ?></option>
               <?php } else { ?>
                  <option value="<?= $p['id']; ?>"><?= $p['product_name']; ?></option>
            <?php }
            endforeach; ?>
         </select>
         <div class="invalid-feedback">
            <?= $validation->getError('product'); ?>
         </div>
      </div>
      <!-- project -->
      <div class="form-group">
         <label for="project">Project</label>
         <select type="text" name="project" class="form-control <?= $validation->hasError('project') ? 'is-invalid' : (!old('project') ? '' : 'is-valid'); ?>" id="project">
            <option value="">pilih product</option>
            <?php foreach ($project as $i => $p) : ?>
               <?php if (old('project') == $p['id']) { ?>
                  <option value="<?= $p['id']; ?>" selected><?= $p['project_name']; ?></option>
               <?php } elseif ($p['id'] == $acquisitions['id_project']) { ?>
                  <option value="<?= $p['id']; ?>" selected><?= $p['project_name']; ?></option>
               <?php } else { ?>
                  <option value="<?= $p['id']; ?>"><?= $p['project_name']; ?></option>
            <?php }
            endforeach; ?>
         </select>
         <div class="invalid-feedback">
            <?= $validation->getError('project'); ?>
         </div>
      </div>
      <!-- Nominal -->
      <div class="form-group">
         <label for="nominal">Nominal</label>
         <input type="text" name="nominal" class="form-control" value="<?= old('nominal') ? old('nominal') : $acquisitions['nominal'] ?>" />
      </div>
      <!-- Nama Nasabah -->
      <div class="form-group">
         <label for="customer_name">Nama Nasabah</label>
         <input type="text" name="customer_name" class="form-control <?= $validation->hasError('customer_name') ? 'is-invalid' : (!old('customer_name') ? '' : 'is-valid'); ?>" value="<?= old('customer_name') ? old('customer_name') : $acquisitions['customer_name'] ?>"" />
         <div class=" invalid-feedback">
         <?= $validation->getError('customer_name'); ?>
      </div>
      <!-- Visita/akuisisi -->
      <div class="form-group">
         <label for="visitation">Visita/acquisitions</label>
         <select type="text" name="visitation" class="form-control <?= $validation->hasError('visitation') ? 'is-invalid' : (!old('visitation') ? '' : 'is-valid'); ?>" id="visitation">
            <option value="">pilih visitasi</option>
            <?php foreach ($visitation as $v) : ?>
               <?php if (old('visitation') == $v) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } elseif ($v == $acquisitions['visitation']) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } else { ?>
                  <option value="<?= $v; ?>"><?= $v ?></option>
            <?php }
            endforeach; ?>
         </select>
         <div class="invalid-feedback">
            <?= $validation->getError('visitation'); ?>
         </div>
      </div>
      <!-- Sumber Pipeline -->
      <div class="form-group">
         <label for="lead_sources">Sumber Pipeline</label>
         <select type="text" name="lead_sources" class="form-control <?= $validation->hasError('lead_sources') ? 'is-invalid' : (!old('lead_sources') ? '' : 'is-valid'); ?>" id="lead_sources">
            <option value="">pilih salah satu</option>
            <?php foreach ($leadSources as $v) : ?>
               <?php if (old('lead_sources') == $v) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } elseif ($v == $acquisitions['lead_sources']) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } else { ?>
                  <option value="<?= $v; ?>"><?= $v ?></option>
            <?php }
            endforeach; ?>
         </select>
         <div class="invalid-feedback">
            <?= $validation->getError('sumber_lead'); ?>
         </div>
      </div>
      <!-- Jenis Nasabah -->
      <div class="form-group">
         <label for="customer_type">Jenis Nasabah</label>
         <select type="text" name="customer_type" class="form-control <?= $validation->hasError('customer_type') ? 'is-invalid' : (!old('customer_type') ? '' : 'is-valid'); ?>" id="customer_type">
            <option value="">pilih salah satu</option>
            <?php foreach ($customerType as $v) : ?>
               <?php if (old('customer_type') == $v) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } elseif ($v == $acquisitions['customer_type']) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } else { ?>
                  <option value="<?= $v; ?>"><?= $v ?></option>
            <?php }
            endforeach; ?>
         </select>
         <div class="invalid-feedback">
            <?= $validation->getError('customer_type'); ?>
         </div>
      </div>
      <!-- Nomor Rekening -->
      <div class="form-group">
         <label for="rekening">No. Rekening</label>
         <input type="text" name="rekening" class="form-control <?= $validation->hasError('rekening') ? 'is-invalid' : (!old('rekening') ? '' : 'is-valid'); ?>" value="<?= old('rekening') ? old('rekening') : $acquisitions['rekening'] ?>" />
         <div class="invalid-feedback">
            <?= $validation->getError('rekening'); ?>
         </div>
      </div>
      <!-- Nomor CIF -->
      <div class="form-group">
         <label for="cif">No. CIF</label>
         <input type="text" name="cif" class="form-control <?= $validation->hasError('cif') ? 'is-invalid' : (!old('cif') ? '' : 'is-valid'); ?>" value="<?= old('cif') ? old('cif') : $acquisitions['cif'] ?>" />
         <div class="invalid-feedback">
            <?= $validation->getError('cif'); ?>
         </div>
      </div>
      <!-- Nomor Handphone -->
      <div class="form-group">
         <label for="handphone">No. Handphone</label>
         <input type="text" name="handphone" class="form-control <?= $validation->hasError('handphone') ? 'is-invalid' : (!old('handphone') ? '' : 'is-valid'); ?>" value="<?= old('handphone') ? old('handphone') : $acquisitions['no_handphone'] ?>"" />
            <div class=" invalid-feedback">
         <?= $validation->getError('handphone'); ?>
      </div>
      <!-- Status -->
      <div class="form-group">
         <label for="status">Status Nasabah</label>
         <select type="text" name="status" class="form-control <?= $validation->hasError('status') ? 'is-invalid' : (!old('status') ? '' : 'is-valid'); ?>" id="status">
            <option value="">pilih salah satu</option>
            <?php foreach ($status as $v) : ?>
               <?php if (old('status') == $v) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } elseif ($v == $acquisitions['status']) { ?>
                  <option value="<?= $v; ?>" selected><?= $v ?></option>
               <?php } else { ?>
                  <option value="<?= $v; ?>"><?= $v ?></option>
            <?php }
            endforeach; ?>
         </select>
         <div class="invalid-feedback">
            <?= $validation->getError('status'); ?>
         </div>
      </div>
      <!-- tanggal_akuisisi -->
      <div class="form-group">
         <label for="acquisitions_dates">Tanggal acquisitions</label>
         <input type="date" name="acquisitions_dates" class="form-control <?= $validation->hasError('acquisitions_dates') ? 'is-invalid' : (!old('acquisitions_dates') ? '' : 'is-valid'); ?>" value="<?= old('acquisitions_dates') ? old('acquisitions_dates') : $acquisitions['acquisitions_dates'] ?>" />
         <div class="invalid-feedback">
            <?= $validation->getError('acquisitions_dates'); ?>
         </div>
      </div>
</div>

<a href="<?= base_url('/user/'); ?>" class="btn btn-secondary">Back</a>
<button type="submit" class="btn btn-primary">Submit</button>

</form>


<?= $this->endSection(); ?><?= $this->extend('templates/index'); ?>