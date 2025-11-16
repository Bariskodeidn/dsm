<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Harga Jual</strong></p>
        </div>
        <div class="card-body">

          <?php
          $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();
          $penunjukan = $this->db->select('a.jenis, a.customer,a.Id')->from('t_penunjukan a')->where('Id', $pda['penunjukan'])->get()->row_array();
          $item_pda = $this->db->select('*')->from('t_item_pda')->where('jenis', $penunjukan['jenis'])->where('port', $port['kode'])->where('title', 'AGENCY REMUNERATION')->get()->result_array();
          $item_pda_desc = $this->db->select('*')->from('t_item_pda')->where('jenis', $penunjukan['jenis'])->where('port', $port['kode'])->where('title', 'DESC')->get()->result_array();
          $data_invoice = $this->db->get_where('t_invoice', ['penunjukan' => $penunjukan['Id'], 'jenis' => 2])->row_array();
          ?>
          <div class="mb-4">
            <a href="<?= base_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
            <!-- <?php if ($pda['harga_jual'] != null) { ?>
              <a href="<?= base_url('pda/view_hargajual/') . $pda['Id'] ?>" class="btn btn-success btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> HARGA JUAL</a>
            <?php } ?> -->

            <?php
            if ($data_invoice) {
            ?>
              <a href="<?= base_url('invoice/ubah/') . $data_invoice['Id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Update Invoice</a>
            <?php }

            if ($pda['harga_jual'] != null and !$data_invoice) { ?>
              <a href="<?= base_url('pda/invoice/') . $pda['Id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Create Invoice</a>
            <?php } ?>
          </div>



          <?php
          if ($pda['harga_jual'] == null) {
          ?>
            <form action="<?= base_url('pda/insert_hargajual/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
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
                  $hpp = json_decode($pda['hpp_rill']);
                  $agency_remuneration = $hpp->agency_remuneration;
                  $desc = $hpp->desc;
                  $other = $hpp->other;
                  if ($other->desc != "") {
                    $other_desc = $other->desc;
                  } else {
                    $other_desc = [""];
                  }
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
                        <input type="text" class="form-control uang" name="grt[]" id="grt" value="<?= $desc->grt[$key] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="tarif[]" id="tarif" value="<?= $desc->tarif[$key] ?>">
                      </td>
                      <td width="30px">
                        <input type="text" class="form-control uang" name="activity[]" id="activity" value="<?= $desc->activity[$key] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="amount-desc[]" id="amount-desc" value="<?= $desc->amount_desc[$key] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control" name="remark-desc[]" id="remark-desc" value="<?= $desc->remark_desc[$key] ?>">
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <table class="table" id="table-item-pda">
                <thead class="thead-dark">
                  <tr>
                    <th>
                      AGENCY REMUNERATION
                    </th>
                    <th>
                      AMOUNT(IDR)
                    </th>
                    <th>
                      QTY
                    </th>
                    <th>
                      Tanggal Mulai
                    </th>
                    <th>Tanggal Selesai</th>
                    <th>Remark</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($agency_remuneration->desc as $k => $data) {
                    $this->db->select('Id,desc,hpp_rill');
                    $item_pda_list = $this->db->get_where('t_item_pda', ['Id' => $data])->row_array();
                    $this->db->select('harga');
                    $harga_jual = $this->db->get_where('t_harga_jual', ['item_pda' => $data, 'customer' => $penunjukan['customer']])->row_array();
                  ?>
                    <tr class="tr_clone">
                      <td>
                        <div class="input-select">
                          <input type="hidden" name="desc[]" id="desc" value="<?= $item_pda_list['Id'] ?>">
                          <!-- <input type="text" class="form-control" value="<?= $item_pda_list['desc'] ?>"> -->
                          <span><?= $item_pda_list['desc'] ?></span>
                        </div>
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="amount[]" id="amount" value="<?= $harga_jual ? $harga_jual['harga'] : 0 ?>">
                      </td>
                      <td width="70px">
                        <input type="text" class="form-control uang" name="qty[]" id="qty" value="<?= $agency_remuneration->qty[$k] ?>">
                      </td>
                      <td>
                        <input type="date" class="form-control" name="mulai[]" id="mulai" value="<?= $agency_remuneration->tanggal_mulai[$k] ?>">
                      </td>
                      <td>
                        <input type="date" class="form-control" name="selesai[]" id="selesai" value="<?= $agency_remuneration->tanggal_selesai[$k] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control" name="remark[]" id="remark" value="<?= $agency_remuneration->remark[$k] ?>">
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <?php if ($other_desc[0] != "") {
              ?>
                <div class="table-responsive">
                  <table class="table" id="table-other" style="min-width: 1200px;">
                    <thead class="thead-dark">
                      <tr>
                        <th>OTHER/OWNER EXPENSE</th>
                        <th width="130px">AMOUNT (IDR)</th>
                        <th width="70px">QTY</th>
                        <th>TANGGAL MULAI</th>
                        <th>TANGGAL SELESAI</th>
                        <th>REMARK</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($other_desc as $index => $o) {
                      ?>
                        <tr class="baris-other">
                          <td>
                            <input type="hidden" name="desc-other[]" value="<?= $o ?>">
                            <span style="text-transform: uppercase;"><?= $o ?></span>
                            <!-- <textarea name="desc-other[]" id="desc-other-<?= $index ?>" class="form-control"><?= $o ?></textarea> -->
                          </td>
                          <td><input type="text" class="form-control uang" name="amount-other[]" id="amount-other-<?= $index ?>" value="<?= $other->amount[$index] ?>"></td>
                          <td><input type="text" class="form-control uang" name="qty-other[]" id="qty-other-<?= $index ?>" value="<?= $other->qty[$index] ?>"></td>
                          <td><input type="date" class="form-control" name="mulai-other[]" id="mulai-other-<?= $index ?>" value="<?= $other->tanggal_mulai[$index] ?>"></td>
                          <td><input type="date" class="form-control" name="selesai-other[]" id="selesai-other-<?= $index ?>" value="<?= $other->tanggal_selesai[$index] ?>"></td>
                          <td><input type="text" class="form-control" name="remark-other[]" id="remark-other-<?= $index ?>" value="<?= $other->remark[$index] ?>"></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              <?php } ?>
              <div style="margin-top: 25px;">
                <a href="<?= base_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm btn-simpan">Simpan</button>
              </div>
            </form>
          <?php } else { ?>
            <form action="<?= base_url('pda/insert_hargajual/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
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
                  $jual = json_decode($pda['harga_jual']);
                  $agency_remuneration = $jual->agency_remuneration;
                  $desc = $jual->desc;
                  if (isset($jual->other)) {
                    $other = $jual->other;
                    if ($other->desc != "") {
                      $other_desc = $other->desc;
                    } else {
                      $other_desc = [""];
                    }
                  } else {
                    $other_desc = [""];
                  }

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
                        <input type="text" class="form-control uang" name="grt[]" id="grt" value="<?= $desc->grt[$key] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="tarif[]" id="tarif" value="<?= $desc->tarif[$key] ?>">
                      </td>
                      <td width="30px">
                        <input type="text" class="form-control uang" name="activity[]" id="activity" value="<?= $desc->activity[$key] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="amount-desc[]" id="amount-desc" value="<?= $desc->amount_desc[$key] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control" name="remark-desc[]" id="remark-desc" value="<?= $desc->remark_desc[$key] ?>">
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <table class="table" id="table-item-pda">
                <thead class="thead-dark">
                  <tr>
                    <th>
                      AGENCY REMUNERATION
                    </th>
                    <th>
                      AMOUNT(IDR)
                    </th>
                    <th>
                      QTY
                    </th>
                    <th>
                      Tanggal Mulai
                    </th>
                    <th>Tanggal Selesai</th>
                    <th>Remark</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($agency_remuneration->desc as $k => $data) {
                    $this->db->select('Id,desc');
                    $item_pda_list = $this->db->get_where('t_item_pda', ['Id' => $data])->row_array();
                  ?>
                    <tr class="tr_clone">
                      <td>
                        <div class="input-select">
                          <input type="hidden" name="desc[]" id="desc" value="<?= $item_pda_list['Id'] ?>">
                          <a href="<?= base_url('pda/kwitansi/') . $this->uri->segment(3) . '/' . $data ?>" target="_blank"><?= $item_pda_list['desc'] ?></a>
                        </div>
                      </td>
                      <td>
                        <input type="text" class="form-control uang" name="amount[]" id="amount" value="<?= $agency_remuneration->amount[$k] ?>">
                      </td>
                      <td width="70px">
                        <input type="text" class="form-control uang" name="qty[]" id="qty" value="<?= $agency_remuneration->qty[$k] ?>">
                      </td>
                      <td>
                        <input type="date" class="form-control" name="mulai[]" id="mulai" value="<?= $agency_remuneration->tanggal_mulai[$k] ?>">
                      </td>
                      <td>
                        <input type="date" class="form-control" name="selesai[]" id="selesai" value="<?= $agency_remuneration->tanggal_selesai[$k] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control" name="remark[]" id="remark" value="<?= $agency_remuneration->remark[$k] ?>">
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <?php if ($other_desc[0] != "") {
              ?>
                <div class="table-responsive">
                  <table class="table" id="table-other" style="min-width: 1200px;">
                    <thead class="thead-dark">
                      <tr>
                        <th>OTHER/OWNER EXPENSE</th>
                        <th width="130px">AMOUNT (IDR)</th>
                        <th width="70px">QTY</th>
                        <th>TANGGAL MULAI</th>
                        <th>TANGGAL SELESAI</th>
                        <th>REMARK</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($other_desc as $index => $o) {
                      ?>
                        <tr class="baris-other">
                          <td>
                            <input type="hidden" name="desc-other[]" id="desc-other-<?= $index ?>" value="<?= $o ?>">
                            <span style="text-transform: uppercase;"><?= $o ?></span>
                            <!-- <textarea name="desc-other[]" id="desc-other-<?= $index ?>" class="form-control"><?= $o ?></textarea> -->
                          </td>
                          <td><input type="text" class="form-control uang" name="amount-other[]" id="amount-other-<?= $index ?>" value="<?= $other->amount[$index] ?>"></td>
                          <td><input type="text" class="form-control uang" name="qty-other[]" id="qty-other-<?= $index ?>" value="<?= $other->qty[$index] ?>"></td>
                          <td><input type="date" class="form-control" name="mulai-other[]" id="mulai-other-<?= $index ?>" value="<?= $other->tanggal_mulai[$index] ?>"></td>
                          <td><input type="date" class="form-control" name="selesai-other[]" id="selesai-other-<?= $index ?>" value="<?= $other->tanggal_selesai[$index] ?>"></td>
                          <td><input type="text" class="form-control" name="remark-other[]" id="remark-other-<?= $index ?>" value="<?= $other->remark[$index] ?>"></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              <?php } ?>
              <div style="margin-top: 25px;">
                <a href="<?= base_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm btn-simpan">Simpan</button>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->