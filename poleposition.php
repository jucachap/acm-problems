<?php 

include_once('acmapi.php');

class PolePosition extends AcmApi{

	private $pole_positions = array();		//Arreglo de posiciones iniciales
	private $current_position = array();	//Arreglo con las posiciones actuales
	private $car_position = 0;				//Posicion actual de un vehiculo
	private $read_next_lines = 0;			//linea actual del archivo de entrada

	/**
	 * Método constructor, invoca el metodo constructor de la clase padre AcmApi.
	 */

	function __construct(){
		parent::__construct('pole');
	}

	/**
	 * Método público principal que se encarga de iniciar el proceso de desarrollo del problema,
	 * lee cada una de las lineas del archivo de entrada e inicia los atributos de la clase.
	 * Determina si la última línea del archivo es cero para dar finalizado el proceso.
	 * @return boolean
	 */

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
						echo $this->get_results();
		    			$this->reset_values();
		    		}
		    	}
		    }

		    $this->close_file();
		} else {
		    // error opening the file.
		}

		return true;
	}

	/**
	 * Inicializa el parámetro de la clase $pole_positions (arreglo), la cantidad de elementos en el arreglo
	 * dependerá del parámetro de entrada $total_cars. La inicializacion del arreglo contendrá objetos null.
	 * @param int $total_cars 
	 * @return boolean
	 */

	private function init_pole_positions( $total_cars = 0 ){
		$this->pole_positions = array();
		if( $total_cars ){
			for($i=0; $i<$total_cars;$i++){
				$this->pole_positions[$i] = null;
			}
		}

		return true;
	}

	/**
	 * Inicializa los atributos de la clase a su estado inicial.
	 * @return boolean
	 */

	private function reset_values(){
		$this->read_next_lines = 0;
		$this->car_position = 0;
		$this->current_position = array();

		return true;
	}

	/**
	 * Este método calcula la posicion inicial en la partida de la carrera, dependiendo de los parámetros
	 * de entrada que son, los puestos que ha ganado o perdido con respecto a su posicion inicial $lap y
	 * la posicion en la que hace la vuelta a evaluar.
	 * @param array $lap 
	 * @param int $car_position 
	 * @return boolean
	 */

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

		return true;
	}

	/**
	 * Se genera una cadena de caracteres que contiene las posiciones iniciales en las que cada auto
	 * empezo la carrea, las posiciones van separadas por un espacio en blanco.
	 * @return string
	 */

	private function get_results(){
		$result = '';
		for($i=0;$i<count($this->pole_positions);$i++){
			if($this->pole_positions[$i] > 0)
				$result .= $this->pole_positions[$i].' ';
			else{
				$result .= '-1';
				break;
			}
		}
		$result .= '<br />';
		return $result;
	}
}

$pole = new PolePosition();
$pole->resolve_challenge();
?>