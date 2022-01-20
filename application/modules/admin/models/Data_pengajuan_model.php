<?php
class Data_pengajuan_model extends CI_Model
{
	var $table = "tr_pengajuan p";
	var $column_order = [
		'FULLNAME',
		'Jenis_Pengajuan',
		'date'
	];
	var $order = [
		'FULLNAME',
		'Jenis_Pengajuan',
		'date'
	];

	private function _get_data_query()
	{
		$role = $_SESSION['role'];

		if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 5) {
			$prodi = '';
		} else {
			$prodi = "AND u.id_prodi = '" . $this->session->userdata('id_prodi') . "'";
		}
		if ($role == '') {
			$id_status = '';
		} else if ($role == 1) {
			$id_status = "AND ps.status_id =  9";
		} else if ($role == 2) {
			$id_status = "AND (ps.status_id =  2 OR ps.status_id = 5 OR ps.status_id = 7)";
		} else if ($role == 5) {
			$id_status = "AND ps.status_id =  8";
		} else if ($role == 6) {
			$id_status = "AND (ps.status_id =  3 OR ps.status_id = 7)";
		}

		$search = '';
		if (isset($_POST['search']['value'])) {
			$var_search = $_POST['search']['value'];
			$search =  " AND (m.FULLNAME LIKE '%$var_search%' 
			OR jp.Jenis_Pengajuan LIKE '%$var_search%'
			OR date LIKE '%$var_search%') ";
		}

		$limit = '';
		if ($_POST['length'] != -1) {
			$var_length = $_POST['length'];
			$var_start = $_POST['start'];
			$limit = " LIMIT 10 ";
		}

		$order_by = '';
		if (isset($_POST['order'])) {
			$var_order = $_POST['order']['0']['column'];
			$order_by = " ORDER BY $var_order ASC ";
		}



		$query = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM tr_pengajuan p
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN tr_status s ON s.status_id = ps.status_id
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM tr_pengajuan_status ps WHERE ps.pengajuan_id = p.pengajuan_id) 
			$id_status
			$search
			$order_by
			"
		);
		return $query;
	}

	private function _all_data_query()
	{
		$role = $_SESSION['role'];

		if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 5) {
			$prodi = '';
		} else {
			$prodi = "AND u.id_prodi = '" . $this->session->userdata('id_prodi') . "'";
		}
		if ($role == '') {
			$id_status = '';
		} else if ($role == 1) {
			$id_status = "AND ps.status_id =  9";
		} else if ($role == 2) {
			$id_status = "AND (ps.status_id =  2 OR ps.status_id = 5 OR ps.status_id = 7)";
		} else if ($role == 5) {
			$id_status = "AND ps.status_id =  8";
		} else if ($role == 6) {
			$id_status = "AND (ps.status_id =  3 OR ps.status_id = 7)";
		}

		$query = $this->db->query(
			"SELECT 
			*,
			FORMAT (ps.date, 'dd/MM/yyyy ') as date,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM tr_pengajuan p
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN tr_status s ON s.status_id = ps.status_id
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM tr_pengajuan_status ps WHERE ps.pengajuan_id = p.pengajuan_id) 
			$id_status
			"
		);
		return $query;
	}

	public function getDataPengajuan()
	{
		if ($_POST['length'] != -1) {
			$result = $this->_get_data_query()->result_array();
		}
		$result = $this->_get_data_query()->result_array();
		return $result;
	}

	public function _count_filtered_data()
	{
		$result = $this->_get_data_query()->num_rows();
		return $result;
	}

	public function _count_all_data()
	{
		$result = $this->_all_data_query()->num_rows();
		return $result;
	}
}
