<?php 

include_once('acmapi.php');

class Electricity extends AcmApi{

	private $dates             = array();	//Arreglo de fechas
	private $consumption       = array();	//Arreglo de consumos por fecha
	private $position          = 0;			//posicion actual del arreglo
	private $qtty_dates        = 0;			//numero total de fechas
	private $total_consumption = 0;			//diferencia de consumo entre dos valores
	private $valid_dates       = 0;			//contador de fechas válidas
	private $init              = 0;			//contador de inicio de lectura de fechas

	/**
	 * Método constructor, invoca el metodo constructor de la clase padre AcmApi.
	 */

	function __construct(){
		parent::__construct('electricity');
	}
	
	/**
	 * Método público principal que se encarga de iniciar el proceso de desarrollo del problema,
	 * lee cada una de las lineas del archivo de entrada e inicia los atributos de la clase.
	 * 
	 * @return boolean
	 */

	public function resolve_challenge(){

		$this->read_file();

		if ($this->handle) {
		    while (($line = fgets($this->handle)) !== false) {

		    	if( count(explode(' ', $line)) == 1 ){
	    			
	    			$this->check_for_dates();
	    			if($this->init){
    					echo $this->get_results();
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
		        	$this->extract_consumption( (int)$array_date[3] );
		    	}
		    }

		    $this->close_file();
		} else {
		    // error opening the file.
		}
		return true;
	}

	/**
	 * Inicializa los atributos de la clase a su estado inicial.
	 * @return boolean
	 */

	private function reset_values(){
		$this->dates             = array();
		$this->consumption       = array();
		$this->qtty_dates        = 0;
		$this->total_consumption = 0;
		$this->valid_dates       = 0;
		$this->position          = 0;

		return true;
	}

	/**
	 * Convierte los parámetros de entrada Día, Mes, Año que son recibidos en este orden
	 * respectivamente y son concatenados en una sola cadena de caracteres para luego ser
	 * convertidos en timestamp. Este resultado es guardado en el atributo de la clase $dates.
	 * @param string $d 
	 * @param string $m 
	 * @param string $y 
	 * @return boolean
	 */

	private function extract_dates( $d = '', $m = '', $y = '' ){
		@$time         = strtotime( $y.'-'.$m.'-'.$d );
		$this->dates[] = $time;

		return true;
	}

	/**
	 * El parametro recibido por el método es almacenado en la siguiente posicion libre
	 * del atributo de clase $consumption, este parámetro hace referencia a la lectura
	 * de consumo que esta almacenada en el archivo de entrada.
	 * @param int $c 
	 * @return boolean
	 */

	private function extract_consumption( $c = 0 ){
		$this->consumption[] = $c;

		return true;
	}

	/**
	 * Método de clase que recorre la posicion N y N+1 de cada uno de los elementos almacenados
	 * en el arreglo $dates para comparar si hay un día de diferencia entre ellos. Si esta condicion
	 * se cumple se calcula la diferencia de los consumos en estas dos fechas. Esta diferencia es
	 * almacenada en el atributo de clase $total_consumption.
	 * 
	 * @return boolean
	 */

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

 		return true;
	}

	/**
	 * Método de la clase que calcula segun los dos parámetros de entrada $c1 y $c2 cual
	 * es la diferencia entre ellos (mayor consumo menos menor consumo).
	 * @param int $c1 
	 * @param int $c2 
	 * @return int
	 */

	private function calculate_consumption($c1 = 0, $c2 = 0){
		$total = 0;
		if( $c2 >= $c1 )
			$total =  $c2-$c1;
		return $total;
	}

	/**
	 * Imprime los resultados que es la cadena de caracteres que concatena los parámetros de
	 * clase $valid_dates y $total_consumption.
	 * @return string
	 */

	private function get_results(){
		return $this->valid_dates.' '.$this->total_consumption.'<br />';
	}
}

$electricity =  new Electricity();
$electricity->resolve_challenge();
?>