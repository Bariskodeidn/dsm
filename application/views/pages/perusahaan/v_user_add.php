<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<style>
  .bagian-th {
    /* Default alignment for mobile */
    display: none;
  }

  @media (min-width: 768px) {
    .bagian-th {
      /* Alignment for desktops */
      display: block;
    }
  }

  /* .bagian-td { */
  /* Default alignment for mobile */
  /* margin-left: -120px; */
  /* } */

  @media (min-width: 768px) {
    .bagian-td {
      /* Alignment for desktops */
      margin-left: 0px;
    }
  }
</style>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> <?= $this->session->flashdata('error'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> <?= $this->session->flashdata('success'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
      <?php endif; ?>
      <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <p class="card-title mb-0"><strong><?= ($this->uri->segment(2) == 'add_user') ? 'Tambah' : 'Ubah' ?> Pasukan</strong></p>
        </div>
        <div class="card-body" id="user">
          <font style="font-size:14px;">
            <?php if ($this->uri->segment(2) == 'add_user') { ?> <!-- add user -->
              <?= $this->session->flashdata('msg') ?>
              <form action="<?= base_url('perusahaan/proccess_add_user') ?>" method="POST">
                <input type="hidden" value="add" name="add">
                <input type="hidden" value="<?= $this->uri->segment('3') ?>" name="uri1">
                <input type="hidden" value="<?= $this->uri->segment('4') ?>" name="uri2">
                <table>
                  <tr>
                    <th width="300">Username</th>
                    <td width="300"> <input type="text" value="<?= set_value('username', $form_data['username'] ?? '') ?>" name="username" class="form-control"></td>
                  </tr>
                  <tr>
                    <th width="300">Password</th>
                    <td width="300"> <input type="password" name="password" class="form-control" value="<?= set_value('password', $form_data['password'] ?? '') ?>"></td>
                  </tr>
                  <tr>
                    <th width="300">Password Confirmation</th>
                    <td width="300"> <input type="password" name="password_confirmation" class="form-control" value="<?= set_value('password_confirmation', $form_data['password_confirmation'] ?? '') ?>"></td>
                  </tr>
                  <tr>
                    <th width="200">Name</th>
                    <td> <input type="text" name="nama" class="form-control" value="<?= set_value('nama', $form_data['nama'] ?? '') ?>">
                    </td>
                  </tr>

                  <?php
                  // Retrieve the form data from flashdata
                  $form_data = $this->session->flashdata('form_data');
                  $status_value = $form_data['status'] ?? '1'; // Default to '1' (active) if no data exists
                  ?>
                  <tr>
                    <th>Status</th>
                    <td>
                      <input name="status" value="1" type="radio" id="active" <?= ($status_value == '1') ? 'checked' : '' ?>>
                      <label for="active">Active</label>

                      <input name="status" value="0" type="radio" id="noactive" <?= ($status_value == '0') ? 'checked' : '' ?>>
                      <label for="noactive">Not Active</label>
                    </td>
                  </tr>
                  <tr>
                    <th width="200">Email</th>
                    <td> <input type="email" name="email" class="form-control" value="<?= set_value('email', $form_data['email'] ?? '') ?>"></td>
                  </tr>
                  <tr>
                    <th>Phone (WhatsApp)</th>
                    <td><input type="tel" name="phone" class="form-control" value="<?= set_value('phone', $form_data['phone'] ?? '') ?>"></td>
                  </tr>
                  <tr>
                    <th>User Role</th>
                    <td>
                      <select name="level_jabatan" id="" class="form-control" <?= ($this->uri->segment(3)) ? 'readonly' : '' ?>>
                        <?php
                        if ($this->uri->segment(3)) {
                          $uri3 = $this->uri->segment(3);
                          if ($this->uri->segment(4) == "Keuangan") {
                            $uri3 = 1;
                          }
                        ?>
                          <option selected value="<?= $uri3 ?>"><?= $this->uri->segment(4) ?></option>
                          <?php
                        } else {
                          if ($this->session->userdata('is_premium')) {
                          ?>
                            <option disabled>Pilih Jabatan</option>
                            <option value="1" <?= set_select('level_jabatan', '1'); ?>>Staff</option>
                            <option value="2" <?= set_select('level_jabatan', '2'); ?>>Supervisi</option>
                            <option value="3" <?= set_select('level_jabatan', '3'); ?>>Manajer</option>
                            <option value="4" <?= set_select('level_jabatan', '4'); ?>>General Manajer</option>
                            <option value="5" <?= set_select('level_jabatan', '5'); ?>>Direktur</option>
                            <option value="6" <?= set_select('level_jabatan', '6'); ?>>Direktur Utama</option>

                          <?php
                          } else {
                          ?>
                            <option selected disabled>Pilih Jabatan</option>
                            <?php
                            $user_counts = isset($user_counts) ? $user_counts : [];
                            $roles = [
                              1 => 'Staff',
                              2 => 'Supervisi',
                              3 => 'Manajer',
                              4 => 'General Manajer', // This one is commented out in your example, so keep it commented
                              5 => 'Direktur',
                              6 => 'Direktur Utama',
                            ];
                            foreach ($roles as $value => $label) {
                              if (isset($user_counts[$value]) && $user_counts[$value] >= 1) {
                                continue;
                              }
                            ?>
                              <option value="<?= $value ?>"><?= $label ?></option>
                        <?php
                            }
                          }
                        }
                        ?>
                      </select>
                      <input type="hidden" name="role_name" value="<?= $this->uri->segment(4) ?>">
                    </td>
                  </tr>
                  <tr>
                    <th>Bagian</th>
                    <td>
                      <div class="row">
                        <div class="col-md-10 col-7">
                          <?php
                          if ($this->session->userdata('is_premium')) {
                          ?>
                            <!-- <select name="bagian" class="form-control" id="mySelect">
                              <?php $xx = $this->db->from('bagian')->where('id_prsh', $this->session->userdata('user_perusahaan_id'))->get()->result();
                              foreach ($xx as $k) {
                                if (!empty($user)) {
                              ?>
                                  <option <?= $k->Id == $user->bagian ? 'selected' : '' ?> value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                                <?php } else { ?>
                                  <option value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                              <?php }
                              } ?>
                            </select> -->
                            <select name="bagian" class="form-control" id="mySelect">
                              <?php
                              $xx = $this->db->from('bagian')->where('id_prsh', $this->session->userdata('user_perusahaan_id'))->get()->result();
                              foreach ($xx as $k) {
                                // Check for flashdata first, then fall back to the existing user data
                                $selected = set_select('bagian', $k->Id, (!empty($user) && $k->Id == $user->bagian));
                              ?>
                                <option value="<?= $k->Id ?>" <?= $selected ?>>
                                  <?= $k->kode_nama . ' - ' . $k->nama ?>
                                </option>
                              <?php } ?>
                            </select>
                          <?php
                          } else {
                          ?>
                            <select name="bagian" class="form-control" id="mySelect" readonly>
                              <?php $xx = $this->db->from('bagian')->where('id_prsh', $this->session->userdata('user_perusahaan_id'))->get()->result();
                              foreach ($xx as $k) {
                                if (!empty($user)) {
                              ?>
                                  <option <?= $k->Id == $user->bagian ? 'selected' : '' ?> value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                                <?php } else { ?>
                                  <option value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                              <?php }
                              } ?>
                            </select>
                          <?php
                          }
                          ?>
                        </div>
                        <div class="col-md-2 col-5">
                          <button type="button" id="addOptionBtn" class="btn btn-primary btn-block"><i class="fe fe-plus"></i></button>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr id="add-bagian-tr" style="display: none;">
                    <th></th>
                    <td class="bagian-td">
                      <form id="form-add-bagian">
                        <div class="row">
                          <input type="hidden" class="form-control" id="input_id_prsh" name="input_id_prsh" value="<?= $this->session->userdata('user_perusahaan_id') ?>">
                          <!-- <div class="col-3">
                            <div class="form-group">
                              <input type="text" class="form-control" id="input_kode" name="input_kode" placeholder="Enter Kode">
                            </div>
                          </div> -->
                          <div class="col-md-4 col-6">
                            <div class="form-group">
                              <!-- <label for="input_kode_nama">Kode Nama</label> -->
                              <input type="text" class="form-control" id="input_kode_nama" name="input_kode_nama" placeholder="Enter Kode">
                            </div>
                          </div>
                          <div class="col-md-5 col-6">
                            <div class="form-group">
                              <!-- <label for="input_nama">Nama</label> -->
                              <input type="text" class="form-control" id="input_nama" name="input_nama" placeholder="Enter Nama">
                            </div>
                          </div>
                          <div class="col-md-3 d-flex justify-content-end">
                            <div class="form-group">
                              <button type="button" id="submitNewBagianBtn" class="btn btn-primary">Submit</button>
                              <button type="button" id="cancelNewBagianBtn" class="btn btn-danger">Cancel</button>
                            </div>
                          </div>
                        </div>
                      </form>
                      <div id="statusMessageBagian" class="mt-2"></div>
                    </td>
                  </tr>
                  <tr>
                    <th>Nama Jabatan</th>
                    <td><input type="text" name="nama_jabatan" class="form-control" value="<?= set_value('nama_jabatan', $form_data['nama_jabatan'] ?? '') ?>"></td>
                  </tr>
                  <tr>
                    <th>Supervisi</th>
                    <td>
                      <select name="supervisi" id="" class="form-control js-example-basic-multiple">
                        <option value="0" <?= set_select('supervisi', '0'); ?>>None</option>
                        <?php foreach ($supervisi as $data) { ?>
                          <option value="<?= $data->nip ?>" <?= set_select('supervisi', $data->nip); ?>>
                            <?= $data->nama_jabatan ?>
                          </option>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>User Menu Access</th>
                    <td width="800">
                      <div class="row">
                        <?php foreach ($all_menus_hierarchical as $parent_menu) : ?>
                          <?php if ($parent_menu->is_active == 1) :
                            // if ($parent_menu->menu_name == "Menu Admin" || "Perusahaan")
                          ?>
                            <div class="col-md-6 mb-3 mt-2">
                              <div class="form-check">
                                <input class="form-check-input parent-checkbox" type="checkbox"
                                  name="menu_ids[]"
                                  <?php if ($parent_menu->premium == 1) {
                                    echo $this->session->userdata('is_premium') == $parent_menu->premium ? '' : 'disabled';
                                  } ?>
                                  <?php
                                  if ($parent_menu->premium == 1 && !$this->session->userdata('is_premium')) {
                                  } else if ($parent_menu->menu_name == "Menu Admin" || $parent_menu->menu_name == "Menu Perusahaan") {
                                  } else if ($this->uri->segment(4) == "Manager" && $parent_menu->menu_name == "Financial") {
                                  } else if ($this->uri->segment(4) == "Staff" && $parent_menu->menu_name == "Financial") {
                                  } else {
                                    echo "Checked";
                                  }
                                  ?>


                                  value="<?= html_escape($parent_menu->Id); ?>"
                                  id="menu_<?= html_escape($parent_menu->Id); ?>">
                                <label class="form-check-label font-weight-bold" for="menu_<?= html_escape($parent_menu->Id); ?>">
                                  <i class="<?= html_escape($parent_menu->icon); ?>"></i> <?= html_escape($parent_menu->menu_name); ?>
                                  <?= $parent_menu->premium == 1 ? ' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>' : '' ?>
                                </label>
                              </div>
                              <?php if (!empty($parent_menu->children)) : ?>
                                <div style="margin-left: 25px;">
                                  <?php foreach ($parent_menu->children as $child_menu) : ?>
                                    <?php if ($child_menu->is_active == 1) : ?>
                                      <div class="form-check">
                                        <input class="form-check-input child-checkbox" type="checkbox"
                                          name="menu_ids[]"

                                          <?php if ($parent_menu->premium == 1) {
                                            echo $this->session->userdata('is_premium') == $parent_menu->premium ? '' : 'disabled';
                                          } ?>

                                          value="<?= html_escape($child_menu->Id); ?>"
                                          id="menu_<?= html_escape($child_menu->Id); ?>"
                                          data-parent-id="<?= html_escape($parent_menu->Id); ?>"

                                          <?php
                                          if ($child_menu->menu_name == "Buat Pengajuan" || $child_menu->menu_name == "List Pengajuan") {
                                            // if ($child_menu->menu_name == "Buat Pengajuan") {
                                            if ($this->uri->segment(4) == "Staff") {
                                              echo "Checked";
                                            }
                                          } else if ($child_menu->menu_name == "Approval Supervisi") {
                                            if ($this->uri->segment(4) == "Manager") {
                                              echo "Checked";
                                            }
                                          } else if ($child_menu->menu_name == "Approval Keuangan") {
                                            if ($this->uri->segment(4) == "Keuangan") {
                                              echo "Checked";
                                            }
                                          } else if ($child_menu->menu_name == "Approval Direktur" || $child_menu->menu_name == "Approval Direksi") {
                                            if ($this->uri->segment(4) == "Direktur") {
                                              echo "Checked";
                                            }
                                          } else if ($child_menu->menu_name == "Neraca Konsolidasi") {
                                            if ($this->uri->segment(4) == "Direktur") {
                                              echo "Checked";
                                            }
                                          } else if ($parent_menu->menu_name == "Menu Admin" || $parent_menu->menu_name == "Menu Perusahaan" || $parent_menu->menu_name == "Settings") {
                                          } else if ($this->uri->segment(4) == "Manager" && $parent_menu->menu_name == "Financial") {
                                          } else if ($this->uri->segment(4) == "Staff" && $parent_menu->menu_name == "Financial") {
                                          } else if ($this->session->userdata('is_premium') && $parent_menu->premium == 1) {
                                            // continue;
                                            echo "Checked";
                                          } else if (!$this->session->userdata('is_premium') && $parent_menu->premium == 1) {
                                            // echo "Checked";
                                          } else {
                                            echo "Checked";
                                          }
                                          ?>>
                                        <label class="form-check-label" for="menu_<?= html_escape($child_menu->Id); ?>">
                                          <i class="<?= html_escape($child_menu->icon); ?>"></i> <?= html_escape($child_menu->menu_name); ?>
                                          <?= $parent_menu->premium == 1 ? ' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>' : '' ?>
                                        </label>
                                      </div>
                                    <?php endif; ?>
                                  <?php endforeach; ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </div>
                    </td>
                  </tr>
                  <br>
                  <tr>
                    <th>
                      <a class="btn btn-warning" href="<?= base_url('perusahaan/user') ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    </th>
                    <td><button type="submit" class="btn btn-primary">Submit</button></td>
                  </tr>
                </table>
              </form>
            <?php  } else if ($this->uri->segment(2) == 'edit_user') { ?>
              </br>
              <?= $this->session->flashdata('msg') ?>
              <form action="<?= base_url('perusahaan/proccess_edit_user/' . $this->uri->segment('3')) ?>" method="POST">
                <input type="hidden" value="edit" name="edit">
                <input type="hidden" value="<?= $this->uri->segment('3') ?>" name="id">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Username</label>
                      <input readonly type="text" name="username" class="form-control" value="<?= $user->username ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="nama" class="form-control" value="<?= $user->nama ?>">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <br>
                      <div class="form-check">
                        <input <?= $user->status ? 'checked' : '' ?> name="status" type="radio" value="1" id="active" class="form-check-input">
                        <label class="form-check-label" for="active">Active</label>
                        <br>
                        <input <?= $user->status ? '' : 'checked' ?> name="status" type="radio" value="0" id="noactive" class="form-check-input">
                        <label class="form-check-label" for="noactive">Not Active</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="email" class="form-control" value="<?= $user->email ?>">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Phone</label>
                      <input type="tel" name="phone" class="form-control" value="<?= $user->phone ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nip</label>
                      <input readonly type="text" name="nip" class="form-control" value="<?= $user->nip ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Role</label>
                      <select name="level_jabatan" id="" class="form-control" <?= ($user->level_jabatan == "99") ? "readonly" : '' ?>>
                        <option selected disabled>Pilih Jabatan</option>
                        <?php
                        if ($user->level_jabatan == "99") {
                        ?>
                          <option selected value="99">Super Admin</option>
                        <?php
                        } else if ($this->session->userdata('is_premium')) {
                        ?>
                          <option <?= ($user->level_jabatan == "1") ? 'selected' : '' ?> value="1">Staff</option>
                          <option <?= ($user->level_jabatan == "2") ? 'selected' : '' ?> value="2">Supervisi</option>
                          <option <?= ($user->level_jabatan == "3") ? 'selected' : '' ?> value="3">Manajer</option>
                          <option <?= ($user->level_jabatan == "4") ? 'selected' : '' ?> value="4">General Manajer</option>
                          <option <?= ($user->level_jabatan == "5") ? 'selected' : '' ?> value="5">Direktur</option>
                          <option <?= ($user->level_jabatan == "6") ? 'selected' : '' ?> value="6">Direktur Utama</option>
                          <?php
                        } else {
                          $user_counts = isset($user_counts) ? $user_counts : [];
                          $roles = [
                            1 => 'Staff',
                            2 => 'Supervisi',
                            3 => 'Manajer',
                            4 => 'General Manajer', // This one is commented out in your example, so keep it commented
                            5 => 'Direktur',
                            6 => 'Direktur Utama',
                          ];
                          foreach ($roles as $value => $label) {
                            if (isset($user_counts[$value]) && $user_counts[$value] >= 1) {
                              if ($user->level_jabatan == $value) {
                          ?>
                                <option selected value="<?= $value ?>"><?= $label ?></option>
                            <?php
                              }
                              continue;
                            }
                            ?>
                            <option <?= ($user->level_jabatan == $value) ?> value="<?= $value ?>"><?= $label ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Bagian</label>
                      <div class="row">
                        <div class="col-md-10 col-lg-10 col-10">
                          <?php
                          if ($this->session->userdata('is_premium')) {
                          ?>
                            <select name="bagian" class="form-control" id="mySelect">
                              <?php $xx = $this->db->from('bagian')->where('id_prsh', $this->session->userdata('user_perusahaan_id'))->get()->result();
                              foreach ($xx as $k) {
                                if (!empty($user)) {
                              ?>
                                  <option <?= $k->Id == $user->bagian ? 'selected' : '' ?> value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                                <?php } else { ?>
                                  <option value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                              <?php }
                              } ?>
                            </select>
                          <?php
                          } else {
                          ?>
                            <select name="bagian" class="form-control" id="mySelect" readonly>
                              <?php $xx = $this->db->from('bagian')->where('id_prsh', $this->session->userdata('user_perusahaan_id'))->get()->result();
                              foreach ($xx as $k) {
                                if (!empty($user)) {
                              ?>
                                  <option <?= $k->Id == $user->bagian ? 'selected' : '' ?> value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                                <?php } else { ?>
                                  <option value="<?= $k->Id ?>"><?= $k->kode_nama . ' - ' . $k->nama ?></option>
                              <?php }
                              } ?>
                            </select>
                          <?php
                          }
                          ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-2">
                          <button type="button" id="addOptionBtn" class="btn btn-primary d-flex align-items-center justify-content-center">
                            <i class="fe fe-plus"></i>
                            <span class="ms-1">
                            </span>
                          </button>
                        </div>
                        <div class="col-md-12 mt-2" style="display: none;" id="add-bagian-div">
                          <form id="form-add-bagian">
                            <div class="row">
                              <input type="hidden" class="form-control" id="input_id_prsh" name="input_id_prsh" value="<?= $this->session->userdata('user_perusahaan_id') ?>">
                              <div class="col-md-3 col-6">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="input_kode_nama" name="input_kode_nama" placeholder="Kode Nama">
                                </div>
                              </div>
                              <div class="col-md-5 col-6">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="input_nama" name="input_nama" placeholder="Enter Nama">
                                </div>
                              </div>
                              <div class="col-md-4 d-flex justify-content-end">
                                <div class="form-group">
                                  <button type="button" id="submitNewBagianBtn" class="btn btn-primary">Submit</button>
                                  <button type="button" id="cancelNewBagianBtn" class="btn btn-danger">Cancel</button>
                                </div>
                              </div>
                            </div>
                          </form>
                          <div id="statusMessageBagian" class="mt-2"></div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nama Jabatan</label>
                      <input type="text" name="nama_jabatan" class="form-control" value="<?= $user->nama_jabatan ?>" <?= ($user->nama_jabatan == "Super Admin") ? "readonly" : '' ?>>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Supervisi</label>
                      <select name="supervisi" class="form-control js-example-basic-multiple">
                        <option value=""> -- Pilih Supervisi --</option>
                        <?php
                        $kode_cabang = $this->session->userdata('kode_cabang');
                        $id_to_exclude = $this->uri->segment(3);

                        $this->db->where('id_cabang', $kode_cabang);
                        $this->db->where('level_jabatan >=', 3);
                        $this->db->where('id !=', $id_to_exclude);

                        $supervisi = $this->db->get('users')->result();

                        foreach ($supervisi as $data) {
                          if ($user->supervisi != null || $user->supervisi != "") {
                            $super_visi = $this->db->get_where('users', ['nip' => $user->supervisi])->row();
                            $selected = $super_visi->nip == $data->nip ? "selected" : "";
                          } else {
                            $selected = "";
                          }
                        ?>
                          <option <?= $selected ?> value="<?= $data->nip ?>"><?= $data->nama_jabatan ?> - <?= $data->nama ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label>User Menu Access</label>
                    <div class="row">
                      <?php foreach ($all_menus_hierarchical as $parent_menu) : ?>
                        <?php if ($parent_menu->is_active == 1) : // Optional: Only show active parent menus 
                        ?>
                          <div class="col-md-6 mb-3 mt-2">
                            <div class="form-check">
                              <input class="form-check-input parent-checkbox" type="checkbox"
                                <?php if ($parent_menu->premium == 1) {
                                  echo $this->session->userdata('is_premium') == $parent_menu->premium ? '' : 'disabled';
                                } ?>
                                name="menu_ids[]"
                                value="<?= html_escape($parent_menu->Id); ?>" id="menu_<?= html_escape($parent_menu->Id); ?>" <?= in_array($parent_menu->Id, $user_menu_ids) ? 'checked' : ''; ?>> <label class="form-check-label font-weight-bold" for="menu_<?= html_escape($parent_menu->Id); ?>"> <i class="<?= html_escape($parent_menu->icon); ?>"></i> <?= html_escape($parent_menu->menu_name); ?>
                                <?= $parent_menu->premium == 1 ? ' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>' : '' ?>
                              </label>
                            </div>
                            <?php if (!empty($parent_menu->children)) : ?>
                              <div style="margin-left: 25px;">
                                <?php foreach ($parent_menu->children as $child_menu) : ?>
                                  <?php if ($child_menu->is_active == 1) : // Optional: Only show active child menus 
                                  ?>
                                    <div class="form-check">
                                      <input class="form-check-input child-checkbox" type="checkbox"
                                        <?php if ($parent_menu->premium == 1) {
                                          echo $this->session->userdata('is_premium') == $parent_menu->premium ? '' : 'disabled';
                                        } ?>
                                        name="menu_ids[]"
                                        value="<?= html_escape($child_menu->Id); ?>" id="menu_<?= html_escape($child_menu->Id); ?>" data-parent-id="<?= html_escape($parent_menu->Id); ?>" <?= in_array($child_menu->Id, $user_menu_ids) ? 'checked' : ''; ?>> <label class="form-check-label" for="menu_<?= html_escape($child_menu->Id); ?>"> <i class="<?= html_escape($child_menu->icon); ?>"></i> <?= html_escape($child_menu->menu_name); ?>
                                        <?= $parent_menu->premium == 1 ? ' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>' : '' ?>

                                      </label>
                                    </div>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </div>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <a class="btn btn-warning" href="<?= base_url('perusahaan/user') ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </div>

                </div>
              </form>
              <br>
            <?php } ?>
          </font>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->