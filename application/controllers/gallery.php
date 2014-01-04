<?php

class Gallery extends MY_Controller {

    function Gallery() {
        parent::MY_Controller();          
#        $this->load->model('Gallery_model');
		$this->load->library('tank_auth');
        $this->load->library('pagination');
    }

	function index() {

        $config = array(
            'base_url' => site_url() . '/gallery/index/',
            'total_rows' => $this->db->count_all('Gallery'),
            'per_page' => 5,
            'uri_segment'=>4
        );
//         $this->pagination->initialize($config);
//         $data['page'] = $this->page;
        $data['content'] = 'gallery';
//         $data['pagination'] = $this->pagination->create_links();
#        $data['Berkass'] = $this->Gallery_model->get_all($config['per_page'], $this->uri->segment(4));
        $this->load->view('front/template', $data);
    }
    
#    function display() {
#        $data['Berkass'] = $this->Gallery_model->get_display();
#        //print_r($data['Berkass']);        
#        echo json_encode($data['Berkass']);
#    }
#    
#    function gettotalfiles() {
#        echo $this->db->count_all('Berkas');
#    }
}

