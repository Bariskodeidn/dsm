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
                <select name="tujuan[]" id="tujuan" class="form-control select2" multiple>
                  <?php foreach ($users as $user) : ?>
                    <option value="<?= $user->nip ?>"><?= $user->nama ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="cc" class="col-sm-3 col-form-label">CC</label>
              <div class="col-sm-9">
                <select name="cc[]" id="cc" class="form-control select2" multiple>
                  <?php foreach ($users as $user) : ?>
                    <option value="<?= $user->nip ?>"><?= $user->nama ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="judul" class="col-sm-3 col-form-label">Judul <strong>(*)</strong></label>
              <div class="col-sm-9">
                <input class="form-control" name="judul" id="judul" type="text" placeholder="Judul Memo Digital" value="<?= $memo->judul ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="attach" class="col-sm-3 col-form-label">Attachment</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="attach_exist" value="<?= $memo->attach_name ?>" readonly>
                <input type="hidden" class="form-control" name="attach_exist_encrypt" value="<?= $memo->attach ?>">
                <input class="form-control-file mt-3" name="attach[]" id="attach" type="file" multiple>
              </div>
            </div>
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