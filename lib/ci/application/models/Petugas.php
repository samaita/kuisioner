<?php
class Petugas extends CI_Model
{
 function loginAttempt($username, $password)
 {
   //$this -> db -> select('idpetugas, username, password');
   $this -> db -> from('petugas');
   $this -> db -> where('username', $username);
   $this -> db -> where('password', MD5($password));
   $this -> db -> limit(1);

   $query = $this -> db -> get();

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }

 function is_active($id){
      $data = array(
          'is_active' => 1
      );

      $this->db->where('idpetugas', $id);
      $query = $this->db->update('petugas', $data);
 }

 function isnt_active($id){
        $data = array(
          'is_active' => 0
      );

      $this->db->where('idpetugas', $id);
      $query = $this->db->update('petugas', $data);
 }

 function createAccount($username, $password, $nama, $level){
  //Check First
  $this ->db->from('petugas');
  $this ->db->where('username', $username);
  $this ->db->limit(1);
  $query = $this -> db -> get();
  
  if($query -> num_rows() == 1){
    return false;
   }
   else{ 
    $data = array(
          'username' => $username,
          'password' => MD5($password),
          'nama' => $nama,
          'level' => $level
    );

    $this->db->insert('petugas', $data);
    return true;
   }
 }

function activeAccount(){
  $this ->db->from('petugas');
  $this ->db-> select('idpetugas, nama, level, is_active');
  $query = $this->db-> get();
  return $query->result();

 }

  function deleteAccount($id){
    $this->db->delete('petugas', array('idpetugas' => $id));
  }

  function getAccountSetting($id){
    $this ->db->from('petugas');
    $this ->db-> select('idpetugas, nama');
    $this -> db -> where('idpetugas', $id);
    $query = $this->db-> get();
    return $query->result();
  }

  function getDashboardData(){
    $this ->db->from('user_entries');
    $this ->db-> select('iduser_entries');
    $this -> db -> where('entry_datetime', date("Y-m-d"));
    $this -> db -> where('blocked', 0);
    $query = $this->db-> get();
    return $query->result();
  }

  function changeAccount($nama, $id){
    $data = array(
        'nama' => $nama
    );

    $this->db->where('idpetugas', $id);
    $query = $this->db->update('petugas', $data);
    return $query;
  }

  function submitData($param1, $param2, $param3, $param4, $param5, $param6 ){
    $data = array(
        'nama' => $param1,
        'nomor_kontak' => $param2,
        'nilai_plasa' => $param3,
        'kritik_saran' => $param4,
        'pasang_indihome' => $param5,
        'idpetugas' => $param6,
        'entry_datetime' => date("Y-m-d")
    );
    $query = $this->db->insert('user_entries', $data);
    return $data;
  }

  function getReportToday(){
    $this ->db->from('user_entries');
    $this ->db-> select('nama, nomor_kontak, kritik_saran,nilai_plasa, pasang_indihome, entry_datetime');
    //$this->db->where("'entry_datetime' BETWEEN ".date("Y-m-d 00:00:00")."' AND '".date("Y-m-d 23:59:59")."'");
    $this -> db -> where('entry_datetime >=', date("Y-m-d"));
    $this -> db -> where('entry_datetime <=', date("Y-m-d"));
    $this -> db -> where('blocked =', 0);
    $query = $this->db-> get();
    return $query->result();
  }

  function destroyReportToday(){
    $data = array(
        'blocked' => 1
    );
    //$this->db->where("'entry_datetime' BETWEEN ".date("Y-m-d 00:00:00")."' AND '".date("Y-m-d 23:59:59")."'");
    $this -> db -> where('entry_datetime =', date("Y-m-d"));
    $this -> db -> where('blocked =', 0);
    $query = $this->db->update('user_entries', $data);
    return $query;
  }

}
?>