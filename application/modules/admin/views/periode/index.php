<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
			<div class="card-header">
				<a class="nav-s text-danger" href="<?= base_url("admin/periode/index/1"); ?>">
					<i class="fas fa-fw fa-exclamation-circle"></i>
					Sudah Diterbitkan
				</a>
				</a>&nbsp;
				<a class=" nav-lilk" href="<?= base_url("admin/periode/index/0"); ?>">
					<i class="fas fa-fw fa-envelope"></i>
					Belum Diterbitkan
				</a>
			</div>
			<div class="card-body">
				<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
					<thead>
						<tr>
							<th style="width:50%">Periode</th>
							<th style="width:20%">Status</th>
							<th>Tanggal Terbit</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($daftar_periode as $periode) { ?>
							<tr>
								<td><a href="<?= base_url('admin/periode/bulan/' . $periode['id_periode']); ?>"><?= $periode['nama_periode']; ?></a></td>
								<td><?= $periode['status'] == 0 ? 'Belum Diterbitkan' : 'Sudah Diterbitkan'; ?></td>
								<td><?= $periode['tanggal'] == '' ? '-' : $periode['tanggal']; ?></td>
								<td>
									<button type="button" data-toggle="modal" class="btn btn-primary btn-sm" data-target="#exampleModal">
										edit reward
									</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Reward</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> -->
			<div class="modal-body">
				<div class="form-group text-center">
					<label for="exampleInputEmail1">Masukkan nominal reward</label>
					<span id="emailHelp" class="form-text text-muted">contoh : 200000</span>
					<input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
<!-- /.modal -->



<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable({});
	});
</script>
