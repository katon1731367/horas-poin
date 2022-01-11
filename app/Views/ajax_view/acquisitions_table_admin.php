<div class="col animated--grow-in">
   <div class="table-responsive">
      <?php if (session()->getFlashdata('hasil_cari')) { ?>
         <div class="mb-2">
            <?= session()->getflashdata('hasil_cari') . ', menampilkan ' . count($acquisitions) . ' data'; ?>
         </div>
      <?php } ?>

      <?php $totalAcquisitions = array_sum(array_map("count", $acquisitions)); ?>
      <?php if ($totalAcquisitions > 0) { ?>
      <table class="table table-hover">
         <thead>
            <tr class="table-primary">
               <th scope="col">#</th>
               <th scope="col">PRODUCT</th>
               <th scope="col">PROJECT</th>
               <th scope="col">NIP KARYAWAN</th>
               <th scope="col">UNIT CABANG</th>
               <th scope="col">NAMA NASABAH</th>
               <th scope="col">CIF</th>
               <th scope="col">NO. REKENING</th>
               <th scope="col">NOMINAL</th>
               <th scope="col">TANGGAL AKUISISI</th>
               <th scope="col">TANGGAL UPLOAD</th>
               <th scope="col">ACTION</th>
            </tr>
         </thead>
         <tbody>
               <?php foreach ($acquisitions as $a) :
                  $start += 1; ?>
                  <tr>
                     <th scope="row"><?= $start; ?></th>
                     <td><?= $a['product_name']; ?></td>
                     <td><?= $a['project_name']; ?></td>
                     <td>
                         <span data-toggle="tooltip" data-placement="top" title="<?= $a['fullname'] ? $a['fullname'] : $a['username'] ?>"><?= $a['nip']; ?></span>
                     </td>
                     <td>
                        <span data-toggle="tooltip" data-placement="top" title="<?= $a['unit_name'] ?>"><?= $a['id_unit']; ?></span>
                     </td>
                     <td><?= $a['customer_name']; ?></td>
                     <td><?= $a['cif']; ?></td>
                     <td><?= $a['rekening']; ?></td>
                     <td>Rp. <?= number_format($a['nominal'],2,',','.') ?></td>
                     <td><?= $a['acquisitions_dates']; ?></td>
                     <td><?= $a['created_at']; ?></td>
                     <td>
                        <a href="<?= base_url('user/editAcquisitions/' . $a['id']); ?>" class="badge badge-warning">edit</a>
                        <a href="<?= base_url('user/deleteAcquisitions/' . $a['id']); ?>" class="badge badge-danger" onclick="return confirm('Yakin hapus data akuisisi atas nama <?= $a['customer_name'] ?> ?')">Hapus</a>
                     </td>
                  </tr>
               <?php endforeach; ?>
         </tbody>
      </table>
      <?php } else { ?>
   <h3>Data akuisisi tidak ditemukan</h3>
<?php } ?>

   </div>
   
   <?php if ($totalAcquisitions > 0) { ?>
   <ul class="pagination">
      <?php if ($totalPage > 1) {
                  $previous = $totalPage - 1; ?>
         <li class="page-item" id="1">
            <buttton class="page-link">first page</buttton>
         </li>
         <li class="page-item" id="<?= $previous; ?>">
            <buttton class="page-link"><i class="fa fa-arrow-left"></i></buttton>
         </li>
      <?php } ?>

      <?php for ($i = 1; $i <= $totalPage; $i++) {
                  $active = '';
                  if ($page == $i) {
                     $active = 'active';
                  } ?>
         <li class="page-item <?= $active; ?>" id="<?= $i; ?>">
            <buttton class="page-link"><?= $i; ?></buttton>
         </li>
      <?php } ?>

      <?php if ($page < $totalPage) {
                  $page++ ?>
         <li class="page-item" id="<?= $page; ?>">
            <buttton class="page-link"><i class="fa fa-arrow-right"></i></buttton>
         </li>
         <li class="page-item" id="<?= $totalPage; ?>">
            <buttton class="page-link">Last Page</buttton>
         </li>
      <?php } ?>
   </ul>
   <?php } ?>
</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>