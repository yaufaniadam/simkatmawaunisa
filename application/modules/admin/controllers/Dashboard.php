<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;

class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengajuan_model');
		$this->load->library('mailer');
	}

	public function index()
	{
		$data['pengajuan_perlu_diproses'] = $this->pengajuan_model->pengajuan_perlu_diproses();
		$data['pengajuan_selesai'] = $this->pengajuan_model->pengajuan_selesai();
		$data['nama_bulan'] = $this->pengajuan_model->getbulan();
		$data['jenis_pengajuan'] = $this->db->query(
			"SELECT 
			DISTINCT(jp.Jenis_Pengajuan),
			jp.Jenis_Pengajuan_Id
			FROM Tr_Pengajuan p 
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id"
		)->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index';
		$this->load->view('layout/layout', $data);
	}

	public function sendmails() {
		echo "kirim email";

		

		$mail = new PHPMailer(true); //Argument true in constructor enables exceptions

    $mail->From = 'igalowbgt@gmail.com';
    $mail->FromName = 'adam';
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'igalowbgt@gmail.com';                     // SMTP username
    $mail->Password   = 'Nining15';
    // $mail->Password   = decrypt_url($this->get_settings('password_email'));
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    $mail->setFrom('igalowbgt@gmail.com', 'Adam');
    $mail->isHTML(true);

		$mail->addAddress('yaufani@gmail.com');

		$mail->Subject = "hellow";
		$mail->Body = "ini body email";

		$mail->send();

		$mail->ClearAddresses();


	}
}
