<?php
//error_reporting(E_ALL);/
//ini_set('display_errors',1);
defined('BASEPATH') or exit('No direct script access allowed');

class Bookshelves extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Bookshelves_model');        
    }

    /**
     * Index Page for this controller.
     */
    public function index() {
		
        $data_string = array();
        $data_string['args'] = _get_query_args();      	
		
		$data = $this->Bookshelves_model->get_books_list($data_string);
		$shelve_id = $data_string['args']['shelve_id'];
		$shelves = $this->Bookshelves_model->get_shelves_list();
		
        $d['result'] = $data;
		$d['shelves'] = $shelves;
		$d['shelve_id'] = $shelve_id;
        $d['search_box'] = (isset($data_string['args']['search_box'])) ? $data_string['args']['search_box'] : '';
        $d['callback'] = 'bookshelves/index';

        // set js file to be load in view
        $d['js'][] = $this->viperks->load_script('assets/js/vip/bookshelves.js');

        $d['v'] = 'bookshelves/bookshelves';
        $this->load->view('template', $d);
    }

  
    /**
     * create edit user form page
     *
     * @param integer $uid
     */
    public function view($book_id) {

        $data_string = array();

        $data_string['args']['book_id'] = $book_id;

		$book_detail = $this->Bookshelves_model->get_book_detail($book_id);		
		$d['book_detail'] = $book_detail;
        $d['v'] = 'bookshelves/detail';
        $this->load->view('template', $d);
    }

}
