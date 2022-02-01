<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<div class="card-header">
				
			</div>

			<div class="card-body">
				<table id="pengajuan-descs" class="table table-bordered tb-pengajuans">
					<thead>
						<tr>
							<th style="width:30%">Kategori</th>
							<th style="width:35%">Judul Kegiatan</th>
							<th style="width:25%">Status</th>
							<th>Mahasiswa</th>							
							<th style="width:10%">Reward</th>						
						</tr>
					</thead>
					<tbody>
						<?php

                        echo "<pre>";
                        print_r($daftar_prestasi);
                        echo "</pre>";
					 	
                        ?>
				</table>

			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- Modal -->




<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable({});
	});

	$(".btn-pencairan").click(function() {
		var id_penerbitan_pengajuan = this.id;
		$("#id_penerbitan_pengajuan_field").val(id_penerbitan_pengajuan);
	});

	function confirmSubmit() {
		var agree = confirm("Yakin ingin menghapus data ini?");
		if (agree)
			return true;
		else
			return false;
	}

	$(".btn-reward").click(function() {
		console.log(this.id);
		var id_prestasi = this.id;
		$.ajax({
			url: "<?= base_url('/admin/periode/reward/'); ?>" + id_prestasi,
			success: function(data) {
				$("#nominal_reward").val(data)
				$("#id_prestasi_value").val(id_prestasi)
			}
		});
	});
</script>