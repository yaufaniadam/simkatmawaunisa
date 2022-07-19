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

	public function index($tahun = null, $sem = null)
	{

		// sem ganjil JULI- Desember. Sem genap januari - Juni
		// berarti 1 tahun akademik mulainya Juli 2021 - Juni 2022
		// contoh 
		// 2022 Ganjil = Jul - Des 2022
		// 2022 genap = Jan - Juni 2023


		$data['selected_tahun'] = $tahun;
		$data['selected_sem'] = $sem;

		//ambil tahun yg sudah ada di db
		$data['alltahun'] = $this->pengajuan_model->get_tahun();

		$prodinya = $this->session->userdata('id_prodi');

		if ($prodinya == 0) {
			$prodi = '';
		} else {
			$prodi = 'AND DEPARTMENT_ID = ' . $prodinya;
		}

		if ($tahun) {
			if ($sem) {
				if ($sem == 1) {
					$tahun = $tahun;
					$where_pengajuan_perlu_diproses = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12 " . $prodi;
					$where_prestasi = "WHERE status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi;

				} elseif ($sem == 2) {
					$tahun = $tahun;					
					$where_pengajuan_perlu_diproses = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6 " . $prodi;
					$where_prestasi = "WHERE status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi;
				}
			} else {
				$tahun = $tahun;
				$where_pengajuan_perlu_diproses = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6 " . $prodi.") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12 " . $prodi.")";
				$where_prestasi = "WHERE (status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi.") OR (status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 ". $prodi.")";
			}
		} else {
			$tahun = date('Y');
			$where_pengajuan_perlu_diproses = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6 ". $prodi.") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12 ". $prodi.")";
			$where_prestasi = "WHERE (status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi.") OR (status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 ". $prodi.")";
		}

		$data['pengajuan_perlu_diproses'] =  $this->db->query("SELECT p.*, ps.status_id, m.STUDENTID, ps.catatan FROM tr_pengajuan p 
		LEFT JOIN v_mahasiswa m ON m.STUDENTID=p.nim 
		LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id=p.pengajuan_id		
		$where_pengajuan_perlu_diproses		
		")->num_rows();

// echo '<pre> pengajuan_perlu_diproses '; print_r($data['pengajuan_perlu_diproses']); echo '</pre>';

		$data['prestasi'] = $this->db->query("select * FROM v_prestasi $where_prestasi")->num_rows();

		// echo '<pre> prestasi '; print_r($data['prestasi']); echo '</pre>';


		$data['propinsi'] = $this->get_lingkup_kegiatan(1, $tahun, $sem);

			// echo '<pre> Juara propinsi '; print_r($data['propinsi']); echo '</pre>';

		$data['wilayah'] = $this->get_lingkup_kegiatan(2, $tahun, $sem);
		$data['nasional'] = $this->get_lingkup_kegiatan(3, $tahun, $sem);
		$data['internasional'] = $this->get_lingkup_kegiatan(4, $tahun, $sem);
		$data['pt'] = $this->get_lingkup_kegiatan(5, $tahun, $sem);

		$data['nama_bulan'] = $this->pengajuan_model->getbulan($tahun, $sem);

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

	function get_lingkup_kegiatan($lingkup, $tahun, $sem)
	{


		$prodinya = $this->session->userdata('id_prodi');

		if ($prodinya == 0) {
			$prodi = '';
		} else {
			$prodi = 'AND vp.DEPARTMENT_ID = ' . $prodinya;
		}

		if ($tahun) {
			if ($sem) {
				if ($sem == 1) {
					$tahun = $tahun;
					$where = "WHERE tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi;

				} elseif ($sem == 2) {
					$tahun = $tahun;	
					$where = "WHERE tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi;
				}
			} else {
				$tahun = $tahun;
				$where = "WHERE ( tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi.") OR (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi .")";
			}
		} else {
			echo $tahun = date('Y');
			$where = "WHERE ( tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi.") OR (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi .")";
		}
		return $this->db->query("select tf.value, vp.tanggal FROM tr_field_value tf 
		LEFT JOIN tr_pengajuan_status tps ON tf.pengajuan_id=tps.pengajuan_id
		LEFT JOIN v_prestasi vp ON vp.id_pengajuan=tps.pengajuan_id
		
		$where")->num_rows();
	}
}
