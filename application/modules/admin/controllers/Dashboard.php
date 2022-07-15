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
		
		$data['pengajuan_perlu_diproses'] = $this->pengajuan_model->pengajuan_perlu_diproses();

		$prodinya = $this->session->userdata('id_prodi');

		if($prodinya == 0) {
			$prodi ='';
		} else {
			$prodi = 'AND DEPARTMENT_ID = '. $prodinya;
		}
		$where ='WHERE status = 1';
		$data['prestasi'] = $this->db->query("select * FROM v_prestasi $where $prodi")->num_rows();

		
		$data['propinsi'] = $this->get_lingkup_kegiatan(1);
		$data['wilayah'] = $this->get_lingkup_kegiatan(2);
		$data['nasional'] = $this->get_lingkup_kegiatan(3);
		$data['internasional'] = $this->get_lingkup_kegiatan(4);
		$data['pt'] = $this->get_lingkup_kegiatan(5);

		$data['nama_bulan'] = $this->pengajuan_model->getbulan();

		$data['jenis_pengajuan'] = $this->db->query(
			"SELECT 
			DISTINCT(jp.Jenis_Pengajuan),
			jp.Jenis_Pengajuan_Id
			FROM tr_pengajuan p 
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id"
		)->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index';
		$data['menu'] = 'dashboard';
		$this->load->view('layout/layout', $data);
	}
	public function dasbor()
	{
		// $data['pengajuan_perlu_diproses'] = $this->pengajuan_model->pengajuan_perlu_diproses();
		// $data['pengajuan_selesai'] = $this->pengajuan_model->pengajuan_selesai();
		// $data['nama_bulan'] = $this->pengajuan_model->getbulan();
		// $data[ adduan'] = $this->db->query(
		// 	"SELECT 
		// 	DISTINCT(jp.Jenis_Pengajuan),
		// 	jp.Jenis_Pengajuan_Id
		// 	FROM tr_pengajuan p 
		// 	LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id"
		// )->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index2';
		$this->load->view('layout/layout', $data);
	}

	//get jumlah lingkup kegiatan

	function get_lingkup_kegiatan($lingkup){

		
		$prodinya = $this->session->userdata('id_prodi');

		if($prodinya == 0) {
			$prodi ='';
		} else {
			$prodi = 'AND vp.DEPARTMENT_ID = '. $prodinya;
		}

		$where ="WHERE tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 ";

		// return	$this->db->select("tf.value")->from('tr_field_value tf')
		// ->join('tr_pengajuan_status tps', 'tf.pengajuan_id=tps.pengajuan_id', 'left')
		// ->join('v_prestasi vp', 'vp.id_pengajuan=tps.pengajuan_id', 'left')
		// ->where(array('vp.DEPARTMENT_ID' => $prodi ))
		// ->where(array('tf.field_id' => 1))
		// ->where(array('tf.value' => $lingkup))
		// ->where(array('tps.status_id' => 10))->get()->num_rows();

		return $this->db->query("select tf.value FROM tr_field_value tf 
		LEFT JOIN tr_pengajuan_status tps ON tf.pengajuan_id=tps.pengajuan_id
		LEFT JOIN v_prestasi vp ON vp.id_pengajuan=tps.pengajuan_id
		
		$where $prodi")->num_rows();

	}



}
