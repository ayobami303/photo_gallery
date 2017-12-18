<?php 
/**
* 
*/
class pagination 
{
	public $current_page;
	public $per_page;
	public $total_count;

	function __construct($page=1,$per_page=20,$total_count=0 )
	{
		$this->current_page = (int)$page;
		$this->per_page = (int)$per_page;
		$this->total_count = (int)$total_count;
	}

	public function total_page()
	{
		return ceil($this->total_count/$this->per_page);
	}

	public function offset()
	{
		return ($this->current_page - 1) * $this->per_page;
	}

	public function previous_page($value='')
	{
		return $this->current_page-1;
	}

	public function next_page($value='')
	{
		return $this->current_page+1;
	}
	public function has_next()
	{
		return ($this->current_page< $this->total_page()) ? true : false ;
	}

	public function has_previous($value='')					
	{
		return ($this->current_page > 1) ? true : false ;
	}
	
}
?>