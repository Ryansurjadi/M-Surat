<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query_helper extends CI_Model{
    
    /*
     * Fetch members data from the database
     * @param array filter data based on the passed parameters
     */
    
    function fetchAllData($table){
        $this->db->select('*');
        $this->db->where('Status =','1');
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function fetchAllDataOrderBY($table,$orderby,$order){
        $this->db->select('*');
        $this->db->where('Status =','1');
        $this->db->order_by($orderby,$order);
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }
    

    function getRows($table,$key,$params = array()){
        $this->db->select('*');
        $this->db->from($table);
        
        //dengan kondisi parameter didalam array
        if(array_key_exists("conditions", $params)){
            foreach($params['conditions'] as $key1=> $val){
                $this->db->where($key1, $val);
            }
        }
        
        //dengan kondisi count 
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
            $result = $this->db->count_all_results();
        }else{
            // UUID
            if(array_key_exists($key, $params)){
                $this->db->where($key, $params[$key]);
                $query = $this->db->get();
                $result = $query->result_array();
            }
            else{
                $this->db->order_by('email', 'asc');
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit'],$params['start']);
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit']);
                }
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }
        
        // Return fetched data
        return $result;
    }

    public function insert($table,$data = array()) {
        if(!empty($data)){
            // Add created and modified date if not included
            if(!array_key_exists("created", $data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists("modified", $data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            
            // Insert data to database
            $insert = $this->db->insert($table, $data);
            
            // return boolean after insert
            return true;
        }
        return false;
    }

    public function update($table, $key , $id, $data=array()) {
        if(!empty($data) && !empty($id)){
            
            // Add modified date if not included
            if(!array_key_exists("modified", $data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            
            // Update member data
            $update = $this->db->update($table, $data, array($key => $id));
            
            // Return the status
            return $update?true:false;
            
        }
        return false;
    }

    public function delete($table,$key,$id){
        // Delete member data
        $delete = $this->db->delete($table, array($key => $id));
        
        // Return the status
        return $delete?true:false;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    
        /////FUngsi untuk AUTH

    //////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function user_exist($data){
        $query = $this->db->get_where('users',$data);
        return $query;
    }

    

    //////////////////////////////////////////////////////////////////////////////////////////////////////////



    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function download($table,$key,$data){
        $this->db->select('File');
        $this->db->from($table);
        $this->db->where($key,$data);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function download_materi($table,$data){
        $this->db->select('File');
        $this->db->from($table);
        $this->db->where($data);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    public function is_exist($table,$key,$data){
        $query = $this->db->get_where($table,array($key => $data));
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function DropdownKelas(){
        $this->db->select('kelas.Id_kelas as Id, matakuliah.Nama as Matakuliah, kelas.Kelas ');
        $this->db->from('kelas');
        $this->db->join('matakuliah','matakuliah.id_matkul = kelas.id_matkul');
        $this->db->select('dosen.Nama as Dosen ,dosen.Nid as NID');
        $this->db->join('dosen','dosen.Nid = kelas.Nid');
        $this->db->where('kelas.Status =','1');
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function editUjian($id){
        $conditions = array( 'kelas.Status' => '1','ujian.Id_ujian' => $id);

        $this->db->select('kelas.Id_kelas as Id, matakuliah.Nama as Matakuliah, kelas.Kelas ');
        $this->db->from('kelas');
        $this->db->join('matakuliah','matakuliah.id_matkul = kelas.id_matkul');
        $this->db->select('dosen.Nama as Dosen ,dosen.Nid as NID');
        $this->db->join('dosen','dosen.Nid = kelas.Nid');
        $this->db->select('ujian.Tanggal,ujian.Jam_mulai,ujian.Jam_selesai');
        $this->db->join('ujian','kelas.Id_kelas = ujian.Id_kelas');
        $this->db->where($conditions);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function getMateri($id){
        $this->db->select('kelas.Id_kelas as Id, matakuliah.Nama as Matakuliah, kelas.Kelas ');
        $this->db->from('kelas');
        $this->db->join('matakuliah','matakuliah.Id_matkul = kelas.Id_matkul');
        $this->db->select('jadwal_kuliah.Id_jadwal');
        $this->db->join('jadwal_kuliah','jadwal_kuliah.Id_kelas = kelas.Id_kelas');
        $this->db->where('NIM',$id);
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        return $result;
    }

    function getMateriDosen($id){
        $this->db->select('kelas.Id_kelas as Id, matakuliah.Nama as Matakuliah, kelas.Kelas ');
        $this->db->from('kelas');
        $this->db->join('matakuliah','matakuliah.Id_matkul = kelas.Id_matkul');
        $this->db->where('Nid',$id);
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        return $result;
    }

    function getNamaKelas($id){
        $this->db->select('matakuliah.Nama as Matakuliah , kelas.Id_kelas,kelas.Kelas , matakuliah.Id_matkul');
        $this->db->from('matakuliah');
        $this->db->join('kelas','matakuliah.Id_matkul = kelas.Id_matkul');
        $this->db->where('kelas.Id_kelas',$id);
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->row():FALSE;
        return $result;
    }

    function getDeadline($table,$id){
        $conditions = array('Id_tugas'=> $id);

        $this->db->select('*');
        $this->db->where($conditions);
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }
    /////////////////////////////////////////////////////////////////////////////////////////

    public function uploadMatkul($dataarray,$prodi) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        for ($i = 1; $i < count($dataarray); $i++) {
            
                //$idrand = mt_rand(10000000,99999999);
                $userData[] = array(
                    'Id_matkul' =>$dataarray[$i]['Kd_matkul'].'-'.$prodi,
                    'Kd_matkul' => $dataarray[$i]['Kd_matkul'],
                    'Nama' => $dataarray[$i]['Nama'],
                    'Sks' => $dataarray[$i]['Sks'],
                    'Semester' => $dataarray[$i]['Semester'],
                    'Id_prodi' => $prodi,
                    'Created' => $create,
                    'Modified' => $modified,
                    'Status' => '1'
                );
                
        }
       
        // echo "<pre>";
        // print_r($userData);
        // die;
        
        if ($prodi =='TI'){
             $this->db->insert_batch('matakuliah', $userData);
        }
        else{
            $this->db->insert_batch('matakuliah', $userData);
        }

    }

    public function uploadKelas($dataarray) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        for ($i = 1; $i < count($dataarray); $i++) {
            
                
                $userData[] = array(
                    'Id_kelas' => mt_rand(100000000,999999999),
                    'Id_matkul' => $dataarray[$i]['Id_matkul']."-".$dataarray[$i]['Id_prodi'],
                    'Nid' => $dataarray[$i]['Nid'],
                    'Kelas' => $dataarray[$i]['Kelas'],
                    'Hari' => $dataarray[$i]['Hari'],
                    'Jam_mulai' => $dataarray[$i]['Jam_mulai'],
                    'Jam_selesai' => $dataarray[$i]['Jam_selesai'],
                    'Created' => $create,
                    'Modified' => $modified,
                    'Status' => '1'
                );
                
        }
       
        // echo "<pre>";
        // print_r($userData);
        // die;
        
             $this->db->insert_batch('kelas', $userData);
        
       

    }

    public function uploadUpdateKelas($dataarray) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        for ($i = 1; $i < count($dataarray); $i++) {
            
                
                $userData[] = array(
                    'Id_matkul' => $dataarray[$i]['Id_matkul']."-".$dataarray[$i]['Id_prodi'],
                    'Nid' => $dataarray[$i]['Nid'],
                    'Kelas' => $dataarray[$i]['Kelas'],
                    'Hari' => $dataarray[$i]['Hari'],
                    'Jam_mulai' => $dataarray[$i]['Jam_mulai'],
                    'Jam_selesai' => $dataarray[$i]['Jam_selesai'],
                    'Modified' => $modified,
                    'Status' => '1'
                );
                
        }
        
        $this->db->update_batch('kelas', $userData,'Id_matkul');
       
    }

    public function uploadUjian($dataarray) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        for ($i = 1; $i < count($dataarray); $i++) {
            
                
                $userData[] = array(
                    'Id_kelas' => mt_rand(100000000,999999999),
                    'Id_matkul' => $dataarray[$i]['Id_matkul'],
                    'Nid' => $dataarray[$i]['Nid'],
                    'Kelas' => $dataarray[$i]['Kelas'],
                    'Hari' => $dataarray[$i]['Hari'],
                    'Tanggal' => $dataarray[$i]['Tanggal'],
                    'Jam_mulai' => $dataarray[$i]['Jam_mulai'],
                    'Jam_selesai' => $dataarray[$i]['Jam_selesai'],
                    'Created' => $create,
                    'Modified' => $modified,
                    'Status' => '1'
                );
                
        }
       
        // echo "<pre>";
        // print_r($userData);
        // die;
        
             $this->db->insert_batch('ujian', $userData);
        
       

    }

    public function uploadUpdateUjian($dataarray) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        for ($i = 1; $i < count($dataarray); $i++) {
            
                
                $userData[] = array(
                    'Id_matkul' => $dataarray[$i]['Id_matkul'],
                    'Nid' => $dataarray[$i]['Nid'],
                    'Kelas' => $dataarray[$i]['Kelas'],
                    'Hari' => $dataarray[$i]['Hari'],
                    'Tanggal' => $dataarray[$i]['Tanggal'],
                    'Jam_mulai' => $dataarray[$i]['Jam_mulai'],
                    'Jam_selesai' => $dataarray[$i]['Jam_selesai'],
                    'Modified' => $modified,
                    'Status' => '1'
                );
                
        }
        
        $this->db->update_batch('ujian', $userData,'Id_matkul');
       
    }

    public function BulanSurat($bulan){
        if($bulan == '01'){
            $romawi = 'I';
            return $romawi;
        }
        elseif($bulan == '02'){
            $romawi = 'II';
            return $romawi;
        }
        elseif($bulan == '03'){
            $romawi = 'III';
            return $romawi;
        }
        elseif($bulan == '04'){
            $romawi = 'IV';
            return $romawi;
        }
        elseif($bulan == '05'){
            $romawi = 'V';
            return $romawi;
        }
        elseif($bulan == '06'){
            $romawi = 'VI';
            return $romawi;
        }
        elseif($bulan == '07'){
            $romawi = 'VII';
            return $romawi;
        }
        elseif($bulan == '08'){
            $romawi = 'VIII';
            return $romawi;
        }
        elseif($bulan == '09'){
            $romawi = 'IX';
            return $romawi;
        }
        elseif($bulan == '10'){
            $romawi = 'X';
            return $romawi;
        }
        elseif($bulan == '11'){
            $romawi = 'XI';
            return $romawi;
        }
        else{
            $romawi = 'XII';
            return $romawi;
        }
    }

    function fetchSurat($table,$conditions){
        $this->db->select('*');
        $this->db->where($conditions);
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    function config_color(){
        $this->db->select('Konfig_Value');
        $this->db->where('Konfig_key','Konfigurasi_warna');
        $this->db->from('konfigurasi');
        $query = $this->db->get();
        $result = $query->result_array();
        $color = $result[0]['Konfig_Value'];

        return $color;
    }

}