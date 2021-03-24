<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<div class="card-body">
				<table id="pengajuan-desc" class="display table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>Judul</th>
							<th>Status</th>
							<th>NIM</th>
							<th>Terakhir Dilihat Admin</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($prestasi as $prestasi) { ?>
							<tr>
								<td><?= $prestasi['id_pengajuan']; ?></td>
								<td><?= $prestasi['id_pengajuan']; ?></td>
								<td><?= $prestasi['id_pengajuan']; ?></td>
								<td><?= $prestasi['id_pengajuan']; ?></td>
							</tr>
						<?php }
						?>
					</tbody>
					<tbody>
					</tbody>
				</table>
			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable();
	});
</script>