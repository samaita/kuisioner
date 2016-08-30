<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telkom extends CI_Controller {
	public function connect(){
		echo 'ready';
	}

	public function login(){
		$this->load->library('session');
		$this->load->model('petugas');

		$username =	$this->input->post('param1');
		$password = $this->input->post('param2');

		$result = $this->petugas->loginAttempt($username, $password);
		
		if($result){
		     $sess_array = array();
		     foreach($result as $row){
		       $sess_array = array(
		         'id' => $row->idpetugas,
		         'username' => $row->username, 
		         'level' => $row->level,
		         'nama' => $row->nama
		       );
		       $this->session->set_userdata($sess_array);
		       $active = $this->petugas->is_active($row->idpetugas);
		     }
		     echo 'true';
		     return true;
		   }
		   else{
		     return false;
		   }
	}

	public function logOut(){
		$this->load->library('session');
		$this->load->model('petugas');
		$this->petugas->isnt_active($this->session->userdata('id'));
		$this->session->sess_destroy();
		$data = array(
			'msg' => 'Sukses Logout',
		);
		echo json_encode($data);
	}

	public function alive(){
		$this->load->library('session');
		$this->load->model('petugas');

		if($this->session->userdata('username')){
			$active = $this->petugas->is_active($this->session->userdata('id'));
			$data = array(
				'id' => $this->session->userdata('id'),
				'username' => $username = $this->session->userdata('username'),
				'level' => $level = $this->session->userdata('level'),
				'nama' => $nama = $this->session->userdata('nama')
				);
			echo json_encode($data);
		}
	}

	public function createAccount(){
		$this->load->model('petugas');

		$username =	$this->input->post('param1');
		$password = $this->input->post('param2');
		$nama =	$this->input->post('param3');
		$level = $this->input->post('param4');

		$result = $this->petugas->createAccount($username, $password, $nama, $level);
		if($result){
			$data = array(
					'msg' => 'Akun '.$username.' berhasil dibuat.',
					'result' => $result
				);
			echo json_encode($data);
		}else{
			$data = array(
					'msg' => 'Username sudah digunakan.',
					'result' => $result
				);
			echo json_encode($data);
		}
	}

	public function getActiveAccount(){
		$this->load->model('petugas');
		$result = $this->petugas->activeAccount();
		$data = array(
					'msg' => 'Akun yang aktif.',
					'result' => $result
				);
		echo json_encode($data);
	}


	public function getDashboardData(){
		$this->load->model('petugas');
		$result = $this->petugas->getDashboardData();
		$data = array(
					'msg' => 'Akun yang aktif.',
					'result' => $result
				);
		echo json_encode($data);
	}

	public function deleteAccount($id){
		$this->load->model('petugas');
		$result = $this->petugas->deleteAccount($id);
		$data = array(
					'msg' => 'Akun berhasil dihapus.',
				);
		echo json_encode($data);		
	}

	public function getAccountSetting(){
		$this->load->library('session');
		$this->load->model('petugas');
// $id = $this->session->userdata('id');
// 			$result = $this->petugas->getAccountSetting($id);
// 			$data = array(
// 					'msg' => 'Setting akun anda berhasil diganti',
// 					'result' => $result,
// 					'apa ' => $id
// 				);
// 			echo json_encode($data);
		if($this->session->userdata('id')){
			$id = $this->session->userdata('id');
			$result = $this->petugas->getAccountSetting($id);
			$data = array(
					'msg' => 'Setting akun anda berhasil diganti',
					'result' => $result
				);
			echo json_encode($data);
		}
	}

	public function changeAccount(){
		$this->load->model('petugas');
		$username =	$this->input->post('param1');
		$password = $this->input->post('param2');
		$id = $this->input->post('param3');
		$result = $this->petugas->changeAccount($username, $id);
		$data = array(
			'msg' => 'Setting akun anda berhasil diganti',
			'result' => $result
		);
		echo json_encode($data);
	}

	public function submitData(){
		$this->load->model('petugas');
		$this->load->library('session');
		
		$nama =	$this->input->post('param1');
		$nomor_kontak = $this->input->post('param2');
		$nilai_plasa =	$this->input->post('param3');
		$kritik_saran = $this->input->post('param4');
		$pasang_indihome = $this->input->post('param5');
		$idpetugas = $this->session->userdata('id');

		$result = $this->petugas->submitData($nama, $nomor_kontak, $nilai_plasa, $kritik_saran, $pasang_indihome, $idpetugas);
		$data = array(
			'msg' => 'Berhasil disimpan',
			'result' => $result
		);
		echo json_encode($data);
	}

	public function getReport($param){
		$this->load->helper('url');
		$base = base_url();
		$this->load->model('petugas');

		$result = $this->petugas->getReportToday();

		//$urlPXL = substr($base, 0, -3);
		$data = array(
			'msg' => 'Data hari ini',
			'result' => $result
		);
		echo json_encode($data);
	}

	public function destroyReportToday(){
		$this->load->model('petugas');
		$result = $this->petugas->destroyReportToday();
		$data = array(
					'msg' => 'Data hari ini berhasil dihapus.',
				);
		echo json_encode($data);		
	}
}
