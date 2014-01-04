<?php

class Feed extends Controller {

    function Feed() {
        parent::Controller();
		$this->load->helper ('xml');
		$this->load->helper ('text');
		$this->load->model ('Posts_model');
    }

	function index() {
		$data['feed_name'] 	= "BorneoClimate.info";
		$data['encoding']	= "utf-8";
		$data['feed_url']	= 'http://www.borneoclimate.info/feed';
		$data['page_description']	= 'Site description';
		$data['page_language']	= 'en-en';
		$data['creator_email']	= 'admin@borneoclimate.info';
		$data['posts']	= $this->Posts_model->get_posts (10);
		$this->output->set_header("Content-Type: application/rss+xml");
		$this->load->view('front/rss', $data); 
	}
}
