<?php 

include_once('acmapi.php');

class Feynman extends AcmApi{

	/**
	 * Método constructor, invoca el metodo constructor de la clase padre AcmApi.
	 */

	function __construct(){
		parent::__construct('feynman');
	}

	/**
	 * Método público principal que se encarga de iniciar el proceso de desarrollo del problema,
	 * lee cada una de las lineas del archivo de entrada e inicia los atributos de la clase.
	 * @return boolean
	 */

	public function resolve_challenge(){
		$this->read_file();

		if ($this->handle) {
		    while (($line = fgets($this->handle)) !== false) {
		    	if( (int)$line > 0 && (int)$line <= 100 )
		    		echo $this->calculate_square( (int)$line ).'<br />';
		    }
	    }

	    return true;
	}

	/**
	 * Método recursivo que se encarga de iterar el calculo de cuantos cuadrados caben en un
	 * cuadrado de NxN, recibe como parametro el tamaño del cuadrado N.
	 * @param int $n 
	 * @return int
	 */

	private function calculate_square( $n = 0 ){
		if( $n <= 0 ){
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