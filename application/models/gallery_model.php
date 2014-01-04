<?php

class Gallery_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all($limit, $uri) {

        $result = $this->db->get('Gallery', $limit, $uri);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }
    
    function get_display() {
        $this->db->select('Thumb as thumb, LokasiBerkas AS sources, TipeBerkas AS type, Keterangan AS description');
        $result = $this->db->get('Gallery');
        if ($result->num_rows() > 0) {
            $display = array();
            foreach($result->result_array() AS $key => $value){
                $display[$key] = $value;
            }
            return $display;
        } else {
            return array();
        }
    }
    
    function get_file_type() {
        $all_type = array('photo' => 'Gambar', 'video' => 'Video');
        return $all_type;
    }
    
    function get_all_user() {
        $this->db->select('idUser');
        $result = $this->db->get('Gallery');
        if ($result->num_rows() > 0) {
            $all=array();
            foreach($result->result_array() AS $key=>$value){
                $all[$value['idUser']] = $value['idUser'];
            }
            return $all;
        } else {
            return array();
        }
    }

    function get_one($id) {
        $this->db->where('idGallery', $id);
        $result = $this->db->get('Gallery');
        if ($result->num_rows() == 1) {
            return $result->row_array();
        } else {
            return array();
        }
    }

    function insert() {
           $data = array(
        
            'TipeGallery' => $this->input->post('TipeGallery', TRUE),
           
            'Tanggal' => $this->input->post('Tanggal', TRUE),
           
            'LokasiGallery' => $this->input->post('LokasiGallery', TRUE),
           
            'Thumb' => $this->input->post('Thumb', TRUE),
           
            'idUser' => $this->input->post('idUser', TRUE),
           
            'Keterangan' => $this->input->post('Keterangan', TRUE),
           
        );
        $this->db->insert('Gallery', $data);
    }
    
    function insert_berkas($data) {
        $this->db->insert('Gallery', $data);
    }

    function update($id) {
        $data = array(
         
       'TipeGallery' => $this->input->post('TipeGallery', TRUE),
       
       'Tanggal' => $this->input->post('Tanggal', TRUE),
       
       'LokasiGallery' => $this->input->post('LokasiGallery', TRUE),
       
       'Thumb' => $this->input->post('Thumb', TRUE),
       
       'idUser' => $this->input->post('idUser', TRUE),
       
       'Keterangan' => $this->input->post('Keterangan', TRUE),
       
        );
        $this->db->where('idGallery', $id);
        $this->db->update('Gallery', $data);
    }

    function delete($id) {
        foreach ($id as $sip) {
            $this->db->where('idGallery', $sip);
            $this->db->delete('Gallery');
        }
    }

}
?>
