<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mahasiswa_model extends CI_Model{
    
    function __construct() {
        // Set table name
        $this->table = 'mahasiswa';
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
            if(!array_key_exists("created", $data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists("modified", $data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            
            // Insert user data
            $insert = $this->db->insert($this->table, $data);
            
            // Return the status
            //return $insert?$this->db->insert_id():false;
            return true;
        }
        return false;
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

    public function pushUpdate($keterangan,$dataarray) {
        
        $create = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");
        
        if($keterangan == '1'){
            for ($i = 1; $i <=count($dataarray); $i++) {
                
                    $userData[] = array(
                        'NIM' => $dataarray[$i]['Nim'],
                        'Nama' => $dataarray[$i]['Nama'],
                        'Semester' => $dataarray[$i]['Semester'],
                        'IPK' => $dataarray[$i]['IPK'],
                        'SKS' => $dataarray[$i]['Total_SKS'],
                        'Modified' => $modified,
                    );
                
            }
            // echo "model<br><pre>";
	  		// print_r($userData);
	  		
            $this->db->update_batch($this->table, $userData,'NIM');
        }
        elseif($keterangan == '2'){
            for ($i = 1; $i <= count($dataarray); $i++) {
                
                    $userData[] = array(
                        'NIM' => $dataarray[$i]['Nim'],
                        'Nama' => $dataarray[$i]['Nama'],
                        'Status' => '0'
                    );
                
            }
            // echo "model<br><pre>";
	  		// print_r($userData);
	  		
           $this->db->update_batch($this->table, $userData,'NIM');
        }
        
    }
}