<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <!-- <h1 class="page-title">Detail Memo</h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <strong class="card-title">Digital Memo</strong>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 col-sm-2 col-2">
              <img src="<?= $utility['logo'] ?>" alt="" width="100%">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <p class="text-center">E-MEMO INTERN <br> No.
                <?php
                $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
                $bln = $array_bln[date('n', strtotime($memo->tanggal))];

                // echo sprintf("%03d", $memo->nomor_memo) . '/E-MEMO/' . $memo->kode_nama . '/' . $bln . '/' . date('Y', strtotime($memo->tanggal));
                echo sprintf("%03d", $memo->nomor_memo) . '/E-MEMO/' . $memo->bagian . '/' . $bln . '/' . date('Y', strtotime($memo->tanggal));
                ?>
                <hr />
              </p>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <table class="mx-auto">
                <tr>
                  <td style="width:30%"><strong>Dari</td>
                  <td> : &nbsp;</td>
                  <td><?php echo $memo->nama . " (" . $memo->nama_jabatan . ")"; ?></td>
                </tr>
                <tr>
                  <td valign="top"><strong>Kepada&nbsp;&nbsp;&nbsp;</td>
                  <td valign="top"> : &nbsp;</td>
                  <td>
                    <?php
                    $no = 0;
                    $string = substr($memo->nip_kpd, 0, -1);
                    $arr_kpd = explode(";", $string);
                    foreach ($arr_kpd as $data) :
                      $sql = "SELECT nama,nama_jabatan FROM users WHERE nip='$data';";
                      $query = $this->db->query($sql);
                      $result = $query->row();
                      echo $result->nama . " (" . $result->nama_jabatan . ")";
                      echo "</br>";
                      $no++;
                    endforeach;
                    ?></td>
                </tr>
                <tr>
                  <td valign="top"><strong>Tembusan&nbsp;&nbsp;&nbsp;</td>
                  <td valign="top"> : &nbsp;</td>
                  <td>
                    <?php
                    $no = 0;
                    if (!empty($memo->nip_cc)) {
                      $string = substr($memo->nip_cc, 0, -1);
                      $arr_kpd = explode(";", $string);
                      foreach ($arr_kpd as $data) :
                        $sql = "SELECT nama,nama_jabatan FROM users WHERE nip='$data';";
                        $query = $this->db->query($sql);
                        $result = $query->row();
                        echo $result->nama . " (" . $result->nama_jabatan . ")";
                        echo "</br>";
                        $no++;
                      endforeach;
                    } else {
                      echo "--";
                    };
                    ?></td>
                </tr>
                <tr>
                  <td style="width:30%"><strong>Perihal</td>
                  <td> : </td>
                  <td><?php echo $memo->judul; ?></td>
                </tr>
              </table>
              <hr />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <table>
                <tr>
                  <td style="word-wrap: break-word; text-align:justify;" width="100%"><?php echo $memo->isi_memo; ?></td>
                </tr>
              </table>
              <table>
                <tr>
                  <td width="80%">Jakarta, <?php $date = $memo->tanggal;
                                            echo $newDate = date("d F Y", strtotime($date)); ?></td>
                  <td></td>
                </tr>
              </table>
              <table class="mt-4">
                <?php if (!empty($memo->attach)) { ?>
                  <tr>
                    <td>Attachment : </td>
                  </tr>
                  <tr>
                    <td>
                      <?php
                      $attach_ = '';
                      $no = '1';
                      $attch1 = explode(";", $memo->attach);
                      $attch2 = explode(";", $memo->attach_name);

                      foreach (array_combine($attch1, $attch2) as $attch1 => $attch2) {
                        if (!empty($attch1)) {
                          $attach_ .= "<a href='" . base_url() . "upload/att_memo/" . $attch1 . "' target='_blank'>" . $no . '. ' . $attch2 . "</a></br>\n";
                          $no++;
                        }
                      }
                      echo $attach_;
                      ?>
                    </td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td>Attachment : - </td>
                  </tr>
                <?php } ?>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <table class="ml-auto">
                <tr style="height:100px;">
                  <td style="text-align:right">
                    <a href="<?= site_url('app/inbox') ?>" class="btn btn-warning">Back</a>
                  </td>
                  <?php if (($this->session->userdata('level_jabatan') >= 1) and ($memo->nip_dari <> $this->session->userdata('nip'))) { ?>
                    <td style="text-align:right">
                      <form action="<?php echo base_url() . "app/reply_memo/" . $memo->Id; ?>" method="post">
                        <button type="submit" class="btn btn-primary">Reply</button>
                      </form>
                    </td>
                    <td style="text-align:right">
                      <form action="<?php echo base_url() . "app/reply_all_memo/" . $memo->Id; ?>" method="post">
                        <button type="submit" class="btn btn-primary">Reply All</button>
                      </form>
                    </td>
                    <td style="text-align:right">
                      <form action="<?php echo base_url() . "app/memo_pdf/" . $memo->Id; ?>" method="post" target="_blank">
                        <button type="submit" class="btn btn-pink">PDF</button>
                      </form>
                    </td>
                    <!-- <td style="text-align:right"><a href="<?= base_url('app/memo_pdf/' . $memo->Id) ?>" class="btn btn-success" target="_blank">PDF</a></td> -->
                  <?php } ?>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->