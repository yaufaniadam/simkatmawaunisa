<?php defined('BASEPATH') or exit('No direct script access allowed');

class Periode extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('periode_model', 'periode_model');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		$this->load->model('notif/Notif_model', 'notif_model');
		$this->load->library('excel');
		$this->load->library('mailer');
	}

	public function index($status = '')
	{
		$data['daftar_periode'] = $this->periode_model->getPeriode($status);
		$data['title'] = 'Periode Penerbitan';
		$data['view'] = 'admin/periode/index';
		$data['menu'] = 'periode';
		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules(
				'nama_periode',
				'Nama Periode',
				'trim|required',
				[
					'required' => '%s Wajib Diisi'
				]
			);

			if ($this->form_validation->run() == FALSE) {
				$data['title'] = 'Tambah Periode';
				$data['view'] = 'admin/periode/tambah';
				$data['menu'] = 'periode';
				$this->load->view('layout/layout', $data);
			} else {
				$data = [
					'nama_periode' => $this->input->post('nama_periode'),
					'tanggal' => '',
					'status' => '0'
				];
				if ($this->periode_model->tambahPeriode($data)) {
					redirect(base_url('admin/periode/index/0'));
				}
			}
		} else {
			$data['title'] = 'Tambah Periode';
			$data['view'] = 'admin/periode/tambah';
			$data['menu'] = 'periode';
			$this->load->view('layout/layout', $data);
		}
	}

	public function tambah_nominal($id_periode)
	{
		echo "tambah nomina";
	}

	public function bulan($id_periode = 0)
	{
		if ($this->input->post('submit')) {

			date_default_timezone_set('Asia/Jakarta');
			$tanggal = date("Y/m/d h:i:s");
			$id_periode = $this->input->post('id_periode');
			$data = [
				'tanggal' => $tanggal,
				'status' => 1
			];

			//update periode penerbitan
			$this->db->where('id_periode', $id_periode);
			$this->db->update('tr_periode_penerbitan', $data);

			$id_periode = $this->input->post('id_periode');
			$pengajuan = $this->input->post('pengajuan[]');

			print_r($id_periode);
			print_r($pengajuan);

			// for ($i = 0; $i < count($penerima); $i++) {
			// 	$this_pengajuan = $this->db->get_where(
			// 		'tr_penerbitan_pengajuan',
			// 		[
			// 			'STUDENTID' => $penerima[$i],
			// 			'id_periode' => $id_periode,
			// 			'id_pengajuan' => $pengajuan[$i]
			// 		]         
			// 	)->row_array();

			// 		//data utk kirim email & notif ke pegawai
			// 		$data_for_notif = [
			// 			'STUDENTID' => $penerima[$i],
			// 			'STUDENTNAME' => $penerima[$i],
			// 			'penerima' => '',
			// 			'id_pengajuan' => $pengajuan[$i],
			// 			'judul_pengajuan' => $data['title'],
			// 			'role' => [3],
			// 			'link' => base_url('admin/pengajuan/detail/'. $pengajuan[$i]),
			// 			'subjek' => 'Ada Pengajuan Prestasi Baru dari ' . $pengajuan[$i],
			// 			'isi' => 'Ada Pengajuan Prestasi Baru dari <strong>' . $pengajuan[$i] . '</strong> kategori <strong>' . $data['title'] . '</strong> yang perlu diperiksa.',
			// 			'id_status_notif' => 10,
			// 		];

			// 		//sendmail & notif
			// 		// $this->mailer->send_mail($data_for_notif);			
			// 		echo "<pre>";
			// 		print_r($this_pengajuan);
			// 		echo "</pre> <hr>";
			// 		echo "<pre>";
			// 		print_r($data_for_notif);
			// 		echo "</pre> <hr>";
			// }		
			// die();

			$this->db->select('id_pengajuan');
			$this->db->distinct();
			$this->db->from('tr_penerbitan_pengajuan');
			$this->db->where(['id_periode' => $id_periode]);
			$this->db->group_by('id_pengajuan');
			$pengajuan = $this->db->get()->result_array();

			foreach ($pengajuan as $pengajuan) {
				$this->db->set('status_id', 10)
					->set('pic', $this->session->userdata('user_id'))
					->set('date', 'NOW()', FALSE)
					->set('pengajuan_id', $pengajuan['id_pengajuan'])
					->insert('tr_pengajuan_status');
			}


			redirect(base_url('/admin/periode/bulan/' . $id_periode));
		} else {
			// die();
			$nama_periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object()->nama_periode;
			$status_periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object()->status;
			$data['daftar_pengajuan'] = $this->pengajuan_model->getPengajuanPerPeriode($id_periode);
			$data['title'] = 'Daftar Pengajuan Periode ' . $nama_periode;
			$data['status_periode'] = $status_periode;
			$data['id_periode'] = $id_periode;
			$data['view'] = 'admin/penerbitan_pengajuan/index';

			$this->load->view('layout/layout', $data);
		}
	}

	public function export_excel($id_periode = 0)
	{
		
		$periode = $this->db->get_where('tr_periode_penerbitan', ['id_periode' => $id_periode])->row_object();


		$daftar_pengajuan = $this->pengajuan_model->getPengajuanPerPeriodeForPrint($id_periode);

		foreach ($daftar_pengajuan as $pengajuan) {
			$prestasi = $this->db->get_where('v_prestasi', ['id_pengajuan' => $pengajuan['pengajuan_id']])->row_object();

			$new_daftar_pengajuan[] = [
				'id_pengajuan' => $pengajuan['pengajuan_id'],
				'nim' => $pengajuan['nim'],
				'jenis_pengajuan_id' => $pengajuan['Jenis_Pengajuan_Id'],
				'jenis_pengajuan' => $this->db->get_where('mstr_jenis_pengajuan', ['Jenis_Pengajuan_Id' => $pengajuan['Jenis_Pengajuan_Id']])->row_object()->Jenis_Pengajuan,
				'judul_prestasi' => get_meta_value('judul', $pengajuan['pengajuan_id'], false),
				'nominal' => $prestasi->nominal,
				'prodi' => $prestasi->NAME_OF_DEPARTMENT,
				'anggota' => get_meta_value('anggota', $pengajuan['pengajuan_id'], false),
				'tanggal' => $periode->tanggal,
				'tingkatan_prestasi' => get_tingkat(get_meta_value('lingkup_kegiatan', $pengajuan['pengajuan_id'], false ))['Tingkat_Prestasi'],
				'file' => $this->ngambil_field_file($pengajuan['pengajuan_id']),
			];

		}

		// echo '<pre>'; print_r($new_daftar_pengajuan); echo '</pre>';
		// exit;
		$data['nama_periode'] = $periode->nama_periode;
		$data['pengajuan'] = $new_daftar_pengajuan;
		$data['title'] = 'Daftar Pengajuan Periode ' . $periode->nama_periode;
		$data['view'] = 'admin/penerbitan_pengajuan/cetak';
		$this->load->view('admin/penerbitan_pengajuan/cetak', $data);
	}


	public function reward($id_prestasi)
	{
		$prestasi = $this->db->get_where('tr_penerbitan_pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])
			->row_object();

		$field_anggota = $this->db->get_where('tr_field_value', [
			'pengajuan_id' => $prestasi->id_pengajuan,
			'field_id' => 77
		]);

		$queryp = $this->db->select('*')
			->from('tr_pengajuan p')
			->join('mstr_jenis_pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id', 'left')
			->where([
				'p.pengajuan_id' => $prestasi->id_pengajuan
			])
			->get()
			->row_object();
		$tipe_reward = $queryp->fixed;

		if (($tipe_reward == 1) || ($tipe_reward == 3)) {
			$reward = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
				'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id
			])->row_object()->nominal;
		} elseif ($tipe_reward == 2) {
			if ($field_anggota->num_rows() > 0) {
				$anggota = explode(',', $field_anggota->row_object()->value);
				$urutan = array_search($prestasi->STUDENTID, $anggota);
				$reward = $this->db->get_where('mstr_penghargaan_rekognisi_mahasiswa', [
					'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
					'urutan' => $urutan > 0 ? 2 : 1
				])->row_object()->nominal;
			}
		} else {
			$reward = get_meta_value('biaya_pribadi', $prestasi->id_pengajuan, false);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($reward));

		// $query = $this->db->select('*')
		// 	->from('tr_penerbitan_pengajuan pp')
		// 	->join('tr_pengajuan p', 'p.pengajuan_id = pp.id_pengajuan')
		// 	->join('mstr_jenis_pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id')
		// 	->join('mstr_penghargaan_rekognisi_mahasiswa prm', 'prm.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id')
		// 	->where([
		// 		'pp.id_penerbitan_pengajuan' => $id_prestasi
		// 	])
		// 	->get()
		// 	->row_object();
	}

	public function set_nominal()
	{
		$id_prestasi = $this->input->post('id_prestasi');
		$id_periode = $this->db->get_where('tr_penerbitan_pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])->row_object()->id_periode;

		$this->db->set('nominal', $this->input->post('nominal'));
		$this->db->where('id_penerbitan_pengajuan', $id_prestasi);
		$this->db->update('tr_penerbitan_pengajuan');
		redirect(base_url('admin/periode/bulan/' . $id_periode));
	}

	public function hapus()
	{
		if ($this->input->post('command') == "DELETE") {
			$id_penerbitan_pengajuan = $this->input->post('id_penerbitan_pengajuan');
			$id_pengajuan = $this->input->post("id_pengajuan");
			$id_periode = $this->input->post("id_periode");

			$this->db->delete('tr_penerbitan_pengajuan', array('id_penerbitan_pengajuan' => $id_penerbitan_pengajuan));
			$this->db->delete('tr_pengajuan_status', array('pengajuan_id' => $id_pengajuan, 'status_id' => 9));

			$this->session->set_flashdata('msg', 'Data berhasil dihapus!');
			redirect(base_url('admin/periode/bulan/' . $id_periode));
		}
	}

	public function pencairan_reward()
	{
		date_default_timezone_set('Asia/Jakarta');

		$id_penerbitan_pengajuan = $this->input->post("id_penerbitan_pengajuan");
		$pegawai = $this->input->post("pegawai");
		$penerima = $this->input->post("penerima");

		$this->db->set([
			'pegawai' => $pegawai,
			'penerima' => $penerima,
			'status_pencairan' => 1,
			'tanggal_pencairan' => date("Y/m/d")
		]);
		$this->db->where('id_penerbitan_pengajuan', $id_penerbitan_pengajuan);
		$this->db->update('tr_penerbitan_pengajuan');
		redirect(base_url('admin/periode/bulan/' . $this->input->post('id_periode')));
	}


	function ngambil_field_file($id_pengajuan)
	{
		$data = $this->db->query("SELECT * FROM mstr_fields mf
		LEFT JOIN tr_field_value fv ON mf.field_id=fv.field_id
		WHERE (mf.type = 'file' AND fv.pengajuan_id = $id_pengajuan) OR  (mf.type = 'image' AND fv.pengajuan_id = $id_pengajuan)")
		->result_array();

		$files = array();
		foreach ($data as $data) {
			$files[] = [
				"key" => $data['key'],
				"field" => $data['field'],
				"value" => $this->db->select("*")->from('tr_media')->where('id', $data['value'])->get()->row_object()->file
			];
		}
		return $files;
	}
}
