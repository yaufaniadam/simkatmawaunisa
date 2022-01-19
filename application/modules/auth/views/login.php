<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="<?= base_url() ?>public/dist/img/favicon.ico" type="image/x-icon" />

	<title>Login SIMKATMAWA UNISA</title>

	<!-- Custom fonts for this template-->
	<link href="<?= base_url() ?>/public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="<?= base_url() ?>/public/dist/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-login">

	<div class="container">

		<!-- Outer Row -->
		<div class="row justify-content-center">

			<div class="col-xl-10 col-lg-12 col-md-9">

				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
							<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
							<div class="col-lg-6">
								<div class="p-5">
									<div class="text-center">
										<img src="<?= base_url('public/dist/img/logologin.png'); ?>" />
										<h1 class="h5 text-gray-900 mb-4">Login</h1>

									</div>

									<?php if (isset($msg) || validation_errors() !== '') : ?>
										<div class="alert alert-warning alert-dismissible">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
											<h4><i class="icon fa fa-warning"></i> Alert!</h4>
											<?= validation_errors(); ?>
											<?= isset($msg) ? $msg : ''; ?>
										</div>
									<?php endif; ?>
									<?php if ($this->session->flashdata('error')) : ?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
											<?= $this->session->flashdata('error') ?>
										</div>
									<?php endif; ?>
									<?php if ($this->session->flashdata('success')) : ?>
										<div class="alert alert-success">
											<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
											<?= $this->session->flashdata('success') ?>
										</div>
									<?php endif; ?>

									<?php echo form_open(base_url('auth/login/' . $ref), 'class="user" '); ?>


									<div class="form-group">
										<input type="username" name="username" class="form-control form-control-user" id="username" aria-describedby="username" placeholder="<?= (!$ref) ? "Email Anda" : "Username"; ?>">
									</div>
									<div class="form-group">
										<input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Password">
									</div>

									<input type="submit" name="submit" class="btn btn-success btn-user btn-block" value="Login">

									<hr>

									<?php echo form_close(); ?>
									<hr>


								</div>
							</div>
						</div>

					</div>

				</div>
				<div class="text-center">
					<a class="small" href="<?= base_url('auth/login/non-sso'); ?>"><i class="fa fa-lock"></i></a>
				</div>
			</div>

		</div>

	</div>

	<!-- Bootstrap core JavaScript-->
	<script src="<?= base_url() ?>/public/vendor/jquery/jquery.min.js"></script>
	<script src="<?= base_url() ?>/public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="<?= base_url() ?>/public/vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="<?= base_url() ?>/public/js/sb-admin-2.min.js"></script>

</body>

</html>
