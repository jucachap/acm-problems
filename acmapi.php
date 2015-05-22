<?php 

class AcmApi{
	
	private $name_file = '';
	protected $handle =  null;

	/**
	 * Método constructor, inicializa el parámetro de la clase $name_file que hace referencia al nombre
	 * del archivo de entrada. Se sobre entiende que este archivo debe tener extension .in
	 * @param string $name_file 
	 * @return boolean
	 */

	public function __construct( $name_file = '' ){
		if( $name_file ){
			$this->name_file = $name_file;
		}

		return true;
	}

	/**
	 * Método que abre un flujo lectura sobre un archivo. El flujo sobre el archivo es solo de lectura.
	 * @return resource
	 */

	protected function read_file(){
		if( $this->name_file )
			$this->handle = fopen($this->name_file.".in", "r");
		
		return $this->handle;
	}

	/**
	 * Cierra el flujo de lectura sobre un archivo.
	 * @return boolean
	 */

	protected function close_file(){
		if( $this->handle )
			fclose($this->handle);
		
		return true;
	}

}

?>