<?php defined('BASEPATH') or exit('No direct script access allowed');


class Prestasi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();     
    }

    public function index()
    {

        $department_data = $this->db->query("SELECT * FROM mstr_department")->result_array();
				$kategori_data = $this->db->query("SELECT * FROM mstr_jenis_pengajuan WHERE Jenis_Pengajuan_Id != 12")->result_array();
				

				$data['departments'] = $department_data;
				$data['kategories'] = $kategori_data;

				$this->session->unset_userdata('kategori');

				// $data['tahun'] = $this->db->query(
				// 	"SELECT tahun
				// 	FROM v_prestasi"
				// )->row_object()->tahun;


        // $prestasi = $this->db->query('SELECT * FROM v_prestasi 
        //     WHERE status = 1 '
        //     	. ($DEPARTMENT_ID == 0 ? "" : "AND DEPARTMENT_ID = '$DEPARTMENT_ID'")
				// . ($ID_JENIS_PENGAJUAN == 0 ? "" : " AND Jenis_Pengajuan_Id = $ID_JENIS_PENGAJUAN") 
        //     )->result_array();
        
        // $data['daftar_prestasi'] = $prestasi;
        $data['title'] = 'Daftar Prestasi & Rekognisi';
        $data['view'] = 'admin/prestasi/index';
        $data['menu'] = 'prestasi';    
        $this->load->view('layout/layout', $data);
		//masa beda?
    }

		function search(){

			$kategori = $this->input->post('kategori');
	
			$this->session->set_userdata('kategori' ,$this->input->post('kategori'));

			echo json_encode($kategori);	
		}

		public function prestasi_json($kategori = null) {

			$prodinya = $this->session->userdata('id_prodi');

			if($prodinya == 0) {
				$prodi ='';
			} else {
				$prodi = 'AND DEPARTMENT_ID = '. $prodinya;
			}

			$where ='WHERE status = 1';

			if($kategori) {
				// $kategori = "AND Jenis_Pengajuan_Id ='" . $this->session->userdata('kategori') ."'";
				$kategori = "AND Jenis_Pengajuan_Id = '" . $kategori . "'";
			} else {
				$kategori ='';
			}

			$records['data'] = $this->db->query("SELECT * FROM v_prestasi $where $kategori $prodi"
			
			)->result_array();
			$data = array();	
			foreach ($records['data']  as $row) 
			{  			
				$data[]= array(
					$row['Jenis_Pengajuan'],
					get_meta_value_by_type_field('judul', $row['id_pengajuan'], false),
					$row['FULLNAME'],
					$row['NAME_OF_DEPARTMENT'],
					$row['tahun'],
					$row['nama_periode'],
					// get_tingkat(get_meta_value_by_type_field('select_tingkat', $row['id_pengajuan'], false))['Tingkat_Prestasi'],	
					// get_prestasi(get_meta_value_by_type_field('select_prestasi', $row['id_pengajuan'], false))['keterangan'],	
				);
			}
			$records['data'] = $data;

			// echo '<pre>'; print_r($records); echo '</pre>';
			echo json_encode($records);	

		}

    public function detail($id_penerbitan_pengajuan = 0)
	{
		

		$query = $this->db->select('*')
			->from('v_prestasi')
		
			->where(
				[
					'id_penerbitan_pengajuan' => $id_penerbitan_pengajuan
				]
			)
			->get()
			->row_array();

		$data['prestasi'] = $query;
        $data['title'] = 'Prestasi & Rekognisi';
        $data['view'] = 'admin/prestasi/detail_prestasi';
        $data['menu'] = 'prestasi'; 

		$this->load->view('layout/layout', $data);
	}

}
