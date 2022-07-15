<div class="row">
	<!-- Earnings (Monthly) Card Example -->
	<div class="col-12">
	<div class="card shadow mb-4">
			<div class="card-body">
				<a class="nav-link">
					<div class="row">
						<!-- <div class="col-2">
							<?= ($this->session->userdata('role') == 3) ? profPic($this->session->userdata('studentid'), 70) : ''; ?>
						</div> -->
						<div class="col-6">
							<h5><?= $this->session->userdata('fullname'); ?></h5>

							<!-- <span class="badge badge-info"><?= $this->session->userdata('email'); ?></span> -->
							<span class="badge badge-info"><?= $this->session->userdata('studentid'); ?></span>
							<br>
							<span class="badge badge-warning"><?= $this->session->userdata('id_prodi'); ?></span>
						</div>
						<div class="col-6">
							<table class="table table-striped table-bordered">
								<tr>
									<td>No Telp</td>
									<td>
										<?php if ($profil['notelpon'] ) { ?>
										<?= $profil['notelpon'] ?>
										<?php } else { echo "Lengkapi data Saudara di SIMPTT Unisa";} ?>
									</td>
								</tr>
								<tr>
									<td>Email</td>									
									<td>
										<?php if ($profil['email'] ) { ?>
										<?= $profil['email'] ?>
										<?php } else { echo "Lengkapi data Saudara di SIMPTT Unisa";} ?>
									</td>
								</tr>
								<tr>
									<td>No Rekening</td>
									<td>
										<?php if ($profil['norekening'] ) { ?>
										<?= $profil['norekening'] ?> Kode Bank <?= $profil['kdbank'] ?> an. <?= $profil['namadirekening'] ?> 
										<?php } else { echo "Lengkapi data Saudara di SIMPTT Unisa";} ?>
									</td>
								</tr>							
							</table>
						</div>
					</div>
				</a>
			</div>
		</div>
		<!-- <div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Informasi Terbaru</h6>
			</div>
			<div class="card-body">
				<div class="timeline timeline-xs">
					
					<?php foreach ($notif as $notif) { ?>
						<div class="timeline-item">
							<div class="timeline-item-marker">
								<div class="timeline-item-marker-text">
									<?= $notif['bulan_masuk_surat']; ?>
									<?= $notif['tanggal_masuk_surat']; ?>
								</div>
								<div class="timeline-item-marker-indicator bg-<?= $notif['badge']; ?>"></div>
							</div>
							<div class="timeline-item-content">
								<a class="font-weight-bold text-dark notif" name="<?= $notif['id_pengajuan']; ?>" id="<?= $notif['id_notif']; ?>" href="#">
									<?= $notif['judul_notif']; ?>.
								</a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div> -->
	</div>

	<!-- Earnings (Monthly) Card Example -->
	<div class="col-12">
		<div class="row">
			<div class="col-xl-6 col-md-6 mb-4">
				<div class="card border-left-ungutua shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-ungutua text-uppercase mb-1">
									Pengajuan Saya <a href="<?= base_url('mahasiswa/pengajuan/pengajuan_saya/'); ?>" title="Lihat Semua Pengajuan"><i class="fas fa-external-link-alt"></i></a></div>
								<div><span class="h3 mb-0 font-weight-bold text-gray-800"><?= $pengajuan_saya; ?></span> </div>

							</div>
							<div class="col-auto">
								<i class="fas fa-envelope-open fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-6 mb-4">
				<div class="card border-left-danger shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Prestasi Saya <a href="<?= base_url('mahasiswa/pengajuan/prestasi_saya/'); ?>" title="Lihat Semua Prestasi Saya"><i class="fas fa-external-link-alt"></i></a></div>
								<div class="h3 mb-0 font-weight-bold text-gray-800"><?= $prestasi_saya; ?></div>
							</div>
							<div class="col-auto">
								<i class="fas fa-medal fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>

<script type="text/javascript">
	$(".notif").click(function() {
		var nid = this.id
		var pid = this.name
		$.ajax({
			url: "<?= base_url('notif/read_notif/'); ?>" + nid,
			success: function() {
				window.location.href = "<?= base_url('mahasiswa/pengajuan/tambah/'); ?>" + pid
			}
		});
	});
</script>
