<?php defined('BASEPATH') or exit('No direct script access allowed');


class Sso extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

		$username = $this->input->post('username');
		$password = $this->input->post('password');
	

		$query = $this->db->select('*')->from('sso_mhs')->where(array('email' => $username))->get();
		if ($query->num_rows() == 0) {
			// return false;
			$status_code = 1;

		} else {

			//Compare the password attempt with the password we have stored.
			$result = $query->row_array();

			$validPassword = password_verify($password, $result['password']);
			if ($validPassword) {
				$status_code = 0;
			}
		}

	
		echo json_encode(
			[
				'status_code' => $status_code,
			]
		);
		

	}
	

	
}
