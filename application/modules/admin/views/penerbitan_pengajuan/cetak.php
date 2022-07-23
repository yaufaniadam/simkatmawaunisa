<?php
header("content-type:Application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=PrestasiMhsUnisa-". $nama_periode .".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="SIMKATMAWA UNISA">
	<meta name="author" content="SIMKATMAWA UNISA">
	<meta name="robots" content="noindex, nofollow">

	<title><?= (isset($title)) ? $title : 'SIMKATMAWA UNISA'; ?></title>


</head>

<body>
	<div class="row">
		<div class="col-12">
			<div class="card card-success card-outline">
				<div class="card-header">
					<div class="row">
						<div class="col-12 text-center">
							<h3 class="mb-0">Nama mahasiswa yang memperoleh reward pada periode <?= $nama_periode; ?></h3>
						</div>
					</div>
				</div>

				<div class="card-body">
					<table id="pengajuan-descs" class="table table-bordered tb-pengajuans">
						<thead>
							<tr>
								<th style="width:20%">Jenis Pengajuan</th>
								<th style="width:35%">Judul </th>
								<th style="width:35%">Nominal Reward </th>
								<th style="width:25%">Dokumen</th>
								<th style="width:25%">Anggota</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($pengajuan as $pengajuan) { ?>
								<tr>

									<td><?= $pengajuan['jenis_pengajuan']; ?></td>
									<td><?= $pengajuan['judul_prestasi']; ?></td>
									<td>Rp<?= number_format($pengajuan['nominal'], 2); ?></td>
									<td>
											<?php foreach ($pengajuan['file'] as $file) { ?>
												<a target="_blank" href="<?= base_url($file['value']); ?>"><?= $file['field']; ?></a> 
											<?php } ?>
									</td>
									<td>
											<?php foreach ($pengajuan['anggota'] as $anggota) { ?>
												<?= get_mahasiswa_by_nim($anggota)['FULLNAME']; ?> - <?= $anggota; ?> (<?= get_mahasiswa_by_nim($anggota)['NAME_OF_DEPARTMENT']; ?>) 
											<?php } ?>
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
</body>