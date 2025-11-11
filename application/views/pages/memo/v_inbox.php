<style>
  .open-memo {
    cursor: pointer;
  }
</style>


<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <h1 class="page-title">Inbox</h1>
      <div class="card shadow mb-4">
        <div class="card-header">
          <strong class="card-title">Digital Memo</strong>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
              <form action="<?= site_url('app/inbox') ?>" method="get">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Cari judul memo" name="search" id="search" value="<?= $this->input->get('search') ?>">
                  <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit">
                      Cari
                    </button>
                    <a href="<?= site_url('app/inbox') ?>" class="btn btn-warning">Tampilkan Semua</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <table class="table table-hover table-sm">
            <thead style="background-color:#3498db;">
              <tr>
                <th style="color: white;">No</th>
                <th style="color: white;">Judul</th>
                <th style="color: white;">Tanggal</th>
                <th style="color: white;">Dari</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (empty($data_memo)) { ?>
                <tr>
                  <td colspan="4" class="text-center">Data tidak ditemukan</td>
                </tr>
                <?php } else {
                $nip = $this->session->userdata('nip');
                foreach ($data_memo as $data) {
                  $user_read = $this->db->select('Id')
                    ->from('memo')
                    ->where('Id', $data->Id)
                    ->like("CONCAT(';', `read`, ';')", ';' . $nip . ';', 'both')
                    ->get()
                    ->num_rows();
                  if ($user_read) {
                    $font_weight = 'normal';
                  } else {
                    $font_weight = 'bold';
                  }
                ?>
                  <tr style="font-weight: <?= $font_weight ?> ">
                    <td onclick="openMemo(<?= $data->Id ?>)" class="open-memo"><?= ++$page; ?></td>
                    <td style="max-width: 150px;" onclick="openMemo(<?= $data->Id ?>)" class="open-memo"><?= $data->judul ?></td>
                    <td onclick="openMemo(<?= $data->Id ?>)" class="open-memo"><?= date('d/m/y | H:i:s', strtotime($data->tanggal)) ?></td>
                    <td onclick="openMemo(<?= $data->Id ?>)" class="open-memo"><?= $data->nama; ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav aria-label="Table Paging" class="mb-0">
            <?= $pagination ?>
          </nav>

        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->