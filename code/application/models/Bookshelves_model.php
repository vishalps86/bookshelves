<?php
class Bookshelves_model extends CI_Model 
{
	private $books = 'books';
	private $shelves = 'shelves';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
		
    }
	
    public function get_books_list($filter_by) {			
        $this->db->select('SQL_CALC_FOUND_ROWS null as rows, b.book_id,b.shelve_id,b.book_name,b.description,b.author', false)->from($this->books .' as b');

        if (isset($filter_by['args']['search_box']) && !empty($filter_by['args']['search_box'])) {
          $this->db->group_start();
          $this->db->like('b.book_name', $filter_by['args']['search_box']);
          $this->db->or_like('b.author', $filter_by['args']['search_box']);
          $this->db->group_end();
        }

		if (isset($filter_by['args']['shelve_id']) && $filter_by['args']['shelve_id']!='') {        
          $this->db->like('b.shelve_id', $filter_by['args']['shelve_id']);        
        }
      
        $query = $this->db->get();

		$result = $query->result_array();
		
        return $result;
    }

	public function get_shelves_list() {
		$this->db->select('shelve_id, name')
        ->from($this->shelves .' as s');    
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
    
   
    public function get_book_detail($book_id) {
        $this->db->select('description')
        ->from($this->books .' as b')
        ->where('book_id', $book_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }    
}