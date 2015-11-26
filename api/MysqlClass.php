<?php
Class MysqlClass {

	/**
	 * @access public
	 * @var array
	 */
	public $host = 'localhost';
	
	/**
	 * @access public
	 * @var array
	 */
	public $user = 'root';
	
	/**
	 * @access public
	 * @var array
	 */
	public $password = '';
	
	/**
	 * @access public
	 * @var array
	 */
	public $database = 'mydb';

	/**
	 * @access private
	 * @var array
	 */
	private $connection;

	/**
	 * __construct
	 * 
	 * String $host 
	 * String $user 
	 * String $password
	 * String $database
	 *
	 */
	function __construct( $host, $user, $password, $database  )
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}

	/**
	 * connect
	 *
	 * Realizo a conexão com o banco de dados
	 *
	 * @access private
	 * @return void
	 */
	private function connect( ) {
		$this->connection = mysql_connect($this->host, $this->user, $this->password) or print (mysql_error()); 
		mysql_select_db($this->database, $this->connection) or print(mysql_error()); 
	}

	/**
	 * close
	 * 
	 * Fecho a conexão com o banco de dados
	 *
	 * @access private
	 * @return void
	 */
	private function close( ) {
		mysql_close($this->connection);
	}

	/**
	 * connect
	 * 
	 * String $sql 
	 *
	 * Realizo a consulta com o banco de dados
	 *
	 * @access public
	 * @return void
	 */
	public function query( $sql ) {

		$this->connect();

		$result = mysql_query($sql, $this->connection); 
		if (!$result) {
		    echo 'Não foi possível executar a consulta: ' . mysql_error();
		    exit;
		}

		// caso tenha um retorno eu organizo ele para mostrar
		$out = array();
		if( ! is_bool( $result ) ){
			while($data = mysql_fetch_object($result)) { 
			  $out[] = get_object_vars($data);
			}
			mysql_free_result($result); 
		}

		$this->close();

		return $out;
	}

	/**
	 * antiInjection
	 * 
	 * @access public
	 * @return void
	 */
	public function antiInjection( $value ) {
		$this->connect();
		$value = mysql_real_escape_string(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
		$this->close();
		return $value;
	}

	/**
	 * pr
	 * 
	 * @access public
	 * @return void
	 */
	public function pr( $value ) {
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	}



}