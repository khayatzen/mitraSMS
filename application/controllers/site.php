<?php
/**
 * Kalkun
 * An open source web based SMS Management
 *
 * @package		Kalkun
 * @author		Kalkun Dev Team
 * @license		http://kalkun.sourceforge.net/license.php
 * @link		http://kalkun.sourceforge.net
 */

// ------------------------------------------------------------------------

/**
 * Kalkun Class
 *
 * @package		Kalkun
 * @subpackage	Base
 * @category	Controllers
 */
class Site extends MY_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function Site()
	{
		parent::MY_Controller();	
		$this->load->model('Posts_model');
		$this->load->model('Pages_model');
	}		
		
	// --------------------------------------------------------------------
	
	function index() 
	{
            $data['title'] = 'Beranda';
            $this->load->view('front/home');
	}
        
    function stream_map(){
        $data['title'] = 'Stream Map';
        $this->load->view('front/stream_map');
    }

	function ajax_blogpage($offset = 0)
	{
		$this->load->library('Jquery_pagination');

		$config['base_url'] = site_url('site/ajax_blogpage/');

		$config['div'] = '#BlogPost';

		$config['total_rows'] = count($this->Posts_model->get_posts());
	    $config['per_page'] = 5;

	    $this->jquery_pagination->initialize($config);

	    $this->load->library('table');
            $Paging = "<div id='pagenav' class='pagenav'>" . $this->jquery_pagination->create_links() . "</div>";

	    $html = $Paging. br(1);

		echo $html;
		$Posts = $this->Posts_model->get_all( 5, $offset);
                
                echo '<ul style="list-style:square;">';
		foreach ($Posts as $key => $Post) {
                     if($Post['PostStatus'] != 'draf'){
			$post = substr($Post['PostContent'], 0, 250);
                        $more_title =  (strlen($Post['PostTitle']) > 80) ? "...":""; 
			echo "<li style='list-style:square;'><a href='" . site_url() . "/site/article/" . $Post['PostSlug'] . "'>" . substr($Post['PostTitle'],0,80) . $more_title."</a><div class='stream_date'>".$Post['PostDate']."</div></li>" ;
                     }
		}		
		 echo '</ul>';
		
	}
        function get_archive_blog($offset = 0)
	{
		$this->load->library('Jquery_pagination');

		$config['base_url'] = site_url('site/get_archive_blog/');
		$config['div'] = '#BlogPost';
		$config['total_rows'] = count($this->Posts_model->get_posts());
                $config['per_page'] = 3;
                $this->jquery_pagination->initialize($config);
                $this->load->library('table');
                $Paging = "<div id='pagenav' class='pagenav'>" . $this->jquery_pagination->create_links() . "</div>";
               echo $Paging.br(1);
		$Posts = $this->Posts_model->get_all($config['per_page'], $offset);
		foreach ($Posts as $key => $Post) {
                        $Post['PostContent'] = preg_replace("/<img[^>]+\>/i", "", $Post['PostContent']);
                        if($Post['PostStatus'] != 'draf'){
                            $post = substr($Post['PostContent'], 0, 500);
                            echo "<a href='" . site_url() . "/site/article/" . $Post['PostSlug'] . "'><h2>" . $Post['PostTitle'] . "</h2></a>";
                            echo '<div style="font-size:0.9em;color:#AAAAAA;padding-bottom:10px;">Pada '.simple_date($Post['PostDate']).'</div>';
                            echo $post . " [...]<hr/>";
                        }
		}	
		
		

                $html =  br(1) . $Paging;

		echo $html;
	}

	public function article($slug) {
		$this->load->helper('share');
		//$this->load->library('Tinyurl');
        $data['post'] = $this->Posts_model->get_from_slug($slug);
        $data['title'] = $data['post']['PostTitle'];
        $data['comments'] = $this->Posts_model->get_comment($data['post']['postID']);
#        print_r($data['post']);
		$data['main'] = 'main/posts/article';
        $this->load->view('front/blog', $data);;
	}

	public function page($slug) {
        $data['page'] = $this->Pages_model->get_from_slug($slug);
        $data['title'] = $data['page']['PostTitle'];
//         print_r($data['page']);
		if (!empty($data['page'])) {
		  $data['main'] = 'main/pages/single';
		  $this->load->view('front/blog', $data);
		} else {
		  redirect('404');
		}
	}

	function ajax_add_comment () {	
#		print_r($_POST);
		if ($this->Posts_model->insert_comment($_POST)) {
			echo "Comment successfully added.";
		} else {
			echo "Error adding comment.";
		}
	}
        /*
         * Post Archive
         */
        function archive(){
            $data['title']  = 'Kabar Kampung';
            $data['page']   = 'posts';
            $data['content']= 'archive';   
            $this->load->view('front/template',$data);
        }
}

/* End of file kalkun.php */
/* Location: ./application/controllers/kalkun.php */