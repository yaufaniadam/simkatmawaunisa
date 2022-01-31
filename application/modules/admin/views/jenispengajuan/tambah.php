<?php
echo form_open_multipart(base_url('admin/jenispengajuan/tambah/'), 'class="form-horizontal"');
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
	.alert.simpan {
		display: none;
	}

	#sortable1 {
		width: 100%;
	}

	#sortable2 {
		width: 100%;
	}

	#sortable1,
	#sortable2 {
		border: 1px solid #eee;

		min-height: 20px;
		list-style-type: none;
		margin: 0;
		padding: 5px 0 0 0;
		float: left;
		margin-right: 10px;
	}

	#sortable1 li,
	#sortable2 li {
		margin: 0 5px 5px 5px;
		padding: 5px;
		font-size: 14px;
		cursor: move;
	}

	.error {
		color: red;
	}
</style>

<div class="row">
	<div class="col-md-8 offset-md-2">

		<!-- fash message yang muncul ketika proses penghapusan data berhasil dilakukan -->
		<?php if ($this->session->flashdata('msg') != '') : ?>
			<div class="alert alert-success flash-msg alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4>Sukses!</h4>
				<?= $this->session->flashdata('msg'); ?>
			</div>
		<?php endif; ?>
		<?php if (isset($msg) || validation_errors() !== '') : ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="fa fa-exclamation"></i> Terjadi Kesalahan</h4>
				<?= validation_errors(); ?>
				<?= isset($msg) ? $msg : ''; ?>
			</div>
		<?php endif; ?>

	</div>

	<div class="col-md-8 offset-md-2">
		<div class="card card-success card-outline">
			<ul class="nav nav-tabs pt-3 pl-4  bg-perak">
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('admin/jenispengajuan'); ?>"><i class="fas fa-fw fa-table"></i> Jenis pengajuan</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="<?= base_url('admin/jenispengajuan/tambah'); ?>"><i class="fas fa-fw fa-plus"></i> Tambah Jenis pengajuan</a>
				</li>			
			</ul>
			<div class="card-body box-profile">

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Jenis Pengajuan</label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : '';  ?>" name="Jenis_Pengajuan" class="form-control <?= (form_error('Jenis_Pengajuan')) ? 'is-invalid' : ''; ?>" id="Jenis_Pengajuan">
						<span class="invalid-feedback"><?php echo form_error('Jenis_Pengajuan'); ?></span>
					</div>
				</div>	

				<div class="form-group row">
					<label for="deskripsi" class="col-md-3 control-label">Deskripsi</label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('deskripsi') : '';  ?>" name="deskripsi" class="form-control <?= (form_error('deskripsi')) ? 'is-invalid' : ''; ?>" id="deskripsi">
						<span class="invalid-feedback"><?php echo form_error('deskripsi'); ?></span>
					</div>
				</div>	

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Kategori</label>
					<div class="col-md-9">
						<select name="parent" class="form-control <?= (form_error('parent')) ? 'is-invalid' : ''; ?>" id="parent" >		<option value=''>Pilih Kategori</option>
							<?php							
								foreach($kategori_jenis_pengajuan as $jp) { ?>
									<option value="<?= $jp['id']; ?>" <?= (validation_errors()) ? (set_select('parent',$jp['id'])) : '' ?>><?= $jp['kategori_pengajuan']; ?></option>
							<?php } ?>							
						</select>
						<span class="invalid-feedback"><?php echo form_error('parent'); ?></span>
					</div>
				</div>	

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Jumlah Anggota</label>
					<div class="col-md-9">
						<select name="jumlah_anggota" class="form-control <?= (form_error('parent')) ? 'is-invalid' : ''; ?>" id="jumlah_anggota">		
							<option value=''>Pilih Jumlah Anggota</option>							
							<option value="individu" <?= (validation_errors()) ? (set_select('jumlah_anggota','individu')) : '' ?>>Individu</option>
							<option value="beregu" <?= (validation_errors()) ? (set_select('jumlah_anggota','beregu')) : '' ?>>Beregu</option>
							<option value="kelompok" <?= (validation_errors()) ? (set_select('jumlah_anggota','kelompok')) : '' ?>>Kelompok</option>
													
						</select>
						<span class="invalid-feedback"><?php echo form_error('jumlah_anggota'); ?></span>
					</div>
				</div>	
				
				<div class="form-group row">
					<label for="kode" class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<input type="submit" name="submit" value="Tambah Jenis Pengajuan" class="btn btn-perak btn-block">
					</div>
				</div>

			</div>
		</div>
	</div>

</div>
<?php echo form_close(); ?>
