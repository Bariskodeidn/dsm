<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-12">
			<h1 class="page-title">Search File Memo</h1>
			<div class="card shadow mb-4">
				<div class="card-header">
					<strong class="card-title">Digital Memo</strong>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<form method="GET" action="<?= site_url('file/search'); ?>">
								<div class="input-group">
									<input type="text" class="form-control" name="keyword" value="<?= html_escape($keyword); ?>" placeholder="Ketik nama file..." required>
									<span class="input-group-btn">
										<button class="btn btn-info" type="submit">Search</button>
										<a href="<?= base_url('file/search') ?>" class="btn btn-warning" style="color:white;">Reset</a>
									</span>
								</div><!-- /input-group -->
							</form>

							<?php if (!empty($keyword)): ?>
								<h3 class="my-3">Hasil Pencarian untuk: "<?= html_escape($keyword); ?>"</h3>

								<?php if (!empty($files)): ?>
									<table class="table">
										<thead class="thead-dark">
											<tr>
												<th>No</th>
												<th>Nama File Asli</th>
												<th>Tanggal Memo</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php $no = 1;
											foreach ($files as $file): ?>
												<tr>
													<td><?= $no++; ?></td>
													<td><?= html_escape($file['original_name']); ?></td>
													<td><?= ($file['date_memo']); ?></td>
													<td>
														<a href="<?= site_url('file/download/' . $file['memo_id'] . '/' . $file['file_index']); ?>" class="btn btn-sm btn-primary">
															Download
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<p>File tidak ditemukan.</p>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- .col-12 -->
	</div> <!-- .row -->
</div> <!-- .container-fluid -->