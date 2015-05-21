<?php 

class AcmApi{
	
	private $name_file = '';
	protected $handle =  null;

	public function __construct( $name_file = '' ){
		if( $name_file ){
			$this->name_file = $name_file;
		}
		return true;
	}

	protected function read_file(){
		if( $this->name_file )
			$this->handle = fopen($this->name_file.".in", "r");
		//var_dump($this->handle);
		return $this->handle;
	}

	protected function close_file(){
		if( $this->handle )
			fclose($this->handle);
		//var_dump($this->handle);
		return true;
	}

}

?>