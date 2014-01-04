<?php

class Pages_model extends Model {

    function Pages_model() {
        parent::Model();
		$this->load->helper('date');
    }

    function get_all($limit, $uri) {
        $this->db->join('auth_users', 'auth_users.id = blog_Posts.id_user');
		$this->db->where('PostType', 'page');
        $result = $this->db->get('blog_Posts', $limit, $uri);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }
	
	function get_posts($limit) {
        $this->db->join('auth_users', 'auth_users.id = blog_Posts.id_user');
        $result = $this->db->get('blog_Posts', $limit);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }

    function get_one($id) {
        $this->db->where('postID', $id);
        $result = $this->db->get('blog_Posts');
        if ($result->num_rows() == 1) {
            return $result->row_array();
        } else {
            return array();
        }
    }

    function insert($post) {

		if (!isset($post['PostStatus'])) {
			$PostStatus = NULL;
		} else {
			$PostStatus = $post['PostStatus'];
		}
		
		$slug = url_title($post['PostTitle']);
		$slug = strtolower($slug);

           $data = array(
        
            'PostDate' => $this->timestamp_to_mysqldatetime(),
           
            'PostTitle' => $post['PostTitle'],
           
            'PostSlug' => $slug,
           
            'PostContent' => $post['PostContent'],
           
            'PostStatus' => $PostStatus,

			'PostType' => 'page',

			'id_user'	=> $this->session->userdata('id_user'),
        );
#		print_r($data);
        $this->db->insert('blog_Posts', $data);

		return $this->db->affected_rows();
    }

    function update($post) {

		if (!isset($post['PostStatus'])) {
			$PostStatus = NULL;
		} else {
			$PostStatus = $post['PostStatus'];
		}
		
		$slug = url_title($post['PostTitle']);
		$slug = strtolower($slug);

           $data = array(
        
            'PostDate' => $this->timestamp_to_mysqldatetime(),
           
            'PostTitle' => $post['PostTitle'],
           
            'PostSlug' => $slug,
           
            'PostContent' => $post['PostContent'],
           
            'PostStatus' => $PostStatus,

			'PostType' => 'page',

			'id_user'	=> $this->session->userdata('id_user'),
           
        );

        $this->db->where('postID', $post['postID']);
        $this->db->update('blog_Posts', $data);

		return $this->db->affected_rows();
    }

    function delete($id) {
        foreach ($id as $sip) {
            $this->db->where('postID', $sip);
            $this->db->delete('blog_Posts');
        }
    }

	function get_from_slug ($slug) {
// 		$this->db->join('auth_user_profiles', 'auth_user_profiles.id = blog_Posts.id_user');
		$this->db->join('auth_users', 'auth_users.id = blog_Posts.id_user');
		$this->db->where('PostSlug', $slug);
		$result = $this->db->get('blog_Posts');
        if ($result->num_rows() == 1) {
            return $result->row_array();
        } else {
            return array();
        }
	}

/**
	* Convert timestamp to MySQL's DATE or DATETIME (YYYY-MM-DD hh:mm:ss)
	*
	* Returns the DATE or DATETIME equivalent of a given timestamp
	*
	* @author Clemens Kofler <clemens.kofler@chello.at>
	* @access    public
	* @return    string
	*/
	function timestamp_to_mysqldatetime($timestamp = "", $datetime = true)
	{
	  if(empty($timestamp) || !is_numeric($timestamp)) $timestamp = time();

		return ($datetime) ? date("Y-m-d H:i:s", $timestamp) : date("Y-m-d", $timestamp);
	}
}