<?php defined('BASEPATH') or exit('No direct script access allowed');


class Prestasi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();     
    }

    public function index($id_periode = 0)
    {
        $prestasi = $this->db->query('SELECT * FROM v_prestasi


        ')->result_array();
        
        $data['daftar_prestasi'] = $prestasi;
        $data['title'] = 'Daftar Prestasi & Rekognisi';
        $data['view'] = 'admin/prestasi/index';

        $this->load->view('layout/layout', $data);
    }

}
