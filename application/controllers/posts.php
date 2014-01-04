<?php

class Posts extends MY_Controller {

    function Posts() {
        parent::MY_Controller();          
        $this->load->model('Posts_model');
		$this->load->library('tank_auth');
        $this->load->library('pagination');

	  if (!$this->tank_auth->is_logged_in()){
		redirect('login');
	  }
    }

    function index() {
        // session check
	if ($this->tank_auth->is_logged_in()){
            $data['title'] = 'Tulisan';
            $config = array(
                    'base_url' => site_url() . '/posts/index/',
                    'total_rows' => $this->db->count_all('blog_Posts'),
                    'per_page' => $this->Kalkun_model->get_setting()->row('paging'),
                    'uri_segment'=> 3,
            );
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['posts'] = $this->Posts_model->get_all($config['per_page'], $this->uri->segment(3));

            $data['main'] = 'main/posts/index';
            $this->load->view('main/layout', $data);
        }else{
            redirect('login');
        }     
    }

    function add() {
		$categories = $this->Posts_model->get_all_categories();
		$data['categories'] = array();
		foreach ($categories->result() as $value) {
		  $data['categories'] [$value->categoryID] = $value->CategoryName;
		}
        $data['main'] = 'main/posts/post';
        $data['type_form'] = 'post';
        $this->load->view('main/layout', $data);
    }

	function ajax_add()
	{
// 		print_r($_POST);
		if ($this->Posts_model->insert($_POST)) {
// 			echo "Post has been saved.";
			$this->session->set_flashdata('notif', 'Post has been saved.');
		} else {
// 			echo "Error while saving post.";
			$this->session->set_flashdata('notif', 'Error while saving post.');
		}
	}

    function update_post($id='') {
        if ($id != '') {

            $data['isi'] = $this->Posts_model->get_one($id);
			$categories = $this->Posts_model->get_all_categories();
			$data['categories'] = array();
			foreach ($categories->result() as $value) {
			  $data['categories'][$value->categoryID] = $value->CategoryName;
			}
            $data['main'] = 'main/posts/post';
            $data['type_form'] = 'update';
            $this->load->view('main/layout', $data);
        } else {
            $this->session->set_flashdata('notif', 'no data');
            redirect('admin/posts');
        }
    }

    function ajax_update_post() {
#        print_r($_POST);
		if ($this->Posts_model->update($_POST)) {
// 			echo "Post has been updated.";
			$this->session->set_flashdata('notif', 'Post has been updated.');
		} else {
// 			echo "Error while saving post.";
			$this->session->set_flashdata('notif', 'Error while saving post.');
		}
    }

    function delete_post() {
        if (isset($_POST['postID'])) {
            $this->Posts_model->delete($_POST['postID']);
            
             $this->session->set_flashdata('notif','data has been deleted');
                redirect('posts');
        } else {
            $this->session->set_flashdata('notif', 'no data deleted');
            redirect('posts');
        }
    }

	function comments() {
		if ($this->tank_auth->is_logged_in()){
            $data['title'] = 'Komentar';
            $config = array(
                    'base_url' => site_url() . '/posts/comments/',
                    'total_rows' => $this->db->count_all('blog_Comments'),
                    'per_page' => $this->Kalkun_model->get_setting()->row('paging'),
                    'uri_segment'=>3
            );
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['comments'] = $this->Posts_model->get_all_comments($config['per_page'], $this->uri->segment(3));

            $data['main'] = 'main/posts/comment';
            $this->load->view('main/layout', $data);
        }else{
            redirect('login');
        }     
	}

	function approve_comment($id='') {
        if ($id != '') {
            if ($this->Posts_model->approve_comment($id)) {
				$this->session->set_flashdata('notif', 'Comment approved.');            	
				redirect('posts/comments');
			} else {
				$this->session->set_flashdata('notif', 'Error while approving comment.');            	
				redirect('posts/comments');
			}
        } else {
            $this->session->set_flashdata('notif', 'no data');
            redirect('posts/comments');
        }
    }

	function categories() {
		if ($this->tank_auth->is_logged_in()){
            $data['title'] = 'Kategori';
            $config = array(
                    'base_url' => site_url() . '/posts/categories/index/',
#                    'total_rows' => $this->db->count_all('blog_Category'),
                    'per_page' => $this->Kalkun_model->get_setting()->row('paging'),
                    'uri_segment'=>3
            );
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $data['categories'] = $this->Posts_model->get_all_categories($config['per_page'], $this->uri->segment(3));
// 			print_r($data['categories']);
            $data['main'] = 'main/posts/category';
            $this->load->view('main/layout', $data);
        }else{
            redirect('404');
        }     
	}

	function add_category() {
		$data['main'] = 'main/posts/form_category';
        $data['type_form'] = 'post';
        $this->load->view('main/layout', $data);
	}

    function ajax_add_category() {
#		print_r($_POST);
        if ($this->Posts_model->insert_category($_POST)) {
// 			echo "Post has been saved.";
			$this->session->set_flashdata('notif', 'Category has been added.');
			redirect ('posts/categories');
		} else {
// 			echo "Error while saving post.";
			$this->session->set_flashdata('notif', 'Error while adding category.');
			redirect ('posts/categories');
		}
    }

	function update_category ($id='') {
		if ($id != '') {

            $data['isi'] = $this->Posts_model->get_one_category($id);
#			print_r($data['isi']);
            $data['main'] = 'main/posts/form_category';
            $data['type_form'] = 'update';
            $this->load->view('main/layout', $data);
        } else {
            $this->session->set_flashdata('notif', 'no data');
            redirect('posts/categories');
        }
	}

	function ajax_update_category () {
#		print_r ($_POST);
		if ($this->Posts_model->update_category ($_POST)) {
// 			echo "Category has been saved.";
			$this->session->set_flashdata('notif', 'Category has been saved.');
			redirect ('posts/categories');
		} else {
// 			echo "Error while saving category.";
			$this->session->set_flashdata ('notif', 'Error while updating category.');
			redirect ('posts/categories');
		}
	}

	function delete_category() {
        if (isset($_POST['categoryID'])) {
            $this->Posts_model->delete_category($_POST['categoryID']);
            
             $this->session->set_flashdata('notif','data has been deleted');
                redirect('posts/categories');
        } else {
            $this->session->set_flashdata('notif', 'no data deleted');
            redirect('posts/categories');
        }
    }
}

?>