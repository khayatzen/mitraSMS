<?php

class Pages extends MY_Controller {

    function Pages() {
        parent::MY_Controller();          
        $this->load->model('Pages_model');
		$this->load->library('tank_auth');
        $this->load->library('pagination');

	  if (!$this->tank_auth->is_logged_in()){
		redirect('login');
	  }
    }

    function index() {
        // session check
		if ($this->tank_auth->is_logged_in()){
            $data['title'] = 'Halaman';
            $config = array(
                    'base_url' => site_url() . '/pages/index/',
#                    'total_rows' => $this->db->count_all('blog_Pages'),
                    'per_page' => $this->Kalkun_model->get_setting()->row('paging'),
                    'uri_segment'=> 3,
            );
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['pages'] = $this->Pages_model->get_all($config['per_page'], $this->uri->segment(3));

            $data['main'] = 'main/pages/index';
            $this->load->view('main/layout', $data);
        }else{
            redirect('login');
        }     
    }

    function add() {
        $data['main'] = 'main/pages/pages';
        $data['type_form'] = 'post';
        $this->load->view('main/layout', $data);
    }

	function ajax_add()
	{
// 		print_r($_POST);
		if ($this->Pages_model->insert($_POST)) {
// 			echo "Post has been saved.";
			$this->session->set_flashdata('notif', 'Post has been saved.');
		} else {
// 			echo "Error while saving post.";
			$this->session->set_flashdata('notif', 'Error while saving post.');
		}
	}

    function update_page($id='') {
        if ($id != '') {

            $data['isi'] = $this->Pages_model->get_one($id);
            $data['main'] = 'main/pages/pages';
            $data['type_form'] = 'update';
            $this->load->view('main/layout', $data);
        } else {
            $this->session->set_flashdata('notif', 'no data');
            redirect('/pages');
        }
    }

    function ajax_update_page() {
#        print_r($_POST);
		if ($this->Pages_model->update($_POST)) {
// 			echo "Post has been updated.";
			$this->session->set_flashdata('notif', 'Post has been updated.');
		} else {
// 			echo "Error while saving post.";
			$this->session->set_flashdata('notif', 'Error while saving post.');
		}
    }

    function delete_page() {
        if (isset($_POST['postID'])) {
            $this->Pages_model->delete($_POST['postID']);
            
             $this->session->set_flashdata('notif','data has been deleted');
                redirect('pages');
        } else {
            $this->session->set_flashdata('notif', 'no data deleted');
            redirect('pages');
        }
    }
}
 
