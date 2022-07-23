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
		// 2022 Ganjil = September - Februari 2022
		// 2022 genap = Maret - Agustus 2023

		if(!$tahun) {
			$akademik = $this->current_akademik();
			$tahun = $akademik[0];
			$sem = 0;
		}

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
					$where_pengajuan_perlu_diproses = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 9 AND 12 " . $prodi .") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) NOT BETWEEN 3 AND 8 " . $prodi .")";
					$where_prestasi = "WHERE (status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 9 AND 12 " . $prodi. ") OR (status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) NOT BETWEEN 3 AND 8 " . $prodi. ")";

				} elseif ($sem == 2) {
					$tahun = $tahun;					
					$where_pengajuan_perlu_diproses = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 3 AND 8 " . $prodi;
					$where_prestasi = "WHERE status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 3 AND 8 " . $prodi;
				}
			} else {		
				$tahun = $tahun;
				$where_pengajuan_perlu_diproses = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 9 AND 12 " . $prodi.") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 8 " . $prodi.")";
				$where_prestasi = "WHERE (status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 9 AND 12 " . $prodi.") OR (status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 8 ". $prodi.")";
			}
		} else {
			$where_pengajuan_perlu_diproses = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 9 AND 12 " . $prodi.") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 8 " . $prodi.")";
			$where_prestasi = "WHERE (status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 9 AND 12 " . $prodi.") OR (status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 8 ". $prodi.")";
		}

		$data['pengajuan_perlu_diproses'] =  $this->db->query("SELECT p.*, ps.status_id, m.STUDENTID, ps.catatan, YEAR(ps.date) as tahun FROM tr_pengajuan p 
		LEFT JOIN v_mahasiswa m ON m.STUDENTID=p.nim 
		LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id=p.pengajuan_id		
		$where_pengajuan_perlu_diproses		
		ORDER BY tahun ASC
		")->num_rows();

		$data['prestasi'] = $this->db->query("select * FROM v_prestasi $where_prestasi")->num_rows();
		$data['propinsi'] = $this->get_lingkup_kegiatan(1, $tahun, $sem);
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
					$where = "WHERE (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun AND MONTH(vp.tanggal) BETWEEN 9 AND 12 " . $prodi. ") OR (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(vp.tanggal) NOT BETWEEN 3 AND 8 " . $prodi. ")";
		
				} elseif ($sem == 2) {
					$tahun = $tahun;					
					$where = "WHERE tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(vp.tanggal) BETWEEN 3 AND 8 " . $prodi;
				}
			} else {		
				$tahun = $tahun;
				$where = "WHERE (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun AND MONTH(vp.tanggal) BETWEEN 9 AND 12 " . $prodi.") OR (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(vp.tanggal) BETWEEN 1 AND 8 ". $prodi.")";
			}
		} else {
			$tahun = date('Y');
			$where = "WHERE (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun AND MONTH(vp.tanggal) BETWEEN 9 AND 12 " . $prodi.") OR (tps.status_id = 10 AND tf.value = $lingkup AND tf.field_id = 1 AND YEAR(vp.tanggal) = $tahun+1 AND MONTH(vp.tanggal) BETWEEN 1 AND 8 ". $prodi.")";
		}

		return $this->db->query("select tf.value, vp.tanggal FROM tr_field_value tf 
		LEFT JOIN tr_pengajuan_status tps ON tf.pengajuan_id=tps.pengajuan_id
		LEFT JOIN v_prestasi vp ON vp.id_pengajuan=tps.pengajuan_id
		
		$where")->num_rows();
	}

	function current_akademik() {
		$curbulan = date('m');
		$curtahun = date('Y');

		if($curbulan >= 3 AND $curbulan < 9) {
			$tahun = $curtahun -1;
			$sem = 2;
		} else {
			$tahun = $curtahun;
			$sem = 1;
		}

		return array($tahun, $sem);
	}
	
}
