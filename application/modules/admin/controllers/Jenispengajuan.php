<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenispengajuan extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengajuan_model', 'pengajuan_model');
	}

	public function index()
	{
		$data['jenis_pengajuan'] = $this->pengajuan_model->get_jenis_pengajuan();

		$data['title'] = 'Jenis Pengajuan';
		$data['view'] = 'jenispengajuan/index';
		$data['menu'] = 'jenispengajuan';
		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{

		if ($this->input->post('submit')) {

			$this->load->model('fields_model', 'fields_model');

			$this->form_validation->set_rules(
				'Jenis_Pengajuan',
				'Nama Jenis Pengajuan',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'deskripsi',
				'Deskripsi',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'parent',
				'Kategori',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			// $this->form_validation->set_rules(
			// 	'jumlah_anggota',
			// 	'Jumlah Anggota',
			// 	'trim|required',
			// 	array('required' => '%s wajib diisi.')
			// );

			if ($this->form_validation->run() == FALSE) {
				$data['kategori_jenis_pengajuan'] = $this->pengajuan_model->get_kategori_jenis_pengajuan();
				$data['title'] = 'Tambah Jenis Pengajuan';
				$data['view'] = 'jenispengajuan/tambah';
				$data['menu'] = 'jenispengajuan';
				$this->load->view('layout/layout', $data);
			} else {

				$parent = $this->input->post('parent');

				$data = array(
					'parent' => $parent,
					'jenis_pengajuan' => $this->input->post('Jenis_Pengajuan'),
					'deskripsi' => $this->input->post('deskripsi'),
					// 'jumlah_anggota' => $this->input->post('jumlah_anggota'),
				);

				$result = $this->pengajuan_model->tambah_jenis_pengajuan($data);

				$id = $this->db->insert_id();
				if ($result) {

					// insert data penghargaan
					$insdata_penghargaan = [
						"urutan" => 0,
						"Jenis_Pengajuan_Id" => $id,
					];
					$this->db->insert('mstr_penghargaan_rekognisi_mahasiswa', $insdata_penghargaan);

					//insert field judul

					$data_judul = [
						'field' => 'Judul kegiatan/Nama Acara/Nama Lomba, dll',
						'key' => 'judul',
						'type' => 'judul',
						'required' => '1',
						'deskripsi' => 'Deskripsi di sini.',
					];
	
					$data_judul = $this->security->xss_clean($data_judul);
	
					$this->fields_model->insert($data_judul);
					$buat_judul_id = $this->db->insert_id();

					//insert field wajib
					$insdata_field = [
					
						["urutan" => 1,
						"terpakai" => 1,
						"field_id" => $buat_judul_id, //id untuk field judul
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 2,
						"terpakai" => 1,
						"field_id" => 15, //id untuk field Laman Penyelenggara
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 3,
						"terpakai" => 1,
						"field_id" => 11, //id untuk field Surat TUgas
						"Jenis_Pengajuan_Id" => $id,
						],
						
						["urutan" => 4,
						"terpakai" => 1,
						"field_id" => 4, //id untuk field Tahun Kegiatan
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 5,
						"terpakai" => 1,
						"field_id" => 5, //id untuk field Anggota /Mahasiswa
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 6,
						"terpakai" => 1,
						"field_id" => 6, //id untuk field Dosen
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 7,
						"terpakai" => 1,
						"field_id" => 7, //id untuk field Keterlibatan Dosen
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 8,
						"terpakai" => 1,
						"field_id" => 8, //id untuk field Tingkatan Prestasi
						"Jenis_Pengajuan_Id" => $id,
						],
					
						["urutan" => 9,
						"terpakai" => 1,
						"field_id" => 10, //id untuk field Sertifikat
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 10,
						"terpakai" => 1,
						"field_id" => 14, //id untuk field UPP
						"Jenis_Pengajuan_Id" => $id,
						],						
						["urutan" => 11,
						"terpakai" => 1,
						"field_id" => 1, //id untuk field lingkup Kegiatan
						"Jenis_Pengajuan_Id" => $id,
						],

						["urutan" => 12,
						"terpakai" => 1,
						"field_id" => 12, //id untuk field Negara Peserta
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 13,
						"terpakai" => 1,
						"field_id" => 13, //id untuk field Negara Peserta
						"Jenis_Pengajuan_Id" => $id,
						],
						//diisi admin
						["urutan" => 14,
						"terpakai" => 1,
						"field_id" => 2, //id untuk field akademik non akademik
						"Jenis_Pengajuan_Id" => $id,
						],
						["urutan" => 15,
						"terpakai" => 1,
						"field_id" => 9, //id untuk field Skor
						"Jenis_Pengajuan_Id" => $id,
						],
					];

					$this->db->insert_batch('mstr_pengajuan_field', $insdata_field);

					$this->session->set_flashdata('msg', 'Kategori Pengajuan berhasil ditambah!');
					redirect(base_url('admin/jenispengajuan/edit/' . $id . '?id=&pos='));
				}
			}
		} else {
			$data['kategori_jenis_pengajuan'] = $this->pengajuan_model->get_kategori_jenis_pengajuan();
			$data['title'] = 'Tambah Jenis Pengajuan';
			$data['view'] = 'jenispengajuan/tambah';
			$data['menu'] = 'jenispengajuan';
			$this->load->view('layout/layout', $data);
		}
	}

	public function edit($id)
	{


		if ($this->input->post('submit')) {

			$this->form_validation->set_rules(
				'parent',
				'Kategori',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			// $this->form_validation->set_rules(
			// 	'jumlah_anggota',
			// 	'Jumlah Anggota',
			// 	'trim|required',
			// 	array('required' => '%s wajib diisi.')
			// );
			$this->form_validation->set_rules(
				'deskripsinya',
				'Deskripsi',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'Jenis_Pengajuan',
				'Nama Jenis Pengajuan',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'aktif',
				'Aktif',
				'trim|required',
				array('required' => '%s wajib diisi.')
			);
			$this->form_validation->set_rules(
				'fields',
				'Field formulir',
				'trim|required|min_length[6]',
				array('required' => '%s wajib diisi.', 'min_length' => 'Field tidak boleh kosong')
			);

			if ($this->form_validation->run() == FALSE) {

				$data['kategori_jenis_pengajuan'] = $this->pengajuan_model->get_kategori_jenis_pengajuan();

				$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);
				$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan($id, 0);
				// $data['fields_pengajuan'] = $this->pengajuan_model->getAllFieldsPengajuan(getAllFieldsPengajuan($id, 1);

				$data['title'] = 'Edit Jenis Pengajuan';

				$data['view'] = 'jenispengajuan/edit';
				$data['menu'] = 'jenispengajuan';
				$this->load->view('layout/layout', $data);
			} else {

				$parent = $this->input->post('parent');
				$data = array(
					'parent' => $parent,
					'jenis_pengajuan' => $this->input->post('Jenis_Pengajuan'),
					'deskripsi' => $this->input->post('deskripsinya'),
					// 'jumlah_anggota' => $this->input->post('jumlah_anggota'),
					'aktif' => $this->input->post('aktif')
				);

				$fields = $this->input->post('fields');
				$expl = explode('&', $fields);
				$arr = array();
				foreach ($expl as $key => $exp) {
					$exp = explode("=", $exp);
					$arr[] = $exp[1];
				}

				$dataFieldCheck = [
					'not_exist_fields_data' => implode(',', $arr),
					'sent_fields_data' => $arr,
				];

				$data = $this->security->xss_clean($data);
				$result = $this->pengajuan_model->edit_jenis_pengajuan($data, $id);

				if ($result) {

					$fields = $this->pengajuan_model->editFieldsPengajuan($dataFieldCheck, $id);

					$this->session->set_flashdata('msg', 'Jenis Pengajuan berhasil diubah!');
					redirect(base_url('admin/jenispengajuan/edit/' . $id . '?id=' . $parent . '&pos=' . $parent));
				}
			}
		} else {
			$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);
			$data['all_fields'] = $this->pengajuan_model->getAllFieldsPengajuan($id, 0);

			$data['kategori_jenis_pengajuan'] = $this->pengajuan_model->get_kategori_jenis_pengajuan();

			$data['title'] = 'Edit Jenis Pengajuan';
			$data['view'] = 'jenispengajuan/edit';
			$data['menu'] = 'jenispengajuan';
			$this->load->view('layout/layout', $data);
		}
	}

	//tambah field baru
	public function tambah_field($id_kat)
	{
		//cek id terakhir
		$query =  $this->db->query('SELECT field_id FROM mstr_fields
		ORDER BY field_id DESC LIMIT 1')->row_array();

		$data = [
			"field" => "Nama Field " . $query['field_id'],
			"key" => "key_" . $query['field_id'],
		];

		$this->db->insert('mstr_fields', $data);
		$last_id = $this->db->insert_id();
		if ($last_id) {

			$data = [
				"field_id" => $last_id,
				"Jenis_Pengajuan_Id" => $id_kat,
				"terpakai" => 0,
			];

			$this->db->insert('mstr_pengajuan_field', $data);

			echo json_encode(array("status" => "Sukses"));
		}
	}

	public function edit_field($id)
	{

		// if ($this->input->post()) {

		$this->form_validation->set_rules(
			'field',
			'Nama Field',
			'trim|required',
			array('required' => '%s wajib diisi')
		);

		$this->form_validation->set_rules(
			'type',
			'Jenis Field',
			'required',
			array('required' => '%s wajib diisi')
		);

		if ($this->form_validation->run() == FALSE) {
			$error = [
				'field' => form_error('field'),
				'type' => form_error('type')
			];
			echo json_encode(array("status" => "Error", "error" => $error));
		} else {

			$data = [
				"required" => $this->input->post('required'),
				"field" => $this->input->post('field'),
				"placeholder" => $this->input->post('placeholder'),
				"key" => ($this->input->post('type') == 'judul') ? 'judul' : $this->input->post('key'),
				"deskripsi" => $this->input->post('deskripsi'),
				"type" => $this->input->post('type'),
			];


			$query = $this->pengajuan_model->edit_form_field($data, $id);

			if ($query) {
				echo json_encode(array("status" => 'Success', 'data' => $data['field']));
			} else {
				echo json_encode(array("status" => "Error Updating"));
			}
		}


		// }
	}

	public function nominal_reward($id)
	{
		$data['kategori'] = $this->pengajuan_model->get_jenis_pengajuan_byid($id);

		$data['title'] = 'Edit Nominal Reward';
		$data['view'] = 'jenispengajuan/nominal_reward';
		$data['menu'] = 'jenispengajuan';
		$this->load->view('layout/layout', $data);
	}
	public function edit_nominal($id)
	{
		$tipe_reward = $this->input->post('tipe_reward');

		$this->form_validation->set_rules(
			'tipe_reward',
			'Tipe Reward',
			'trim|required',
			array('required' => '%s wajib diisi')
		);

		if ($tipe_reward != '') {


			if ($tipe_reward != 4) {

				$this->form_validation->set_rules(
					'nominal1',
					'Nominal 1',
					'trim|required',
					array('required' => '%s wajib diisi')
				);

				if ($tipe_reward == 2) {

					$this->form_validation->set_rules(
						'nominal2',
						'Nominal 2',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
				} elseif ($tipe_reward == 5) {

					$this->form_validation->set_rules(
						'keterangan-nominal1',
						'Nominal 2',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
					$this->form_validation->set_rules(
						'nominal2',
						'Nominal 2',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
				}
			}
		}

		if ($this->form_validation->run() == FALSE) {

			$error = [
				'nominal1' => form_error('nominal1'),
				'nominal2' => form_error('nominal2'),
				'nominal3' => form_error('nominal3'),
				'nominal4' => form_error('nominal4'),
				'tipe_reward' => form_error('tipe_reward')
			];

			echo json_encode(array("status" => "Error", "error" => $error));
		} else {
			//ubah jenis nominal (column:fixed) pada tabel mstr_jenis_pengajuan
			$this->db->update('mstr_jenis_pengajuan', ["fixed" => $tipe_reward], array('Jenis_Pengajuan_Id' => $id));

			$new_nominal = array(
				"0" => $this->input->post('nominal1'),
				"1" => $this->input->post('nominal2'),
				"2" => $this->input->post('nominal3'),
				"3" => $this->input->post('nominal4')
			);


			if ($tipe_reward == 2) {

					//cek order awal apkah ada yg nilainya 1
					$nominal_exist = $this->db->select('nominal')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
						"Jenis_Pengajuan_Id" => $id,
						"urutan" => 1
					])->get()->result_array();

				if ($nominal_exist) {

					//jika nominal exist $nominal_exist, maka cukup mengupdate data nominal dengan yg baru

					foreach ($new_nominal as $key => $value) {
						$this->db->where([
							"Jenis_Pengajuan_Id" => $id,
							"urutan" => $key
						]);
						$data_nominal = [
							"nominal" => $value
						];

						$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
					}
				} else {

					$insdata_penghargaan = [
						"urutan" => 1,
						"Jenis_Pengajuan_Id" => $id,
					];

					//jika nominal tidak exist $nominal_exist tidak ada, maka insert data baru dengan order=1
					$this->db->insert('mstr_penghargaan_rekognisi_mahasiswa', $insdata_penghargaan);

					//jika sudah diinsert, masukan valuenya
					foreach ($new_nominal as $key => $value) {
						$this->db->where([
							"Jenis_Pengajuan_Id" => $id,
							"urutan" => $key
						]);
						$data_nominal = [
							"nominal" => $value
						];

						$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
					}
				}
			} else {

				$new_nominal = $this->input->post('nominal1');

				$data_penghargaan = [
					"nominal" => $new_nominal,
					"urutan" => 0,
				];
			}

			if ($tipe_reward == 1 || $tipe_reward == 3) {



				$this->db->where(array(
					'Jenis_Pengajuan_Id' => $id,
					'urutan' => '0'
				));

				$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_penghargaan);
			}

			echo json_encode(array("status" => "sukses"));
		}
	}


	public function edit_nominal_prestasi($id)
	{
	 	$tipe_reward = $this->input->post('tipe_reward');

		

		$this->form_validation->set_rules(
			'tipe_reward',
			'Tipe Reward',
			'trim|required',
			array('required' => '%s wajib diisi')
		);

		$this->form_validation->set_rules(
			'keterangan[]',
			'Keterangan Juara',
			'trim|required',
			array('required' => '%s wajib diisi')
		);

		$this->form_validation->set_rules(
			'nominal[]',
			'Nominal',
			'trim|required',
			array('required' => '%s wajib diisi')
		);
		

		if ($this->form_validation->run() == FALSE) {

			$error = [
				'nominal' => form_error('nominal[]'),
				'keterangan' => form_error('keterangan[]'),
				'tipe_reward' => form_error('tipe_reward')
			];

			echo json_encode(array("status" => "Error", "error" => $error));

		} else {
			//ubah jenis nominal (column:fixed) pada tabel mstr_jenis_pengajuan
			 $this->db->update('mstr_jenis_pengajuan', ["fixed" => $tipe_reward], array('Jenis_Pengajuan_Id' => $id));
			
			$keterangan = $this->input->post('keterangan');
			$nominal = $this->input->post('nominal');

			$i = 0;
			foreach($keterangan as $key=>$val)
			{
				  $data[$i]['Jenis_Pengajuan_Id'] = $id;
				  $data[$i]['urutan'] = $i;
				  $data[$i]['keterangan'] = $val;
				  $data[$i]['nominal'] = $nominal[$key];
				  $i++;
			}

			//cek order awal apkah ada yg nilainya 1
			$cekvalue = $this->db->select('MAX(urutan) as maxnum')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
				"Jenis_Pengajuan_Id" => $id,
			])->get();

			$val_exists = $cekvalue->num_rows();
			$max_val = $cekvalue->row_array()['maxnum'];

			if($val_exists > 0) {
				
				//cek max valuenya brp, yg diinsert adalah data dgn value yg lebih besar dari max value
				//sedangkan yg diupdate adalah data yg sama/kurang dari max value
				//caranya? pecah pake array slice,

				$start = array_slice($data, 0, $max_val+1); // data yg sudah ada
				$end = array_slice($data, $max_val+1); // data baru
			
				foreach ($start as $key => $val) {
					$this->db->where([
						"Jenis_Pengajuan_Id" => $id,
						"urutan" => $key
					]);
					$data_nominal = [
						"nominal" => $val['nominal'],
						"keterangan" => $val['keterangan'],
					];

					$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
				}

				if(!empty($end)) {
					$this->db->insert_batch('mstr_penghargaan_rekognisi_mahasiswa', $end);
				} 

			} else {
		
				$this->db->insert_batch('mstr_penghargaan_rekognisi_mahasiswa', $data);
			}


			echo json_encode(array("status" => "sukses"));
		}
	}


	public function store_kategori()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('nama', 'Nama Kategori', 'trim|required');
			$this->form_validation->set_rules('singkatan', 'Singkatan', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$data['view'] = 'admin/borang/kategori/tambah_kategori';
				$this->load->view('admin/layout', $data);
			} else {

				$data = array(
					'kategori_dokumen' => $this->input->post('nama'),
					'singkatan' => $this->input->post('singkatan'),
				);

				print_r($data);

				$data = $this->security->xss_clean($data);
				$result = $this->apt_model->add_kategori($data);
				if ($result) {
					$this->session->set_flashdata('msg', 'Kategori baru berhasil ditambahkan!');
					redirect(base_url('admin/kategori'));
				}
			}
		} else {
			$data['view'] = 'admin/borang/kategori/tambah_kategori';
			$this->load->view('admin/layout', $data);
		}
	}

	public function nominal_penghargaan()
	{
		$penghargaan = $this->db->select('*')
			->from('mstr_jenis_pengajuan jp')
			->join('mstr_penghargaan_rekognisi_mahasiswa penghargaan', 'penghargaan.Jenis_Pengajuan_Id=jp.Jenis_Pengajuan_Id')
			->where([
				'parent' => 12
			])
			->get()
			->result_array();

		$data = [
			'penghargaan' => $penghargaan,
			'view' => 'jenispengajuan/nominal'
		];

		$this->load->view('layout/layout', $data);

		// echo "<pre>";
		// print_r($penghargaan);
		// echo "<pre/>";
	}

	function alpha_dash_space($fullname)
	{
		if (!preg_match('/^[a-z\_]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'Hanya huruf kecil dan tanpa spasi, spasi boleh diganti underscore (_)');
			return FALSE;
		} else {
			return TRUE;
		}
	}


	// //kategori isian rules
	// public function kat_keterangan_pengajuan_check()
	// {
	// 	//if (isset($_POST['accept_terms_checkbox']))
	//         if ($this->input->post('kat_keterangan_surat'))
	// 	{
	// 		return TRUE;
	// 	}
	// 	else
	// 	{
	// 		$error = 'Please read and accept our terms and conditions.';
	// 		$this->form_validation->set_message('kat_keterangan_surat', $error);
	// 		return FALSE;
	// 	}
	// }
}
