<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{
    
    function __construct() {
        // Set table name
        $this->table = 'users';
        $this->table_m = 'mahasiswa';
        $this->table_d = 'dosen';
    }
    
    /*
     * Fetch members data from the database
     * @param array filter data based on the passed parameters
     */
    
    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from($this->table);
        
        //dengan kondisi parameter didalam array
        if(array_key_exists("conditions", $params)){
            foreach($params['conditions'] as $key => $val){
                $this->db->where($key, $val);
            }
        }
        
        //dengan kondisi count 
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
            $result = $this->db->count_all_results();
        }else{
            //cek UUID
            if(array_key_exists("UUID", $params)){
                $this->db->where('UUID', $params['UUID']);
                $query = $this->db->get();
                $result = $query->row_array();
            }else{
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
    
    /*
     * Insert members data into the database
     * @param $data data to be insert based on the passed parameters
     */
    public function insert($data = array()) {
        if(!empty($data)){
            // Add created and modified date if not included
            if(!array_key_exists("Created", $data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists("Modified", $data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists("Status", $data)){
                $data['Status'] = '1';
            }
            
            // Insert user data
            $insert = $this->db->insert($this->table, $data);
            
            // Return the status
            //return $insert?$this->db->insert_id():false;
            return true;
        }
        return false;
    }
    
    public function is_exist($uuid){
        $query = $this->db->get_where($this->table,array('UUID' => $uuid));
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function pushInsert($dataarray) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");
        //base64 img
        $path = site_url('assets/images/default_profile.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        

        for ($i = 1; $i <= count($dataarray); $i++) {
            
                $uniq = uniqid();
                $UUID = substr($uniq,-10);
                $userData[] = array(
                    'UUID' => $UUID,
                    'Email' => $dataarray[$i]['Email'],
                    'Password' => md5($dataarray[$i]['Password']),
                    'Level' => $dataarray[$i]['Level'],
                    'Foto' => $base64,
                    'Created' => $create,
                    'Modified' => $modified,
                    'Status' => '1'
                );
            
                if($dataarray[$i]['Level'] === "Mahasiswa"){
                    // prepare mahasiswa data
                    $mahasiswaData[] = array(
                        'UUID'=> $UUID,
                        'NIM' => $dataarray[$i]['NomorInduk'],
                        'Nama' =>  $dataarray[$i]['Nama'],
                        'Id_Prodi' => $dataarray[$i]['Prodi'],
                        'Angkatan' =>  $dataarray[$i]['Angkatan'],
                        'created' => $create,
                        'modified' => $modified,
                        'Status' => '1'
                    );
                }
                else{
                    // prepare dosen data 
                    $dosenData[] = array(
                        'UUID'=> $UUID,
                        'NID' => $dataarray[$i]['NomorInduk'],
                        'Nama'=> $dataarray[$i]['Nama'],
                        'Id_Prodi' => $dataarray[$i]['Prodi'],
                        'created' => $create,
                        'modified' => $modified,
                        'Status' => '1'
                    );
                }
                

        }
        // if($keterangan == 'Tambah'){
            $this->db->insert_batch($this->table, $userData);
            $this->db->insert_batch($this->table_m, $mahasiswaData);
            $this->db->insert_batch($this->table_d, $dosenData);
        // }
        // elseif($keterangan == 'Update'){

        // }
       
        // echo "<pre>";
        // print_r($userData);

        // echo "<pre>";
        // print_r($mahasiswaData);
        
        
    }

    /*
     * Update member data into the database
     * @param $data array to be update based on the passed parameters
     * @param $id num filter data
     */
    public function update($data, $id) {
        if(!empty($data) && !empty($id)){
            // Add modified date if not included
            if(!array_key_exists("modified", $data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            
            // Update member data
            $update = $this->db->update($this->table, $data, array('UUID' => $id));
            
            // Return the status
            return $update?true:false;
        }
        return false;
    }
    
    /*
     * Delete member data from the database
     * @param num filter data based on the passed parameter
     */
    public function delete($id){
        // Delete member data
        $delete = $this->db->delete($this->table, array('UUID' => $id));
        
        // Return the status
        return $delete?true:false;
    }
}