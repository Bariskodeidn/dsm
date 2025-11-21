<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <!-- <h1 class="page-title">Daftar Penunjukan </h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>List Invoice</strong></p>
        </div>
        <div class="card-body">
          <a href="<?= site_url('invoice/create') ?>" class="btn btn-primary">
            <i class="fe fe-plus"></i> Buat Invoice
          </a>
          <div class="table-responsive">
            <table id="tableInvoiceAgency" class="table table-bordered table-sm" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th style="width: 1%;">No.</th>
                  <th>Customer</th>
                  <th>Nomor Invoice</th>
                  <th>Tanggal</th>
                  <th>Kirim</th>
                  <th>Bayar</th>
                  <th>Monitor</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalKirim">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          Kirim Invoice
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="monitoring-table">
        <form action="<?= base_url('invoice/kirim') ?>" enctype="multipart/form-data" method="post" id="form-ubah-item">
          <div class="form-group">
            <label for="form-label">No Invoice</label>
            <input type="hidden" class="form-control" name="invoice-id" id="invoice-id" readonly>
            <input type="text" class="form-control" name="referensi" id="referensi" readonly>
          </div>
          <div class="form-group">
            <label for="form-label">Tanggal Kirim</label>
            <input type="date" class="form-control" name="tgl" id="tgl" value="<?= date('Y-m-d') ?>">
          </div>
          <div>
            <button type="submit" class="btn btn-primary btn-sm btn-submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalBayar">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          Bayar Invoice
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="monitoring-table">
        <form action="<?= base_url('invoice/bayar') ?>" enctype="multipart/form-data" method="post" id="form-ubah-item">
          <div class="form-group">
            <label for="form-label">No Invoice</label>
            <input type="hidden" class="form-control" name="invoice-id" id="invoice-id" readonly>
            <input type="text" class="form-control" name="referensi" id="referensi" readonly>
          </div>
          <div class="form-group">
            <label for="form-label">Tanggal Bayar</label>
            <input type="date" class="form-control" name="tgl" id="tgl" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group">
            <label for="form-label">Bukti Bayar</label>
            <input type="file" class="form-control" name="file" id="file">
          </div>
          <div class="form-group">
            <label for="form-label">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control"></textarea>
          </div>
          <div>
            <button type="submit" class="btn btn-primary btn-sm btn-submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalUpload">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          Upload Invoice
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="monitoring-table">
        <form action="<?= base_url('invoice/upload') ?>" enctype="multipart/form-data" method="post" id="form-ubah-item">
          <div class="form-group">
            <label for="form-label">No Invoice</label>
            <input type="hidden" class="form-control" name="invoice-id" id="invoice-id" readonly>
            <input type="text" class="form-control" name="referensi" id="referensi" readonly>
          </div>
          <div class="form-group">
            <label for="form-label">File Invoice</label>
            <input type="file" class="form-control" name="file" id="file">
          </div>
          <div>
            <button type="submit" class="btn btn-primary btn-sm btn-submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>