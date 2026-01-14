<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Pra PDA</strong></p>
        </div>
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <a href="<?= site_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
              <a href="<?= site_url('pda/view_prapda_excel/') . $pda['Id'] ?>" class="btn btn-success btn-sm"><i class="fe fe-file"></i> PRA PDA EXCEL</a>
            </div>
          </div>

          <?php
          $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();
          $penunjukan = $this->db->select('a.jenis,a.no_surat,b.nama_customer,c.name as nama_kapal')->from('t_penunjukan a')->join('agency_customer b', 'b.Id = a.customer', 'left')->join('agency_kapal c', 'a.nama_kapal = c.Id', 'left')->where('a.Id', $pda['penunjukan'])->get()->row_array();
          $item_pda = $this->db->select('*')->from('t_item_pda')->where('jenis', $penunjukan['jenis'])->where('port', $port['kode'])->where('title', 'AGENCY REMUNERATION')->get()->result_array();
          $item_pda_desc = $this->db->select('*')->from('t_item_pda')->where('jenis', $penunjukan['jenis'])->where('port', $port['kode'])->where('title', 'DESC')->get()->result_array();
          ?>

          <table class="table">
            <tr>
              <th width="250px">No. Penunjukan</th>
              <td width="5px">:</td>
              <td><?= $penunjukan['no_surat'] ?></td>
            </tr>
            <tr>
              <th width="250px">Principal</th>
              <td width="5px">:</td>
              <td><?= $penunjukan['nama_customer'] ?></td>
            </tr>
            <tr>
              <th width="250px">Nama Kapal</th>
              <td width="5px">:</td>
              <td><?= $penunjukan['nama_kapal'] ?></td>
            </tr>
            <tr>
              <th width="250px">ETA</th>
              <td width="5px">:</td>
              <td><?= date('d-m-Y', strtotime($pda['eta'])) ?></td>
            </tr>
            <tr>
              <th width="250px">Port</th>
              <td width="5px">:</td>
              <td><?= $port['nama'] . ' (' . $port['kode'] . ')' ?></td>
            </tr>
            <tr>
              <th width="250px">EST GRT</th>
              <td width="5px">:</td>
              <td><?= number_format($pda['grt'] + $pda['grt_barge']) . " (" . number_format($pda['grt']) . " + " . number_format($pda['grt_barge']) . ")" ?></td>
            </tr>
          </table>


          <?php
          if ($pda['est'] == null) {
          ?>
            <form action="<?= base_url('pda/insert_est/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
              <table class="table table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th width="400px">DESCRIPTION</th>
                    <th>REMARKS</th>
                    <th>GRT</th>
                    <th>TARIF</th>
                    <th>ACTIVITY</th>
                    <th>AMOUNT</th>
                    <th>REMARK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($item_pda_desc as $val) { ?>
                    <tr>
                      <td>
                        <input type="hidden" name="id_desc[]" id="id_desc" value="<?= $val['Id'] ?>">
                        <span style="text-transform: uppercase;"><?= $val['desc'] ?></span>
                      </td>
                      <td>
                        <input type="hidden" name="remarks[]" id="remarks" value="<?= $val['remarks'] ?>">
                        <span style="text-transform: uppercase;"><?= $val['remarks'] ?></span>
                      </td>
                      <td width="90px">
                        <input type="text" class="form-control uang" name="grt[]" id="grt">
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="tarif[]" id="tarif">
                      </td>
                      <td width="30px">
                        <input type="text" class="form-control uang" name="activity[]" id="activity">
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="amount-desc[]" id="amount-desc">
                      </td>
                      <td>
                        <input type="text" class="form-control" name="remark-desc[]" id="remark-desc">
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <table class="table table-striped" id="table-item-pda">
                <thead>
                  <tr>
                    <th width="400px">AGENCY REMUNERATION</th>
                    <th>AMOUNT (IDR)</th>
                    <th width="70px">QTY</th>
                    <th>REMARK</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="baris-agency">
                    <td>
                      <div class="input-select">
                        <select name="desc[]" id="desc" class="form-control items" tabindex="-1">
                          <option value=""> -- PILIH ITEM -- </option>
                          <?php foreach ($item_pda as $value) { ?>
                            <option value="<?= $value['Id'] ?>"><?= $value['desc'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </td>
                    <td>
                      <input type="text" class="form-control uang" name="amount[]" id="amount" value="0">
                    </td>
                    <td>
                      <input type="text" class="form-control" name="qty[]" id="qty">
                    </td>
                    <td>
                      <input type="text" class="form-control" name="remark[]" id="remark">
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-xs hapusRow"><i class="fa fa-trash"></i></button>
                      <button type="button" class="btn btn-success btn-xs add-row"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row" style="margin-top: 25px;">
                <a href="<?= base_url('pda') ?>" class="btn btn-warning btn-sm"><i class="fa fa-chevron-left" aria-hidden="true"></i> Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm btn-simpan">Simpan</button>
              </div>
            </form>
          <?php } else { ?>
            <form action="<?= base_url('pda/insert_est/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
              <table class="table table-bordered">
                <thead class="thead-dark">
                  <tr>
                    <th>DESCRIPTION</th>
                    <th>REMARKS</th>
                    <th>GRT</th>
                    <th>TARIF</th>
                    <th>ACTIVITY</th>
                    <th>AMOUNT</th>
                    <th>REMARK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $estimasi = json_decode($pda['est']);
                  $agency_remuneration = $estimasi->agency_remuneration;
                  $desc = $estimasi->desc;
                  foreach ($desc->id_desc as $key => $val) {
                    $this->db->select('desc, remarks');
                    $item_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
                  ?>
                    <tr>
                      <td>
                        <input type="hidden" name="id_desc[]" id="id_desc" value="<?= $val ?>">
                        <span style="text-transform: uppercase;"><?= $item_desc['desc'] ?></span>
                      </td>
                      <td>
                        <input type="hidden" name="remarks[]" id="remarks" value="<?= $item_desc['remarks'] ?>">
                        <span style="text-transform: uppercase;"><?= $item_desc['remarks'] ?></span>
                      </td>
                      <td width="90px">
                        <input type="text" class="form-control uang" name="grt[]" id="grt" value="<?= $desc->grt[$key] ?>" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="tarif[]" id="tarif" value="<?= $desc->tarif[$key] ?>" readonly>
                      </td>
                      <td width="30px">
                        <input type="text" class="form-control uang" name="activity[]" id="activity" value="<?= $desc->activity[$key] ?>" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="amount-desc[]" id="amount-desc" value="<?= $desc->amount_desc[$key] ?>" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control" name="remark-desc[]" id="remark-desc" value="<?= $desc->remark_desc[$key] ?>" readonly>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <table class="table" id="table-item-pda">
                <thead>
                  <tr>
                    <th width="400px">AGENCY REMUNERATION</th>
                    <th>AMOUNT (IDR)</th>
                    <th width="70px">QTY</th>
                    <th>REMARK</th>
                    <!-- <th>#</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($agency_remuneration->desc as $k => $data) {
                    if ($data != "") {
                      $this->db->select('Id,desc');
                      $item_pda_list = $this->db->get_where('t_item_pda', ['Id' => $data])->row_array();
                  ?>
                      <tr class="baris-agency">
                        <td>
                          <div class="input-select">
                            <span><?= $item_pda_list['desc'] ?></span>
                            <!-- <select name="desc[]" id="desc-<?= $i ?>" class="form-control items" tabindex="-1" readonly>
                                    <option value=""> -- PILIH ITEM -- </option>
                                    <?php foreach ($item_pda as $value) { ?>
                                      <option value="<?= $value['Id'] ?>" <?= $item_pda_list['Id'] == $value['Id'] ? 'selected' : '' ?>><?= $value['desc'] ?></option>
                                    <?php } ?>
                                  </select> -->
                          </div>
                        </td>
                        <td>
                          <input type="text" class="form-control uang" name="amount[]" id="amount-<?= $i ?>" value="<?= $agency_remuneration->amount[$k] ?>" readonly>
                        </td>
                        <td width="80px">
                          <input type="text" class="form-control uang" name="qty[]" id="qty=<?= $i ?>" value="<?= $agency_remuneration->qty[$k] ?>" readonly>
                        </td>
                        <td>
                          <input type="text" class="form-control" name="remark[]" id="remark-<?= $i ?>" value="<?= $agency_remuneration->remark[$k] ?>" readonly>
                        </td>
                        <!-- <td>
                                <button type="button" class="btn btn-danger btn-xs hapusRow"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-success btn-xs add-row"><i class="fa fa-plus" aria-hidden="true"></i></button>
                              </td> -->
                      </tr>
                    <?php
                      $i++;
                    } else { ?>
                      <tr class="baris-agency">
                        <td>
                          <div class="input-select">
                            <select name="desc[]" id="desc" class="form-control items" tabindex="-1">
                              <option value=""> -- PILIH ITEM -- </option>
                              <?php foreach ($item_pda as $value) { ?>
                                <option value="<?= $value['Id'] ?>"><?= $value['desc'] ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </td>
                        <td>
                          <input type="text" class="form-control uang" name="amount[]" id="amount" value="0">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="qty[]" id="qty">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="remark[]" id="remark">
                        </td>
                        <td>
                          <button type="button" class="btn btn-danger btn-xs hapusRow"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-success btn-xs add-row"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php } ?>
                </tbody>
              </table>
              <div style="margin-top: 25px;">
                <a href="<?= site_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm"> Kembali</a>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->