<?php defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengajuan_model');
		// echo check merges
	}

	public function index()
	{
		// $data['pengajuan_perlu_diproses'] = $this->pengajuan_model->pengajuan_perlu_diproses();
		// $data['pengajuan_selesai'] = $this->pengajuan_model->pengajuan_selesai();
		// $data['nama_bulan'] = $this->pengajuan_model->getbulan();
		// $data['jenis_pengajuan'] = $this->db->query(
		// 	"SELECT 
		// 	DISTINCT(jp.Jenis_Pengajuan),
		// 	jp.Jenis_Pengajuan_Id
		// 	FROM Tr_Pengajuan p 
		// 	LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id"
		// )->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'blank';
		$data['menu'] = 'dashboard';
		$this->load->view('layout/layout', $data);
	}
	public function dasbor()
	{
		// $data['pengajuan_perlu_diproses'] = $this->pengajuan_model->pengajuan_perlu_diproses();
		// $data['pengajuan_selesai'] = $this->pengajuan_model->pengajuan_selesai();
		// $data['nama_bulan'] = $this->pengajuan_model->getbulan();
		// $data['jenis_pengajuan'] = $this->db->query(
		// 	"SELECT 
		// 	DISTINCT(jp.Jenis_Pengajuan),
		// 	jp.Jenis_Pengajuan_Id
		// 	FROM Tr_Pengajuan p 
		// 	LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id"
		// )->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index2';
		$this->load->view('layout/layout', $data);
	}

}
