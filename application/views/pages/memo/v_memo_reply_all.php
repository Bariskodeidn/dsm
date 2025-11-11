<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <h2 class="page-title">Digital Memo</h2>
      <div class="card shadow mb-4">
        <div class="card-header">
          <strong class="card-title">Reply Memo</strong>
        </div>
        <div class="card-body">
          <form method="post" action="<?= site_url('app/send_memo') ?>" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="tujuan" class="col-sm-3 col-form-label">Tujuan <strong>(*)</strong></label>
              <div class="col-sm-9">
                <select name="tujuan[]" id="tujuan" class="form-control select2" multiple></select>
              </div>
            </div>
            <div class="form-group row">
              <label for="cc" class="col-sm-3 col-form-label">CC</label>
              <div class="col-sm-9">
                <select name="cc[]" id="cc" class="form-control select2" multiple></select>
              </div>
            </div>
            <div class="form-group row">
              <label for="judul" class="col-sm-3 col-form-label">Judul <strong>(*)</strong></label>
              <div class="col-sm-9">
                <input class="form-control" name="judul" id="judul" type="text" placeholder="Judul Memo Digital" value="<?= $memo->judul ?>">
              </div>
            </div>
            <?php
            if ($this->session->userdata('is_premium')) {
            ?>
              <div class="form-group row">
                <label for="attach" class="col-sm-3 col-form-label">Attachment <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16">
                    <path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z" />
                  </svg></label>
                <div class="col-sm-9">
                  <?php
                  if ($memo->attach_name) {
                  ?>
                    <input type="text" class="form-control" name="attach_exist" value="<?= $memo->attach_name ?>" readonly>
                    <input type="hidden" class="form-control mb-3" name="attach_exist_encrypt" value="<?= $memo->attach ?>">
                  <?php } ?>
                  <input class="form-control-file" name="attach[]" id="attach" type="file" multiple>
                </div>
              </div>
            <?php
            }
            ?>
            <div class="form-group row">
              <label class="col-sm-3" for="exampleFormControlTextarea1">Isi Memo Digital <strong>(*)</strong></label>
              <div class="col-sm-12">
                <textarea class="ckeditor" id="ckeditor" name="ckeditor" rows="2">
                  <?php
                  $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
                  $bln = $array_bln[date('n', strtotime($memo->tanggal))];

                  if (!empty($memo->isi_memo)) {
                    echo ('<br><hr/>');
                    echo ('<br> created by. ');
                    $nip = $memo->nip_dari;

                    $query = $this->db->query("SELECT nama,nama_jabatan FROM users WHERE nip='$nip';")->row()->nama;
                    echo $query;
                    if ($this->uri->segment(3) == true) {
                      echo "<br>";
                      echo "No Memo : " . sprintf("%03d", $memo->nomor_memo) . '/E-MEMO/INT/' . $memo->bagian . '/' . $bln . '/' . date('Y', strtotime($memo->tanggal));
                    }
                    echo $memo->isi_memo;
                  }

                  ?>

                </textarea>
              </div>
            </div>
            <div class="form-group mb-2">
              <a href="<?= site_url('app/memo_view/' . $memo->Id) ?>" class="btn btn-warning">Back</a>
              <button type="submit" class="btn btn-primary btn-send"><i class="fe fe-send"></i> Kirim</button>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->