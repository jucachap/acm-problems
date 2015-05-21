<?php 

include_once('acmapi.php');

class Electricity extends AcmApi{

	private $dates             = array();
	private $consumption       = array();
	private $position          = 0;
	private $qtty_dates        = 0;
	private $total_consumption = 0;
	private $valid_dates       = 0;
	private $init              = 0;

	function __construct(){
		parent::__construct('electricity');
	}
	
	public function resolve_challenge(){

		$this->read_file();

		if ($this->handle) {
		    while (($line = fgets($this->handle)) !== false) {

		    	if( count(explode(' ', $line)) == 1 ){
	    			
	    			$this->check_for_dates();
	    			if($this->init){
    					$this->print_results();
	    			}
	    			$this->reset_values();
	    			$this->init++;

	    			if(!$this->position){
	    				$this->qtty_dates = $line+0;
	    				$this->position++;
	    			}
	    		}
		    	else{		    		
		    		$array_date = explode(' ', $line);
		        	$this->extract_dates( $array_date[0], $array_date[1], $array_date[2] );
		        	$this->extract_consumption( $array_date[3] );
		    	}
		    }

		    $this->close_file();
		} else {
		    // error opening the file.
		}
	}

	private function reset_values(){
		$this->dates             = array();
		$this->consumption       = array();
		$this->qtty_dates        = 0;
		$this->total_consumption = 0;
		$this->valid_dates       = 0;
		$this->position          = 0;
	}

	private function extract_dates( $d = '', $m = '', $y = '' ){
		@$time         = strtotime( $y.'-'.$m.'-'.$d );
		$this->dates[] = $time;
	}

	private function extract_consumption( $c ){
		$this->consumption[] = $c;
	}

	private function check_for_dates(){
		for($i=0; $i<($this->qtty_dates-1); $i++){

			$date1 = $this->dates[$i];
			$date2 = $this->dates[$i+1];
			
			$datediff = $date2 - $date1;
			$datediff = floor($datediff/(60*60*24));

     		if( $datediff == 1 ){
     			$this->total_consumption += $this->calculate_consumption( $this->consumption[$i], $this->consumption[$i+1] );
     			$this->valid_dates+=1;
     		}
 		}
	}

	private function calculate_consumption($c1 = 0, $c2 = 0){
		$total =  $c2-$c1;
		return $total;
	}

	private function print_results(){
		echo $this->valid_dates.' '.$this->total_consumption.'<br />';
	}
}

$electricity =  new Electricity();
$electricity->resolve_challenge();
?>
	