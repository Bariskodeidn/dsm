<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Create ER</strong></p>
        </div>
        <div class="card-body">
          <div class="container" style="margin-top: 20px;">
            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <i class="glyphicon glyphicon-ok-sign"></i> <?= $this->session->flashdata('success'); ?>
              </div>
            <?php endif;
            unset($_SESSION['success']) ?>

            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <i class="glyphicon glyphicon-error"></i> <?= $this->session->flashdata('error'); ?>
              </div>
            <?php endif;
            unset($_SESSION['error']);
            ?>

            <div class="container" style="margin-top: 20px;">
              <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1"><i class="fe fe-search"></i></span>
                    </div>
                    <input type="text" id="globalSearch" class="form-control input-lg" placeholder="Cari item, remark, atau nominal di semua kategori...">
                  </div>
                </div>
              </div>

              <form action="<?= base_url('pda/request_expense/' . $order->Id) ?>" method="POST" id="formRequest">

                <div class="panel panel-default">
                  <div class="panel-heading p-2" style="display:flex; justify-content:space-between; align-items:center;">
                    <strong>I. DESKRIPSI KEGIATAN</strong>
                    <button type="button" class="btn btn-xs btn-success select-all" data-target="selected_desc">
                      <i class="fe fe-check-circle"></i> Select All
                    </button>
                  </div>
                  <table class="table table-hover table-bordered table-condensed category-table">
                    <thead class="thead-dark">
                      <tr class="active">
                        <th width="30 text-center">Pilih</th>
                        <th>Description</th>
                        <th>Keterangan / Remark</th>
                        <th>GRT</th>
                        <th>Tarif</th>
                        <th>Activity</th>
                        <th class="text-right">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($hpp['desc']['id_desc'] as $k => $v):
                        $item = $this->db->get_where('t_item_pda', ['Id' => $v])->row_array();
                      ?>
                        <?php $is_locked = in_array($k, $locked['desc']); ?>
                        <tr class="<?= $is_locked ? 'bg-danger text-muted' : '' ?>">
                          <td class="text-center">
                            <input type="checkbox" name="selected_desc[]" value="<?= $k ?>" <?= $is_locked ? 'disabled checked' : '' ?> class="selected_desc">
                          </td>
                          <td><?= $item['desc'] ?></td>
                          <td>
                            <?= $hpp['desc']['remarks'][$k] ?>
                            <?php if ($is_locked): ?> <span class="label bg-secondary p-1 pull-right text-white">Sudah diajukan</span> <?php endif; ?>
                          </td>
                          <td><?= $hpp['desc']['grt'][$k] ?></td>
                          <td><?= $hpp['desc']['tarif'][$k] ?></td>
                          <td><?= $hpp['desc']['activity'][$k] ?></td>
                          <td class="text-right"><?= number_format((float)str_replace('.', '', $hpp['desc']['amount_desc'][$k]), 0, ',', '.') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading p-2" style="display:flex; justify-content:space-between; align-items:center;">
                    <strong>II. AGENCY REMUNERATION</strong>
                    <button type="button" class="btn btn-xs btn-success select-all" data-target="selected_agency">
                      <i class="fe fe-check-circle"></i> Select All
                    </button>
                  </div>
                  <table class="table table-hover table-bordered table-condensed category-table">
                    <thead class="thead-dark">
                      <tr class="active">
                        <th width="30" class="text-center">Pilih</th>
                        <th>Desc</th>
                        <th>Keterangan</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($hpp['agency_remuneration']['desc'] as $k => $v):
                        $item = $this->db->get_where('t_item_pda', ['Id' => $v])->row_array();
                      ?>
                        <?php $is_locked = in_array($k, $locked['agency']); ?>
                        <tr class="<?= $is_locked ? 'bg-danger text-muted' : '' ?>">
                          <td class="text-center">
                            <input type="checkbox" name="selected_agency[]" class="check-item selected_agency" value="<?= $k ?>" <?= $is_locked ? 'disabled checked' : '' ?>>
                          </td>
                          <td><?= $item['desc'] ?></td>
                          <td>
                            <?php if ($is_locked): ?> <span class="label label-default pull-right">Sudah diajukan</span> <?php endif; ?>
                          </td>
                          <td class="text-center"><?= $hpp['agency_remuneration']['qty'][$k] ?></td>
                          <td class="text-right"><?= number_format((float)str_replace('.', '', $hpp['agency_remuneration']['amount'][$k]), 0, ',', '.') ?></td>
                          <td class="text-right"><?= number_format((float)str_replace('.', '', $hpp['agency_remuneration']['amount'][$k]) * (int)str_replace('.', '', $hpp['agency_remuneration']['qty'][$k]), 0, ',', '.');  ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <?php if (!empty($hpp['other']['desc']) && $hpp['other']['desc'][0] != ""): ?>
                  <div class="panel panel-default">
                    <div class="panel-heading p-2" style="display:flex; justify-content:space-between; align-items:center;">
                      <strong>III. OTHER EXPENSES</strong>
                      <button type="button" class="btn btn-xs btn-success select-all" data-target="selected_other">
                        <i class="fe fe-check-circle"></i> Select All
                      </button>
                    </div>
                    <table class="table table-hover table-bordered table-condensed category-table">
                      <thead>
                        <tr class="active">
                          <th width="30" class="text-center">Pilih</th>
                          <th>Desc</th>
                          <th>Keterangan</th>
                          <th>Qty</th>
                          <th class="text-right">Amount</th>
                          <th class="text-right">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($hpp['other']['desc'] as $k => $v): ?>
                          <?php
                          $is_locked = in_array($k, $locked['other']);
                          ?>
                          <tr class="<?= $is_locked ? 'bg-danger text-muted' : '' ?>">
                            <td class="text-center">
                              <input type="checkbox" name="selected_other[]" class="check-item selected_other" value="<?= $k ?>" <?= $is_locked ? 'disabled checked' : '' ?>>
                            </td>
                            <td><?= $hpp['other']['desc'][$k] ?></td>
                            <td>
                              <?= $hpp['other']['remark'][$k] ?>
                              <?php if ($is_locked): ?> <span class="label label-default pull-right">Sudah diajukan</span> <?php endif; ?>
                            </td>
                            <td><?= $hpp['other']['qty'][$k] ?></td>
                            <td class="text-right"><?= number_format((float)str_replace('.', '', $hpp['other']['amount'][$k]), 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format((float)str_replace('.', '', $hpp['other']['amount'][$k]) * (int)str_replace('.', '', $hpp['other']['qty'][$k]), 0, ',', '.') ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-sm btn-block shadow-lg" style="margin-bottom: 50px;">
                  <i class="fe fe-send"></i> KIRIM PENGAJUAN
                </button>
              </form>
            </div>
          </div>

          <hr>
          <div class="row">
            <div class="col-md-12">
              <!-- <a href="<?= base_url('pda/export_all_approved/' . $order->Id) ?>" class="btn btn-success btn-sm" style="margin:10px;">Export All Approve</a> -->
              <div>
                <div class="p-2">
                  <h3 class="panel-title"><i class="fe fe-clock"></i> RIWAYAT PENGAJUAN ANDA</h3>
                </div>
                <div class="panel-body">
                  <?php if (empty($history)): ?>
                    <div class="text-center text-muted py-4">
                      <p>Belum ada riwayat pengajuan untuk order ini.</p>
                    </div>
                  <?php else: ?>
                    <table class="table table-hover">
                      <thead class="thead-dark">
                        <tr class="active">
                          <th>ID Pengajuan</th>
                          <th>Tanggal</th>
                          <th class="text-right">Total Nominal</th>
                          <th class="text-center">Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($history as $h): ?>
                          <tr>
                            <td>
                              <a href="javascript:void(0)" class="btn-detail-view"
                                data-id="<?= $h['request_id'] ?>"
                                data-items='<?= json_encode($h['items']) ?>'>
                                <strong><?= $h['request_id'] ?></strong>
                              </a>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($h['submitted_at'])) ?></td>
                            <td class="text-right">Rp <?= number_format($h['subtotal'], 0, ',', '.') ?></td>
                            <td class="text-center">
                              <?php
                              // Penentuan label posisi
                              if ($h['status'] == 'PENDING') echo '<span class="text-warning"> Manager</span>';
                              elseif ($h['status'] == 'WAITING FINANCE') echo '<span class="text-info">Finance</span>';
                              elseif ($h['status'] == 'WAITING DIRECTOR') echo '<span class="text-primary">Director</span>';
                              elseif ($h['status'] == 'FINAL APPROVED') echo '<span class="text-success">Siap Ditransfer (Finance)</span>';
                              elseif ($h['status'] == 'PAID') echo '<span class="label label-success">LUNAS / TERBAYAR</span>';
                              ?>
                            </td>
                            <td>
                              <?php if ($h['status'] == 'FINAL APPROVED' or $h['status'] == 'PAID'): ?>
                                <a href="<?= base_url('pda/er_pdf/' . $order->Id . '/' . $h['request_id']) ?>" target="_blank" class="btn btn-xs btn-secondary">
                                  <i class="fe fe-printer"></i> Cetak
                                </a>
                              <?php else: ?>
                                <span class="text-muted small">N/A</span>
                              <?php endif; ?>

                              <?php if ($h['status'] == 'PAID'): ?>
                                <?php if (!isset($h['settlement'])): ?>
                                  <button type="button" class="btn btn-xs btn-primary btn-upload-settle"
                                    data-rid="<?= $h['request_id'] ?>"
                                    data-oid="<?= $order->Id ?>"
                                    data-nominal="<?= $h['subtotal'] ?>">
                                    <i class="fe fe-upload"></i> Upload Nota
                                  </button>
                                <?php else: ?>
                                  <span class="badge bg-success text-white">Sudah Lapor: <?= $h['settlement']['status'] ?></span>
                                <?php endif; ?>
                              <?php else: ?>
                                <span class="text-muted">Menunggu Pembayaran</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" style="color:white">
          <i class="fe fe-file-text"></i> Detail Pengajuan: <span id="view-id"></span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1">&times;</button>
      </div>
      <div class="modal-body">
        <div id="view-content">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalUploadSettle" tabindex="-1">
  <div class="modal-dialog">
    <form action="<?= base_url('pda/submit_settlement') ?>" method="POST" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" style="color:white;">Form Pertanggungjawaban Nota</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="up_oid">
          <input type="hidden" name="request_id" id="up_rid">

          <div class="alert alert-info">
            ID Request: <strong id="display_rid"></strong><br>
            Uang Diterima: <strong id="display_nominal"></strong>
          </div>

          <div class="form-group">
            <label>Total Terpakai (Sesuai Nota Riil)</label>
            <input type="text" name="actual_amount" class="form-control amount" placeholder="Masukkan total nominal di nota">
            <small class="error" id="actual_amount_error" style="color:red"></small>
          </div>

          <div class="form-group">
            <label>Foto/Scan Nota (JPG/PNG/PDF)</label>
            <input type="file" name="nota_file" class="form-control">
            <small class="error" id="nota_file_error" style="color:red"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-simpan">Kirim Pertanggungjawaban</button>
        </div>
      </div>
    </form>
  </div>
</div>