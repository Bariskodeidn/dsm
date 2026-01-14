<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <p class="card-title"><strong>Ubah Kapal <?= $kapal->name ?></strong></p>
                </div>
                <div class="card-body">
                    <form class="form-horizontal form-label-left" method="post" action="<?= site_url('agency/update_kapal/' . $kapal->Id) ?>">
                        <div class="item form-group">
                            <div class="row">
                                <label class="col-form-label col-md-3 col-sm-3 col-xs-12 label-align" for="first-name">Vessel Of Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="vessel-name" class="form-control" value="<?= $kapal->name ?>">
                                    <small class="error" id="vessel-name_error" style="color:red"></small>
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="row">
                                <label class="col-form-label col-md-3 col-sm-3 col-xs-12 label-align" for="last-name">Type <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="type" class="form-control">
                                        <?php foreach ($kategori as $kat) : ?>
                                            <option value="<?= $kat->Id ?>" <?= $kapal->type == $kat->Id ? 'selected' : '' ?>><?= $kat->nama_kategori . ' (' . $kat->kode . ')' ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="error" id="type_error" style="color:red"></small>
                                </div>
                            </div>
                        </div>

                        <div class="item form-group">
                            <div class="row">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 col-xs-12 label-align">Flag <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" name="flag" value="<?= $kapal->flag ?>">
                                    <small class="error" id="flag_error" style="color:red"></small>
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="row">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Gross Tonage (T) <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control nominal" type="text" name="gross" value="<?= $kapal->grt ?>">
                                    <small class="error" id="gross_error" style="color:red"></small>
                                </div>
                            </div>
                        </div>

                        <div class="item form-group">
                            <div class="row">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Gross Tonage Barge (T)</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control nominal" type="text" name="gross_barge" value="<?= $kapal->grt_barge ?>">
                                    <small class="error" id="gross_barge_error" style="color:red"></small>
                                </div>
                            </div>
                        </div>

                        <div class="item form-group">
                            <div class="row">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">DWT (T) <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control nominal" type="text" name="dwt" value="<?= $kapal->dwt ?>">
                                    <small class="error" id="dwt_error" style="color:red"></small>
                                </div>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <a href="<?= site_url('agency/kapal') ?>" class="btn btn-warning" type="button">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->