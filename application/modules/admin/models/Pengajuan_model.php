<?php
class Pengajuan_model extends CI_Model
{
	public function getVerifiedPengajuan()
	{
		$id_status = " AND ps.status_id = 7";

		$query = $this->db->query(
			"SELECT 
			*,
			date_format(ps.date, '%d %M %Y ') as date,
			date_format(ps.date, '%H:%i') as time
			FROM tr_pengajuan p
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM tr_pengajuan_status ps WHERE ps.pengajuan_id = p.pengajuan_id) 
			$id_status"
		);
		return $query->result_array();
	}

	public function getPengajuanPerPeriode($id_periode)
	{
		$query = $this->db->query(
			"SELECT
			*,
			pp.STUDENTID AS mhs_id,
			date_format(ps.date, '%d %M %Y ') as date,
			date_format(ps.date, '%H:%i') as time
			FROM tr_pengajuan p
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN tr_penerbitan_pengajuan pp ON pp.id_pengajuan = p.pengajuan_id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = pp.STUDENTID
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM tr_pengajuan_status ps WHERE ps.pengajuan_id = p.pengajuan_id)
			AND pp.id_periode = '$id_periode' "
		);
		return $query->result_array();
	}

	public function get_pengajuan($role)
	{

		echo $this->session->userdata('role');
		echo $this->session->userdata('id_prodi');

		if ($this->session->userdata('role') == 1) {
			$prodi = '';
		} else {
			$prodi = "AND d.DEPARTMENT_ID = '" . $this->session->userdata('id_prodi') . "'";
		}
		

		if ($role == 0) {
			$id_status = ' AND ps.status_id NOT IN (1)';
			
		} else if ($role == 1) {
			$id_status = "AND ps.status_id =  2";
		} else if ($role == 2) {
			$id_status = "AND (ps.status_id =  2 OR ps.status_id = 5 OR ps.status_id = 7)";
		} else if ($role == 5) {
			$id_status = "AND ps.status_id NOT IN (1) AND m.DEPARTMENT_ID = " . $this->session->userdata('id_prodi');
		} else if ($role == 6) {
			$id_status = "AND (ps.status_id =  3 OR ps.status_id = 7)";
		}

		$query = $this->db->query(
			"SELECT 
			*,
			date_format(ps.date, '%d %M %Y ') as date,
			date_format(ps.date, '%H:%i') as time
			FROM tr_pengajuan p
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = (SELECT MAX(status_id) FROM tr_pengajuan_status ps WHERE ps.pengajuan_id = p.pengajuan_id) 
			$id_status $prodi AND NOT ps.status_id=20" 
		);
		return $result = $query->result_array();
	}

	public function pengajuan_perlu_diproses($tahun, $sem)
	{
		// $this->db->query("SELECT * FROM tr_pengajuan p 
		// LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
		// WHERE ps.status_id != 1 
		// AND ps.status_id != 10")->num_rows();

		if ($_SESSION['role'] == 5) {
			$prodi_user =  $this->session->userdata('id_prodi');

			if ($tahun) {
				if ($sem) {
					if ($sem == 1) {
						$tahun = $tahun;
						$where = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12 AND m.DEPARTMENT_ID =". $prodi_user;
	
					} elseif ($sem == 2) {
						$tahun = $tahun;					
						$where = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6 AND m.DEPARTMENT_ID =". $prodi_user;
					}
				} else {
					$tahun = $tahun;
					$where = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6 AND m.DEPARTMENT_ID =". $prodi_user.") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12 AND m.DEPARTMENT_ID =". $prodi_user.")";
				}
			} else {
				echo $tahun = date('Y');
				$where = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6 AND m.DEPARTMENT_ID =". $prodi_user.") OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12 AND m.DEPARTMENT_ID =". $prodi_user.")";
			}

		} else {

			if ($tahun) {
				if ($sem) {
					if ($sem == 1) {
						$tahun = $tahun;
						$where = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12";
	
					} elseif ($sem == 2) {
						$tahun = $tahun;					
						$where = "WHERE ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6";
					}
				} else {
					$tahun = $tahun;
					$where = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6) OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12)";
				}
			} else {
				echo $tahun = date('Y');
				$where = "WHERE (ps.status_id = 2 AND YEAR(ps.date) = $tahun+1 AND MONTH(ps.date) BETWEEN 1 AND 6) OR (ps.status_id = 2 AND YEAR(ps.date) = $tahun AND MONTH(ps.date) BETWEEN 7 AND 12)";
			}
		

		}

		$result = $this->db->query("SELECT p.*, ps.status_id, m.STUDENTID, ps.catatan FROM tr_pengajuan p 
		LEFT JOIN v_mahasiswa m ON m.STUDENTID=p.nim 
		LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id=p.pengajuan_id		
		$where			
		")->num_rows();

		return $result;

	}

	public function pengajuan_selesai()
	{
		// $this->db->query("SELECT * FROM tr_pengajuan p 
		// LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
		// WHERE ps.status_id != 1 
		// AND ps.status_id = 10")->num_rows();


		if ($_SESSION['role'] == 5) {
			$prodi_user = $this->db->select('prodi')
				->from('users')
				->where([
					'id' => $_SESSION['user_id']
				])
				->get()
				->row_object()
				->prodi;

			return $this->db->select("*")
				->from("tr_penerbitan_pengajuan pp")
				->join("v_mahasiswa m", "m.STUDENTID=pp.STUDENTID")
				// ->join("tr_pengajuan_status ps", "ps.pengajuan_id=pp.id_pengajuan")
				->where([
					"m.DEPARTMENT_ID =" => $prodi_user,
				])
				->get()
				->num_rows();
		} else {
			return $this->db->select("*")
				->from("tr_penerbitan_pengajuan pp")
				->join("v_mahasiswa m", "m.STUDENTID=pp.STUDENTID")
				// ->join("tr_pengajuan_status ps", "ps.pengajuan_id=pp.id_pengajuan")
				->get()
				->num_rows();
		}
	}

	public function get_arsip_pengajuan($DEPARTMENT_ID = 0, $ID_JENIS_PENGAJUAN = 0)
	{
		$query = $this->db->query(
			"SELECT 
			*,
			date_format(ps.date, '%d %M %Y ') as date,
			date_format(ps.date, '%H:%i') as time
			FROM tr_pengajuan p
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			-- LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE ps.status_id = 10"
				// . ($DEPARTMENT_ID == 0 ? "" : "AND d.DEPARTMENT_ID = $DEPARTMENT_ID")
				. ($DEPARTMENT_ID == 0 ? "" : "AND m.DEPARTMENT_ID = '$DEPARTMENT_ID'")
				. ($ID_JENIS_PENGAJUAN == 0 ? "" : " AND jp.Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN")
		);
		return $query->result_array();
	}

	public function getbulan($tahun, $sem)
	{

		$prodinya = $_SESSION['id_prodi'];

	if ($prodinya == 0) {
		$prodi = '';
	} else {
		$prodi = 'AND DEPARTMENT_ID = ' . $prodinya;
	}

	if ($tahun) {
		if ($sem) {
			if ($sem == 1) {
				$tahun = $tahun;
				$where = "WHERE status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi;
	
			} elseif ($sem == 2) {
				$tahun = $tahun;					
				$where = "WHERE status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi;
			}
		} else {
			$tahun = $tahun;
			$where = "WHERE ( status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi . ") OR ( status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi . ")";
				}
	} else {
		$tahun = date('Y');
		$where = "WHERE ( status = 1 AND YEAR(tanggal) = $tahun+1 AND MONTH(tanggal) BETWEEN 1 AND 6 " . $prodi . ") OR ( status = 1 AND YEAR(tanggal) = $tahun AND MONTH(tanggal) BETWEEN 7 AND 12 " . $prodi . ")";
	}

		return $this->db->query(
			"SELECT 
			distinct(MONTH(tanggal)) AS bulan 
			FROM v_prestasi
			$where
			ORDER BY bulan ASC
			"
		)->result_array();


	}
	public function get_tahun()
	{
		return $this->db->query(
			"SELECT 
			-- distinct(FORMAT (ps.date, 'MMMM')) AS bulan 
			distinct(YEAR(ps.date)) AS tahun 
			FROM tr_pengajuan_status ps
			WHERE ps.status_id = 2 
			-- AND FORMAT (ps.date, 'yyyy') = YEAR(NOW())
			ORDER BY tahun ASC
			"
		)->result_array();

	}

	public function get_detail_pengajuan($pengajuan_id)
	{
		return $this->db->query(
			"SELECT *, m.*
			FROM tr_pengajuan p
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id 		
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE p.pengajuan_id = $pengajuan_id 
			AND s.status_id = (
				SELECT status_id FROM tr_pengajuan_status 
				WHERE status_pengajuan_id = (
				SELECT MAX(status_pengajuan_id) FROM tr_pengajuan_status 
				WHERE pengajuan_id = $pengajuan_id
				)
			)"
		)->row_array();
		// return $pengajuan_id;
	}

	public function get_jenis_pengajuan()
	{
		$query = $this->db->query("SELECT mj.*, mkjp.kategori_pengajuan FROM mstr_jenis_pengajuan mj LEFT JOIN mstr_kategori_jenis_pengajuan mkjp ON mkjp.id=mj.parent");

		return $result = $query->result_array();
	}
	public function get_kategori_jenis_pengajuan()
	{
		$query = $this->db->query("SELECT * FROM  mstr_kategori_jenis_pengajuan");

		return $result = $query->result_array();
	}

	public function get_jenis_pengajuan_byid($id)
	{

		// $query1 = $this->db->query("SELECT 
		// * 
		// FROM mstr_jenis_pengajuan jp
		// LEFT JOIN mstr_penghargaan_rekognisi_mahasiswa reward ON reward.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id
		// where jp.Jenis_Pengajuan_Id='$id'");

		$query1 = $this->db->select("*,jp.Jenis_pengajuan_Id as jpi")
			->from('mstr_jenis_pengajuan jp')
			->join("mstr_penghargaan_rekognisi_mahasiswa reward", "reward.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id", "LEFT")
			->where([
				'jp.Jenis_Pengajuan_Id' => $id
			])
			->get();
		$result1 = $query1->row_array();

		$query2 = $this->db->query("SELECT field_id FROM mstr_pengajuan_field where Jenis_Pengajuan_Id=$id AND terpakai = 1");
		$result2 = $query2->result_array();

		$result3 = $this->db->get_where(
			"mstr_penghargaan_rekognisi_mahasiswa",
			["Jenis_Pengajuan_Id" => $id]
		)->result_array();

		return array($result1, $result2, $result3);
	}

	public function edit_kategori_pengajuan($data, $id)
	{
		return $this->db->update('kategori_pengajuan', $data, array('id' => $id));
	}

	public function editFieldsPengajuan($dataFieldCheck, $id)
	{
		$not_exist_fields_data = $dataFieldCheck['not_exist_fields_data'];
		$sent_fields_data = $dataFieldCheck['sent_fields_data'];

		foreach ($sent_fields_data as $key => $field) {

			$data = [
				'Jenis_Pengajuan_Id' => $id,
				'field_id' => $field,
				'terpakai' => 1,
				'urutan' => $key
			];

			// menambahkan field yang belum ada
			$datafield_exist = $this->db->query(
				"SELECT field_id FROM mstr_pengajuan_field 
									WHERE Jenis_Pengajuan_Id = $id AND field_id IN (
										SELECT field_id FROM mstr_pengajuan_field 
										WHERE Jenis_Pengajuan_Id = $id AND field_id = " . $data['field_id'] . " )"
			)->num_rows();

			if ($datafield_exist == 0) {
				$this->db->insert('mstr_pengajuan_field', $data);
			} else {
				$field_property = [
					'terpakai' => 1,
					'urutan' => $key
				];

				$this->db->update(
					'mstr_pengajuan_field',
					$field_property,
					[
						'Jenis_Pengajuan_Id' => $id,
						'field_id' => $data['field_id']
					]
				);
			}
			//1,3,69,70,71,72,73

			//mengecek field yang tidak digunakan
			// $id_field = $data['field_id'];
		}

		$query_fields = $this->db->query(
			"SELECT field_id FROM mstr_pengajuan_field 
			WHERE Jenis_Pengajuan_Id = $id 
			-- AND field_id = $field
			 AND field_id NOT IN ($not_exist_fields_data)"
		);

		$non_exist_fields = $query_fields->result_array();

		$field_property = [
			'terpakai' => 0
		];

		if ($query_fields->num_rows() > 0) {
			foreach ($non_exist_fields as $field_tidak_dipakai) {
				$this->db->update(
					'mstr_pengajuan_field',
					$field_property,
					[
						'Jenis_Pengajuan_Id' => $id,
						'field_id' => $field_tidak_dipakai['field_id']
					]
				);
			}
		}

		// return $non_exist_fields;
		// return $datafield_exist;
	}

	public function tambah_field_pengajuan($data)
	{
		return $this->db->insert('mstr_pengajuan_field', $data);
	}

	public function tambah_jenis_pengajuan($data)
	{
		return $this->db->insert('mstr_jenis_pengajuan', $data);
	}

	public function get_pengajuan_status($id_pengajuan)
	{
		return $this->db->select('ss.*, DATE_FORMAT(ss.date,"%d %M %Y") as date, st.status')
			->from('pengajuan_status ss')
			->join('status st', 'ss.id_status=st.id', 'left')
			->where(array('ss.id_pengajuan' => $id_pengajuan, 'ss.id_status !=' => '0', 'ss.id_status !=' => '1'))->get()->result_array();
	}

	//class Kategori
	public function get_kat_keterangan_pengajuan()
	{
		return $this->db->get('kat_keterangan_pengajuan')->result_array();
	}

	public function get_timeline($id_pengajuan)
	{
		$query = $this->db->query("SELECT ss.id_status, DATE_FORMAT(ss.date, '%d %M') as date,  DATE_FORMAT(ss.date, '%H:%i') as time,  DATE_FORMAT(ss.date, '%d %M %Y') as date_full, s.status, s.badge          
        FROM pengajuan_status ss
        LEFT JOIN status s ON s.id = ss.id_status  
        where ss.id_pengajuan='$id_pengajuan'
        ORDER BY ss.id DESC
        ");
		return $result = $query->result_array();
	}

	public function getAllFieldsPengajuan($jenis_pengajuan_id, $aktif)
	{
		if($jenis_pengajuan_id) {
			$query = $this->db->query(
				"SELECT * FROM mstr_fields
			LEFT JOIN mstr_pengajuan_field ON mstr_pengajuan_field.field_id = mstr_fields.field_id 
				WHERE mstr_pengajuan_field.Jenis_Pengajuan_Id =" . $jenis_pengajuan_id .
					" AND mstr_pengajuan_field.terpakai=" . $aktif
			);
		} else {
			$query = $this->db->query(
				"SELECT * FROM mstr_fields
			LEFT JOIN mstr_pengajuan_field ON mstr_pengajuan_field.field_id = mstr_fields.field_id 
			AND mstr_pengajuan_field.terpakai=" . $aktif
			);

		}

		return $result = $query->result_array();
	}

	public function edit_form_field($data, $id)
	{
		return $this->db->update('mstr_fields', $data, array('field_id' => $id));
	}

	public function edit_jenis_pengajuan($data, $id)
	{
		return $this->db->update('mstr_jenis_pengajuan', $data, array('Jenis_Pengajuan_Id' => $id));
	}

	public function get_status_pengajuan_perbulan($status, $tahun)
	{
		if ($_SESSION['role'] == 5) {
			$where = 'AND ';
		} else {
			$where = '';
		}

		//mengambil data keseluruhan pengajuan berdsarkan status
		return $this->db->query("SELECT COUNT(status_pengajuan_id) jumlah, 
		MONTH(date) bulan 
		FROM tr_pengajuan_status 
		WHERE status_id IN($status) AND YEAR(date)=$tahun
		GROUP BY MONTH(date) 
		ORDER BY MONTH(date) ASC

		")->result_array();
	}
}
