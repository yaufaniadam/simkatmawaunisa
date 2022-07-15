<?php
	$last = $this->uri->total_segments();
	$id_dprt = $this->uri->segment($last - 1);
	$id_ktg = $this->uri->segment($last);
?>
<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<!-- <div class="card-header">
			<?php echo form_open("/", 'id="filter_search"') ?>
				<div class="row">
		
						<div class="col-md-4">
							<select name="kategori" id="kategori" class="form-control kategori mb-2">

								<option value="">Semua Kategori</option>
								<?php  foreach ($kategories as $option) { ?>								
									<option value="<?= $option['Jenis_Pengajuan_Id']; ?>"><?= $option['Jenis_Pengajuan']; ?></option>
								<?php } ?>					
							</select>
						
						</div>
						<div class="col-md-4">

					
						<select name="prodi" id="prodi" class="form-control prodi mb-2">
							<option value="">Semua Prodi</option>
							<?php  foreach ($departments as $department) { ?>								
									<option value="<?= $department['DEPARTMENT_ID']; ?>"><?= $department['NAME_OF_DEPARTMENT']; ?></option>
								<?php } ?>	
							</select>	
						</div>					

				</div>
				<?php echo form_close(); ?>
			</div> -->

			<div class="card-body">
				<table id="prestasi" class="table table-bordered tb-pengajuans table-striped">
					<thead>
						<tr>
							<th style="width:20%">Kategori</th>
							<th style="width:35%">Judul/Nama/Kegiatan/Karya</th>
							<th  style="width:20%">Mahasiswa</th>
							<th  style="width:20%">Prodi</th>
							<th  style="width:20%">Tahun</th>
							<th  style="width:20%">Periode</th>
							<th  style="width:20%">Tingkat</th>
							<th  style="width:20%">Prestasi</th>
						</tr>
					</thead>
				
				</table>

					<!-- <tbody>
					<?php
						foreach ($daftar_prestasi as $prestasi) {
							$reward = $prestasi['nominal']; ?>
							<tr>
								<td><?= $prestasi['Jenis_Pengajuan']; ?></td>
								<td><a href="<?= base_url('admin/prestasi/detail/' . $prestasi['id_penerbitan_pengajuan']); ?>"><?= get_meta_value_by_type_field('judul', $prestasi['id_pengajuan'], false); ?></a></td> 
								<td><?= $prestasi['FULLNAME']; ?></td>
								<td><?= $prestasi['NAME_OF_DEPARTMENT']; ?></td>
								<td><?= $prestasi['nama_periode']; ?></td>
								<td></td>
								<td class="text-right">Rp<?= ($reward > 0) ? number_format($reward) : 'Pada Tim'; ?></td>
								<td class="text-center"><?= $prestasi['point']; ?></td>
							</tr>
						<?php } ?> </tbody>-->

			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- Modal -->

<!-- 
						
					
						<th>Periode</th>
						<th>Tahun</th>
						<th style="width:10%">Reward</th>
						<th style="width:10%">Point</th> -->



<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	
		//---------------------------------------------------
		var table = $('#prestasi').DataTable({
			"processing": true,
			"serverSide": false,
			"dom": 'Bfrtip',
			// "buttons": [{
			// 		extend: 'collection',
      //     text: 'Export Table',
			// 		buttons: [
      //               'copy',
      //               'excel',
                   
      //               'pdf',
      //               'print'
			// 		]
      //   }],
			"ajax": "<?= base_url('admin/prestasi/prestasi_json/')?>",
			"order": [
				[2, 'desc']
			],
			
		});

		$('select').on('change', function() {
			var s_form = $("#filter_search").serialize();
			$.ajax({
				data: s_form,
				type: 'post',
				url: '<?php echo base_url(); ?>admin/prestasi/search',
				async: true,
				success: function(output) {
					table.ajax.reload();
					console.log(s_form);
				}
			});
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