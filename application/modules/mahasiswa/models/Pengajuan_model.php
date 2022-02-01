<?php
class Pengajuan_model extends CI_Model
{
	public function get_surat_bymahasiswa($id_mhs)
	{
		$query = $this->db->query("SELECT s.id as id_surat, ss.id_status, k.kategori_surat, st.status, st.badge, DATE_FORMAT(ss.date, '%d %M') as date,  DATE_FORMAT(ss.date, '%H:%i') as time,  DATE_FORMAT(ss.date, '%d %M %Y') as date_full
        FROM surat s
        LEFT JOIN surat_status ss ON ss.id_surat = s.id
        LEFT JOIN status st ON st.id = ss.id_status
        LEFT JOIN kategori_surat k ON k.id = s.id_kategori_surat
        WHERE s.id_mahasiswa='$id_mhs' AND ss.id_status = (SELECT MAX(id_status) FROM surat_status WHERE id_surat=s.id) 
        ORDER BY s.id DESC        
        ");
		return $result = $query->result_array();
	}
	public function get_detail_surat($id_surat)
	{
		$query = $this->db->query("SELECT 
        s.id, 
        s.id_kategori_surat, 
        s.id_mahasiswa, 
        k.kategori_surat, 
        k.template, 
        k.kat_keterangan_surat, 
        k.klien, 
        ss.id_status, 
        st.status, 
        st.icon,
        st.badge, 
        st.alert, 
        u.id_prodi, 
        pr.prodi, 
        u.fullname, 
        u.username,
        n.id as id_notif
        FROM 
        surat s
        LEFT JOIN users u ON u.id = s.id_mahasiswa        
        LEFT JOIN surat_status ss ON ss.id_surat = s.id        
        LEFT JOIN status st ON st.id = ss.id_status
        LEFT JOIN prodi pr ON pr.id = u.id_prodi    
        LEFT JOIN kategori_surat k ON k.id = s.id_kategori_surat
        LEFT JOIN notif n ON n.id_surat = s.id
        WHERE 
        s.id = '$id_surat' 
        AND 
        ss.id_status= (
            SELECT 
            MAX(id_status) 
            FROM 
            surat_status 
            WHERE 
            id_surat ='$id_surat'
            )
        ");
		return $result = $query->row_array();
	}

	/*
    Mengambil kategori surat berdasarkan klien (user role) 

    $klien =
    m = mahasiswa
    d = dosen
    t = tu
    a = admin
    d = direktur pasca
    k = kaprodi

    $prodi = nama prodi, ada bbrp surat yang hanya khusus untuk prodi tertentu
    $aktif = status aktif/tidak aktif mahasiswa pada semester dan tahun ini jika $klien = 'm'
    */
	public function get_jenis_pengajuan($id)
	{
		if ($id == 0) {
		} else {
			$query = $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE Jenis_Pengajuan_Id =$id");

			$isparent = $this->db->query("SELECT *  FROM mstr_jenis_pengajuan WHERE parent =$id");

			if ($isparent->num_rows() > 0) {
				$result =  $isparent->result_array();
			} else {
				$result = $query->result_array();
			}
		}

		return $result;
	}

	public function kategori_pengajuan()
	{
		return $this->db->query("SELECT distinct(mj.parent), mkjp.kategori_pengajuan FROM mstr_jenis_pengajuan mj LEFT JOIN mstr_kategori_jenis_pengajuan mkjp ON mkjp.id=mj.parent WHERE mj.parent <> '' ")->result_array();
	}
	
	public function prestasi($id)
	{
		return $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE parent = '$id' AND aktif = '1' ORDER BY Jenis_Pengajuan ASC")->result_array();
	}

	public function get_detail_pengajuan($pengajuan_id)
	{
		return $this->db->query(
			"SELECT *, jp.jenis_pengajuan FROM tr_pengajuan p
			LEFT JOIN mstr_jenis_pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id 		
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			LEFT JOIN v_mahasiswa m ON p.nim = m.STUDENTID
			LEFT JOIN mstr_department d ON d.DEPARTMENT_ID = m.DEPARTMENT_ID
			WHERE p.pengajuan_id = $pengajuan_id AND s.status_id = (SELECT status_id FROM tr_pengajuan_status WHERE status_pengajuan_id = (
			SELECT MAX(status_pengajuan_id) FROM tr_pengajuan_status WHERE pengajuan_id = $pengajuan_id
			))"
		)->row_object();
	}

	public function getAnggota($search)
	{
		$this->db->select('*');
		$this->db->from('v_mahasiswa');
		$this->db->like('FULLNAME', $search);
		$this->db->or_like('STUDENTID', $search);
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}

	function getPengajuanSaya($id_jenis_pengajuan = 0)
	{
		$nim = $_SESSION['studentid'];
		return $this->db->query(
			"SELECT 
			p.*,
			jp.Jenis_Pengajuan,
			m.FULLNAME,
			m.NAME_OF_FACULTY,
			m.DEPARTMENT_ID,
			ps.pic,
			ps.status_id,
			ps.date,
			s.status,
			s.status_id,
			s.badge,
			FORMAT (ps.date, 'hh:mm:ss ') as time
			FROM tr_pengajuan p 
			LEFT JOIN mstr_jenis_pengajuan jp ON p.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id
			LEFT JOIN v_mahasiswa m ON m.STUDENTID = p.nim
			LEFT JOIN tr_pengajuan_status ps ON ps.pengajuan_id = p.pengajuan_id
			LEFT JOIN mstr_status s ON s.status_id = ps.status_id
			WHERE p.nim = '$nim' "
				. ($id_jenis_pengajuan == 0
					? ""
					: ($id_jenis_pengajuan == 12 ? "AND jp.parent = 12 "
						: " AND p.Jenis_Pengajuan_Id = $id_jenis_pengajuan")) .
				"AND ps.status_pengajuan_id = (SELECT MAX(status_pengajuan_id) 
													FROM tr_pengajuan_status  
													WHERE pengajuan_id = p.pengajuan_id)"
		)->result_array();

		// return $this->db->query("SELECT * FROM v_mahasiswa")->result_array();
	}
	// AND p.Jenis_Pengajuan_Id = $id_jenis_pengajuan
	public function getPembimbing($search)
	{
		$this->db->select('*');
		$this->db->from('v_dosen');
		$this->db->like('nama', $search);
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}

	public function get_spesific_pengajuan_fields($pengajuan_id)
	{
		return $this->db->query(
			"SELECT * FROM tr_pengajuan
			LEFT JOIN mstr_pengajuan_field ON mstr_pengajuan_field.Jenis_Pengajuan_Id = tr_pengajuan.Jenis_Pengajuan_Id
			LEFT JOIN tr_pengajuan_status ON tr_pengajuan_status.id_pengajuan = tr_pengajuan.pengajuan_id
			LEFT JOIN mstr_fields ON mstr_fields.field_id = mstr_pengajuan_field.field_id
			LEFT JOIN mstr_status ON mstr_status.status_id = tr_pengajuan_status.id_status
			LEFT JOIN mstr_jenis_pengajuan ON mstr_jenis_pengajuan.Jenis_Pengajuan_Id = tr_pengajuan.Jenis_Pengajuan_Id
			WHERE tr_pengajuan.pengajuan_id = $pengajuan_id AND mstr_pengajuan_field.terpakai = 1"
		)->result_array();
	}

	public function get_keterangan_surat()
	{
	}
	public function tambah($data)
	{
		return $this->db->insert('tr_pengajuan', $data);
	}
	function simpan_upload($judul, $gambar)
	{
		$hasil = $this->db->query("INSERT INTO keterangan_surat(ket_value,gambar) VALUES ('$judul','$gambar')");
		return $hasil;
	}

	public function get_timeline($id_surat)
	{
		$query = $this->db->query("SELECT ss.id_status, DATE_FORMAT(ss.date, '%d %M') as date,  DATE_FORMAT(ss.date, '%H:%i') as time,  DATE_FORMAT(ss.date, '%d %M %Y') as date_full, s.status, s.badge          
        FROM surat_status ss
        LEFT JOIN status s ON s.id = ss.id_status  
        where ss.id_surat='$id_surat'
        ORDER BY ss.id DESC
        ");
		return $result = $query->result_array();
	}

	public function get_no_surat($id_surat)
	{
		$no_surat = $this->db->query("select ns.no_surat, ns.instansi, kts.kode, ts.kode_tujuan, us.kode as kode_us, DATE_FORMAT(tanggal_terbit, '%c') as bulan, DATE_FORMAT(tanggal_terbit, '%Y') as tahun, DATE_FORMAT(tanggal_terbit, '%c %M %Y') as tanggal_full from no_surat ns 
			LEFT JOIN kat_tujuan_surat kts ON kts.id=ns.kat_tujuan_surat
			LEFT JOIN tujuan_surat ts ON ts.id=ns.tujuan_surat
			LEFT JOIN urusan_surat us ON us.id=ns.urusan_surat
			where ns.id_surat= $id_surat
            ")->row_array();

		return $no_surat;
	}
}
