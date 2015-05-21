<?php 

include_once('acmapi.php');

class PolePosition extends AcmApi{

	private $pole_positions = array();
	private $current_position = array();
	private $car_position = 0;
	private $read_next_lines = 0;

	function __construct(){
		parent::__construct('pole');
	}

	public function resolve_challenge(){
		$this->read_file();

		if ($this->handle) {
		    while (($line = fgets($this->handle)) !== false) {

		    	if( count(explode(' ', $line)) == 1 ){
		    		if((int)$line > 0){
		    			$this->read_next_lines = (int)$line;
	    				$this->init_pole_positions( $this->read_next_lines );
		    		}
		    		else{
		    			break;
		    		}
	    		}
		    	else{
		    		$this->car_position++;
		    		$lap = explode(' ', $line);
		    		$this->calculate_pole( $lap, $this->car_position );

		    		$this->read_next_lines--;
		    		
		    		if( !$this->read_next_lines ){
						$this->print_results();
		    			$this->reset_values();
		    		}
		    	}
		    }

		    $this->close_file();
		} else {
		    // error opening the file.
		}
	}

	private function init_pole_positions( $total_cars = 0 ){
		$this->pole_positions = array();
		if( $total_cars ){
			for($i=0; $i<$total_cars;$i++){
				$this->pole_positions[$i] = null;
			}
		}
	}

	private function reset_values(){
		$this->read_next_lines = 0;
		$this->car_position = 0;
		$this->current_position = array();
	}

	private function calculate_pole( $lap = array(), $car_position = 0 ){
		if( !empty($lap) ){
			
			if( $lap[1] == 0 ){
				$this->pole_positions[$car_position-1] = $lap[0];
			}
			elseif( $this->pole_positions[(($car_position+(int)$lap[1])-1)] == null ){
				$this->pole_positions[(($car_position+(int)$lap[1])-1)] = $lap[0];
			}
			else{
				$this->pole_positions[0] = -1;
			}
		}
	}

	private function print_results(){
		for($i=0;$i<count($this->pole_positions);$i++){
			if($this->pole_positions[$i] > 0)
				echo $this->pole_positions[$i].' ';
			else{
				echo -1;
				break;
			}
		}
		echo '<br />';
	}
}

$pole = new PolePosition();
$pole->resolve_challenge();

?>