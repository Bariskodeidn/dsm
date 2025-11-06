<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <h2 class="page-title">Digital Memo</h2>
      <div class="card shadow mb-4">
        <div class="card-header">
          <strong class="card-title">Create New Digital Memo</strong>
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
                <input class="form-control" name="judul" id="judul" type="text" placeholder="Judul Memo Digital">
              </div>
            </div>
            <div class="form-group row">
              <label for="attach" class="col-sm-3 col-form-label">Attachment</label>
              <div class="col-sm-9">
                <input class="form-control-file" name="attach[]" id="attach" type="file" multiple>
              </div>
            </div>
            <div class="form-group row">
              <label id="memo_digital_label" class="col-sm-3" for="exampleFormControlTextarea1">Isi Memo Digital <strong>(*)</strong></label>
              <div class="col-sm-12">
                <textarea class="ckeditor" id="ckeditor" name="ckeditor" rows="2"></textarea>
              </div>
            </div>
            <div class="form-group mb-2">
              <button type="submit" class="btn btn-primary btn-send"><i class="fe fe-send"></i> Kirim</button>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->