<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Update ER</strong></p>
        </div>
        <div class="card-body">
          <?php
          $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();
          $penunjukan = $this->db->select('a.jenis')->from('t_penunjukan a')->where('Id', $pda['penunjukan'])->get()->row_array();
          $item_pda = $this->db->select('*')->from('t_item_pda')->where('jenis', $penunjukan['jenis'])->where('port', $port['kode'])->where('title', 'AGENCY REMUNERATION')->get()->result_array();
          $item_pda_desc = $this->db->select('*')->from('t_item_pda')->where('jenis', $penunjukan['jenis'])->where('port', $port['kode'])->where('title', 'DESC')->get()->result_array()
          ?>
          <div class="mb-4">
            <a href="<?= base_url('pda/hpp_rill/') . $this->uri->segment(3) ?>" class="btn btn-warning btn-sm">Kembali</a>
          </div>

          <form action="<?= base_url('pda/insert_er/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
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
                $er = json_decode($pda['er']);
                $agency_remuneration = $er->agency_remuneration;
                $desc = $er->desc;
                ?>
                <?php
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
                      <input type="hidden" name="remarks_desc[]" id="remarks" value="<?= $item_desc['remarks'] ?>">
                      <span style="text-transform: uppercase;"><?= $item_desc['remarks'] ?></span>
                    </td>
                    <td width="90px">
                      <input type="text" class="form-control uang" name="grt_desc[]" id="grt" value="<?= $desc->grt[$key] ?>">
                    </td>
                    <td>
                      <input type="text" class="form-control uang" name="tarif_desc[]" id="tarif" value="<?= $desc->tarif[$key] ?>">
                    </td>
                    <td width="30px">
                      <input type="text" class="form-control uang" name="activity_desc[]" id="activity" value="<?= $desc->activity[$key] ?>">
                    </td>
                    <td>
                      <input type="text" class="form-control uang" name="amount_desc[]" id="amount-desc" value="<?= $desc->amount_desc[$key] ?>">
                    </td>
                    <td>
                      <input type="text" class="form-control" name="remark_desc[]" id="remark-desc" value="<?= $desc->remark_desc[$key] ?>">
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lkk_in" class="form-label">Request LKK IN</label>
                  <input type="text" name="lkk_in" id="lkk_in" class="form-control uang" value="<?= $pda['lkk_in'] ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lkk_out" class="form-label">Request LKK OUT</label>
                  <input type="text" name="lkk_out" id="lkk_out" class="form-control uang" value="<?= $pda['lkk_out'] ?>">
                </div>
              </div>
            </div>
            <table class="table" id="table-item-pda">
              <thead class="thead-dark">
                <tr>
                  <th>AGENCY REMUNERATION</th>
                  <th>ER</th>
                  <th width="30%">REMARK</th>
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
                        <input type="hidden" class="form-control uang" name="amount[]" id="amount" value="<?= $agency_remuneration->amount[$k] ?>">
                        <input type="hidden" class="form-control uang" name="qty[]" id="qty" value="<?= $agency_remuneration->qty[$k] ?>">
                        <input type="hidden" class="form-control" name="mulai[]" id="mulai" value="<?= $agency_remuneration->tanggal_mulai[$k] ?>">
                        <input type="hidden" class="form-control" name="selesai[]" id="selesai" value="<?= $agency_remuneration->tanggal_selesai[$k] ?>">
                        <span><?= $item_pda_list['desc'] ?></span>
                      </div>
                    </td>
                    <td>
                      <!-- <input type="checkbox" name="er[]" class="form-control"> -->
                      <select name="er[]" id="er<?= $k ?>" class="form-control">
                        <option value="1" <?= $agency_remuneration->er[$k] == '1' ? 'selected' : '' ?>>Ya</option>
                        <option value="2" <?= $agency_remuneration->er[$k] == '2' ? 'selected' : '' ?>>Tidak</option>
                      </select>
                    </td>
                    <td>
                      <textarea name="remark[]" id="remark" class="form-control"><?= $agency_remuneration->remark[$k] ?></textarea>
                      <!-- <input type="text" class="form-control" name="remark[]" id="remark" value="<?= $agency_remuneration->remark[$k] ?>"> -->
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <div style="margin-top: 25px;">
              <a href="<?= base_url('pda/hpp_rill/') . $this->uri->segment(3) ?>" class="btn btn-warning btn-sm">Kembali</a>
              <button type="submit" class="btn btn-primary btn-sm btn-submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->