<?php

class Posts_model extends Model {

    function Posts_model() {
        parent::Model();
		$this->load->helper('date');
    }

    function get_all($limit=2, $uri) {
        $this->db->join('auth_users', 'auth_users.id = blog_Posts.id_user');
        $this->db->join('blog_Category', 'blog_Category.categoryID = blog_Posts.categoryID');
	$this->db->where('PostType', 'post');
        $this->db->orderby('blog_Posts.postID','DESC');
        $result = $this->db->get('blog_Posts', $limit, $uri);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }
	
    function get_posts($limit=NULL) {
        
        
        $this->db->join('auth_users', 'auth_users.id = blog_Posts.id_user');
        $this->db->join('blog_Category', 'blog_Category.categoryID = blog_Posts.categoryID');
        //$this->db->where('PostStatus !=','draf');
	$this->db->order_by('blog_Posts.postID', 'desc');
        if($limit != NULL)$result = $this->db->get('blog_Posts', $limit);
        else $result = $this->db->get('blog_Posts');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }

	function get_all_comments($limit, $uri) {
        $this->db->join('blog_Posts', 'blog_Posts.postID = blog_Comments.postID');
        $this->db->order_by('commentID', 'desc');
        $result = $this->db->get('blog_Comments', $limit, $uri);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }

	function get_all_categories() {
		$this->db->select ('categoryID, CategoryName, CategoryDescription');
        $result = $this->db->get('blog_Category');
        if ($result->num_rows() > 0) {
            return $result;
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

	function get_one_category($id) {
        $this->db->where('categoryID', $id);
        $result = $this->db->get('blog_Category');
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

			'PostType' => 'post',
           
            'categoryID' => $post['CategoryID'],

			'id_user'	=> $this->session->userdata('id_user'),
        );
#		print_r($data);
        $this->db->insert('blog_Posts', $data);

		return $this->db->affected_rows();
    }

	function insert_category($category) {
		$slug = url_title($category['CategoryName']);
		$slug = strtolower($slug);

        $data = array(
        
            'CategoryName' => $category['CategoryName'],
           
            'CategoryDescription' => $category['CategoryDescription'],
           
            'CategorySlug' => $slug,

        );
// 		print_r($data);
        $this->db->insert('blog_Category', $data);

		return $this->db->affected_rows();
    }

	function insert_comment($post) {

           $data = array(
        
            'CommentDate' => $this->timestamp_to_mysqldatetime(),
           
            'CommentAuthor' => $post['CommentAuthor'],
           
            'CommentAuthorEmail' => $post['CommentAuthorEmail'],
           
            'CommentContent' => $post['CommentContent'],

            'CommentApproved' => 'unapprove',
		
			'postID' => $post['postID'],
           
        );

        $this->db->insert('blog_Comments', $data);

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

			'PostType' => 'post',
           
            'categoryID' => $post['CategoryID'],

			'id_user'	=> $this->session->userdata('id_user'),
           
        );

        $this->db->where('postID', $post['postID']);
        $this->db->update('blog_Posts', $data);

		return $this->db->affected_rows();
    }

	function update_category ($category) {
		$slug = url_title($category['CategoryName']);
		$slug = strtolower($slug);

           $data = array(
        
            'CategoryName' => $category['CategoryName'],
           
            'CategoryDescription' => $category['CategoryDescription'],
           
            'CategorySlug' => $slug,

        );

		$this->db->where('categoryID', $category['categoryID']);
        $this->db->update('blog_Category', $data);

		return $this->db->affected_rows();
	}

    function delete($id) {
        foreach ($id as $sip) {
            $this->db->where('postID', $sip);
            $this->db->delete('blog_Posts');
        }
    }

	function delete_category($id) {
        foreach ($id as $sip) {
            $this->db->where('categoryID', $sip);
            $this->db->delete('blog_Category');
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

	function get_comment ($postID) {
		$where = "postID = '$postID' AND CommentApproved = 'approved'";
		$this->db->where($where);
		$result = $this->db->get('blog_Comments');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
	}

	function approve_comment($id) {

        $data['CommentApproved'] = 'approved';
        $this->db->where('commentID', $id);
        $this->db->update('blog_Comments', $data);

		return $this->db->affected_rows();
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
?>
