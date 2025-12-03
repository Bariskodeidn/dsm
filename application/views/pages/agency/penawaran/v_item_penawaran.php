<div class="container-fluid">
   <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
         <h1 class="page-title">Daftar Item Penawaran </h1>
         <div class="card shadow mb-4">
            <div class="card-body">
               <div class="row mb-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalPenawaranTambah">
                        <i class="fe fe-plus"></i> Tambah Item
                     </button>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 col-xs-12 form-group pull-right top_search">
                     <form class="form-horizontal form-label-left" method="get" action="<?= site_url('agency/item_penawaran') ?>">
                        <div class="input-group">
                           <input type="text" class="form-control" name="keyword" placeholder="Search for..." value="<?= $this->input->get('keyword') ?>">
                           <span class="input-group-btn">
                              <button class="btn btn-secondary" type="submit">Go!</button>
                              <a href="<?= site_url('agency/item_penawaran') ?>" class="btn btn-warning" style="color:white;">Reset</a>
                           </span>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-bordered" id="">
                     <thead class="thead-dark">
                        <tr>
                           <th width="45px">No.</th>
                           <th>Nama Penawaran</th>
                           <th>Harga</th>
                           <th>Jenis Item</th>
                           <th width="150px">#</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if ($penawaran->num_rows() > 0) {
                           foreach ($penawaran->result_array() as $value) {
                        ?>
                              <tr>
                                 <td><?= ++$page; ?></td>
                                 <td><?= $value['nama_penawaran'] ?></td>
                                 <td class="text-right"><?= number_format($value['cost']) ?></td>
                                 <td>
                                    <?php
                                    if ($value['jenis'] == 1) {
                                       echo "Biaya Tetap";
                                    }

                                    if ($value['jenis'] == 2) {
                                       echo "Biaya Tetap (Additional)";
                                    }

                                    if ($value['jenis'] == 3) {
                                       echo "Biaya Tambahan";
                                    }
                                    ?>
                                 </td>
                                 <td>
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalPenawaranUbah<?= $value['Id'] ?>"><i class="fe fe-edit" aria-hidden="true"></i></button>
                                    <a href="<?= base_url('agency/hapus_item_penawaran/' . $value['Id']) ?>" class="btn btn-danger btn-sm hapusItem"><i class="fe fe-trash" aria-hidden="true"></i></a>
                                    <!-- Modal Upload File Penawaran -->
                                    <div class="modal fade" id="myModalPenawaranUbah<?= $value['Id'] ?>" role="dialog">
                                       <div class="modal-dialog">
                                          <!-- Modal content-->
                                          <div class="modal-content modal-center">
                                             <div class="modal-header">
                                                <h5 class="modal-title" id="defaultModalLabel">Ubah Item Penawaran</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                   <span aria-hidden="true">×</span>
                                                </button>
                                             </div>
                                             <div class="modal-body">
                                                <form action="<?= base_url('agency/ubah_item_penawaran/' . $value['Id']) ?>" enctype="multipart/form-data" method="post" id="form-ubah-item">
                                                   <div class="form-group">
                                                      <label for="form-label">Nama Penawaran</label>
                                                      <input type="text" class="form-control" name="nama_penawaran_ubah" id="nama_penawaran_ubah" value="<?= $value['nama_penawaran'] ?>">
                                                   </div>
                                                   <div class="form-group">
                                                      <label for="form-label">Harga Penawaran</label>
                                                      <input type="text" class="form-control uang" name="harga_penawaran_ubah" id="harga_penawaran_ubah" value="<?= preg_replace('/[^a-zA-Z0-9\']/', '', number_format($value['cost'])) ?>">
                                                   </div>
                                                   <div class="form-group">
                                                      <label for="form-label">Jenis Item</label>
                                                      <select name="jenis_item_ubah" id="jenis_item_ubah" class="form-control">
                                                         <option value=""> ::Pilih Jenis Item :: </option>
                                                         <option value="1" <?= $value['jenis'] == 1 ? 'selected' : '' ?>>Biaya Tetap</option>
                                                         <option value="2" <?= $value['jenis'] == 2 ? 'selected' : '' ?>>Biaya Tetap (Additional)</option>
                                                         <option value="2" <?= $value['jenis'] == 3 ? 'selected' : '' ?>>Biaya Tambahan</option>
                                                      </select>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-md-12">
                                                         <button type="submit" class="btn btn-primary btn-sm btn-simpan"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
                                                      </div>
                                                   </div>
                                                </form>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                           <?php }
                        } else { ?>
                           <tr align="center">
                              <td colspan="7">Tidak ada data</td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="row">
                  <div class="col-md-12 col-xs-12 text-right">
                     <?= $this->pagination->create_links() ?>
                  </div>
               </div>
            </div>
         </div>
      </div> <!-- .col-12 -->
   </div> <!-- .row -->
</div> <!-- .container-fluid -->

<div class="modal fade show" id="myModalPenawaranTambah" tabindex="-1" role="dialog" aria-labelledby="myModalPenawaranTambah" aria-modal="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="defaultModalLabel">Tambah Item Penawaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <form action="<?= base_url('agency/tambah_item_penawaran') ?>" enctype="multipart/form-data" method="post">
            <div class="modal-body">
               <div class="form-group">
                  <label for="form-label">Nama Penawaran</label>
                  <input type="text" class="form-control" name="nama_penawaran" id="nama_penawaran">
               </div>
               <div class="form-group">
                  <label for="form-label">Harga Penawaran</label>
                  <input type="text" class="form-control uang" name="harga_penawaran" id="harga_penawaran">
               </div>
               <div class="form-group">
                  <label for="form-label">Jenis Item</label>
                  <select name="jenis_item" id="jenis_item" class="form-control">
                     <option value=""> ::Pilih Jenis Item :: </option>
                     <option value="1">Biaya Tetap</option>
                     <option value="2">Biaya Tetap (Additional)</option>
                     <option value="3">Biaya Tambahan</option>
                  </select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn mb-2 btn-primary btn-simpan">Simpan</button>
            </div>
         </form>
      </div>
   </div>
</div>