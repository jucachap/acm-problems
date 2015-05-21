<?php 

include_once('acmapi.php');

class Feynman extends AcmApi{

	function __construct(){
		parent::__construct('feynman');
	}

	public function resolve_challenge(){
		$this->read_file();

		if ($this->handle) {
		    while (($line = fgets($this->handle)) !== false) {
		    	if( (int)$line > 0 )
		    		echo $this->calculate_square( (int)$line ).'<br />';
		    }
	    }
	}

	function calculate_square( $n = 0 ){
		if( $n == 0 ){
			return 0;
		}
		elseif( $n == 1 ){
			return 1;
		}
		else{
			return ($n*$n) + $this->calculate_square( $n-1 );
		}
	}
}

$feynman = new Feynman();
$feynman->resolve_challenge();

?>