<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Tracks extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		// Load url helper
		$this->load->helper('url');
		$this->load->library('session');		
    }

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		//api url to get top tracks
        $url = 'http://ws.audioscrobbler.com/2.0/?method=artist.gettoptracks&artist=cher&api_key=35d4ffbb1c75524030c0fdce6f4e8b30&format=json';
        
        $result = api_curl($url, 'POST');		
        $d['tracks'] = $result->toptracks->track;
        
        // set view template
        $d['v'] = 'tracks';
		$this->load->view('template', $d);
	}
}
