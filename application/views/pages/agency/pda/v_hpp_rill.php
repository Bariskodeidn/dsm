<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Hpp Rill</strong></p>
        </div>
        <div class="card-body">

          <div class="mb-4">
            <a href="<?= base_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
            <?php if ($pda['hpp_rill'] != null) { ?>
              <a href="<?= base_url('pda/view_hpprill_excel/') . $pda['Id'] ?>" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i> HPP RILL EXCEL</a>
              <?php if ($pda['er'] != null) { ?>
                <a href="<?= base_url('pda/er_excel/') . $pda['Id'] ?>" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i> ER EXCEL</a>
                <a href="<?= base_url('pda/update_er/') . $pda['Id'] ?>" class="btn btn-primary btn-sm">Update ER</a>
              <?php } else { ?>
                <a href="<?= base_url('pda/create_er/') . $pda['Id'] ?>" class="btn btn-primary btn-sm">Create ER</a>
            <?php }
            } ?>
          </div>
          <?php
          if ($pda['hpp_rill'] == null) {
          ?>
            <form action="<?= base_url('pda/insert_hpprill/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
              <div class="table-responsive">
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
              </div>
              <div class="table-responsive">
                <table class="table" id="table-item-pda" style="min-width: 1500px; table-layout: fixed; width: 100%;">
                  <thead class="thead-dark">
                    <tr>
                      <th style="width: 400px !important;">AGENCY REMUNERATION</th>
                      <th>AMOUNT (IDR)</th>
                      <th style="width: 80px !important;">QTY</th>
                      <th>TANGGAL MULAI</th>
                      <th>TANGGAL SELESAI</th>
                      <th>REMARK</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;
                    foreach ($agency_remuneration->desc as $k => $data) {
                      if ($data != "") {
                        $this->db->select('Id,desc,hpp_rill');
                        $item_pda_list = $this->db->get_where('t_item_pda', ['Id' => $data])->row_array();
                    ?>
                        <tr class="tr_clone">
                          <td>
                            <div class="input-select">
                              <select name="desc[]" id="desc-<?= $item_pda_list['Id'] ?>" class="form-control items">
                                <option value="<?= $item_pda_list['Id'] ?>"><?= $item_pda_list['desc'] ?></option>
                              </select>
                            </div>
                          </td>
                          <td width="150px">
                            <input type="text" class="form-control uang" name="amount[]" id="amount-<?= $k ?>" value="<?= $item_pda_list['hpp_rill'] ?>">
                          </td>
                          <td width="70px">
                            <input type="text" class="form-control uang" name="qty[]" id="qty-<?= $k ?>" value="<?= $agency_remuneration->qty[$k] ?>">
                          </td>
                          <td>
                            <input type="date" class="form-control" name="mulai[]" id="mulai-<?= $k ?>">
                          </td>
                          <td>
                            <input type="date" class="form-control" name="selesai[]" id="selesai-<?= $k ?>">
                          </td>
                          <td>
                            <input type="text" class="form-control" name="remark[]" id="remark-<?= $k ?>" value="<?= $agency_remuneration->remark[$k] ?>">
                          </td>
                          <td>
                            <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                            <button type="button" class="btn btn-success btn-sm add-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">
                <table class="table" id="table-other" style="min-width: 1200px;">
                  <thead>
                    <tr>
                      <th>OTHER/OWNER EXPENSE</th>
                      <th width="130px">AMOUNT (IDR)</th>
                      <th width="70px">QTY</th>
                      <th>TANGGAL MULAI</th>
                      <th>TANGGAL SELESAI</th>
                      <th>REMARK</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="baris-other">
                      <td><textarea name="desc-other[]" id="desc-other" class="form-control"></textarea></td>
                      <td><input type="text" class="form-control uang" name="amount-other[]" id="amount-other" value="0"></td>
                      <td><input type="text" class="form-control uang" name="qty-other[]" id="qty-other" value="1"></td>
                      <td><input type="date" class="form-control" name="mulai-other[]" id="mulai-other"></td>
                      <td><input type="date" class="form-control" name="selesai-other[]" id="selesai-other"></td>
                      <td><input type="text" class="form-control" name="remark-other[]" id="remark-other"></td>
                      <td>
                        <button type="button" class="btn btn-danger btn-xs hapusRowOther"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-success btn-xs add-row-other"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row" style="margin-top: 25px;">
                <a href="<?= base_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm btn-simpan">Simpan</button>
              </div>
            </form>
          <?php } else { ?>
            <form action="<?= base_url('pda/insert_hpprill/') . $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data">
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
              <div class="table-responsive">
                <table class="table" id="table-item-pda" style="min-width: 1500px;table-layout: fixed; width: 100%;">
                  <thead class="thead-dark">
                    <tr>
                      <th style="width: 400px;">AGENCY REMUNERATION</th>
                      <th>AMOUNT (IDR)</th>
                      <th style="width: 80px;">QTY</th>
                      <th>TANGGAL MULAI</th>
                      <th>TANGGAL SELESAI</th>
                      <th width=" 200px">REMARK</th>
                      <th>#</th>
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
                            <!-- <input type="hidden" name="desc[]" id="desc" value="<?= $item_pda_list['Id'] ?>">
                                <span><?= $item_pda_list['desc'] ?></span> -->
                            <div class="input-select">
                              <select name="desc[]" id="desc-<?= $k ?>" class="form-control items">
                                <option value="<?= $item_pda_list['Id'] ?>"><?= $item_pda_list['desc'] ?></option>
                              </select>
                            </div>
                          </div>
                        </td>
                        <td>
                          <input type="text" class="form-control uang" name="amount[]" id="amount-<?= $k ?>" value="<?= $agency_remuneration->amount[$k] ?>">
                        </td>
                        <td width="70px">
                          <input type="text" class="form-control uang" name="qty[]" id="qty-<?= $k ?>" value="<?= $agency_remuneration->qty[$k] ?>">
                        </td>
                        <td>
                          <input type="date" class="form-control" name="mulai[]" id="mulai-<?= $k ?>" value="<?= $agency_remuneration->tanggal_mulai[$k] ?>">
                        </td>
                        <td>
                          <input type="date" class="form-control" name="selesai[]" id="selesai-<?= $k ?>" value="<?= $agency_remuneration->tanggal_selesai[$k] ?>">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="remark[]" id="remark-<?= $k ?>" value="<?= $agency_remuneration->remark[$k] ?>">
                        </td>
                        <td>
                          <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                          <button type="button" class="btn btn-success btn-sm add-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <?php
              if ($other_desc[0] != "") {
              ?>
                <table class="table" id="table-other">
                  <thead>
                    <tr>
                      <th>OTHER/OWNER EXPENSE</th>
                      <th width="130px">AMOUNT (IDR)</th>
                      <th width="70px">QTY</th>
                      <th>TANGGAL MULAI</th>
                      <th>TANGGAL SELESAI</th>
                      <th>REMARK</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($other_desc as $index => $o) {
                    ?>
                      <tr class="baris-other">
                        <td><textarea name="desc-other[]" id="desc-other-<?= $index ?>" class="form-control"><?= $o ?></textarea></td>
                        <td><input type="text" class="form-control uang" name="amount-other[]" id="amount-other-<?= $index ?>" value="<?= $other->amount[$index] ?>"></td>
                        <td><input type="text" class="form-control uang" name="qty-other[]" id="qty-other-<?= $index ?>" value="<?= $other->qty[$index] ?>"></td>
                        <td><input type="date" class="form-control" name="mulai-other[]" id="mulai-other-<?= $index ?>" value="<?= $other->tanggal_mulai[$index] ?>"></td>
                        <td><input type="date" class="form-control" name="selesai-other[]" id="selesai-other-<?= $index ?>" value="<?= $other->tanggal_selesai[$index] ?>"></td>
                        <td><input type="text" class="form-control" name="remark-other[]" id="remark-other-<?= $index ?>" value="<?= $other->remark[$index] ?>"></td>
                        <td>
                          <button type="button" class="btn btn-danger btn-xs hapusRowOther"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-success btn-xs add-row-other"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              <?php } else { ?>
                <div class="table-responsive">
                  <table class="table" id="table-other" style="min-width: 1200px;">
                    <thead>
                      <tr>
                        <th>OTHER/OWNER EXPENSE</th>
                        <th width="130px">AMOUNT (IDR)</th>
                        <th width="70px">QTY</th>
                        <th>TANGGAL MULAI</th>
                        <th>TANGGAL SELESAI</th>
                        <th>REMARK</th>
                        <th>#</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="baris-other">
                        <td><textarea name="desc-other[]" id="desc-other" class="form-control"></textarea></td>
                        <td><input type="text" class="form-control uang" name="amount-other[]" id="amount-other" value="0"></td>
                        <td><input type="text" class="form-control uang" name="qty-other[]" id="qty-other" value="1"></td>
                        <td><input type="date" class="form-control" name="mulai-other[]" id="mulai-other"></td>
                        <td><input type="date" class="form-control" name="selesai-other[]" id="selesai-other"></td>
                        <td><input type="text" class="form-control" name="remark-other[]" id="remark-other"></td>
                        <td>
                          <button type="button" class="btn btn-danger btn-xs hapusRowOther"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-success btn-xs add-row-other"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <?php } ?>
              <div class="row" style="margin-top: 25px;">
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