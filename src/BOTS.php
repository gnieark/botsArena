<?php


class InvalidBotsException extends UnexpectedValueException{
}

class BOT{
/*
+---------------------+-------------+------+-----+-------------------+----------------+
| Field               | Type        | Null | Key | Default           | Extra          |
+---------------------+-------------+------+-----+-------------------+----------------+
| id                  | int(11)     | NO   | PRI | NULL              | auto_increment |
| name                | text        | NO   |     | NULL              |                |
| game                | varchar(10) | NO   |     | NULL              |                |
| url                 | text        | NO   |     | NULL              |                |
| description         | text        | NO   |     | NULL              |                |
| unclean_description | text        | NO   |     | NULL              |                |
| active              | int(1)      | NO   |     | NULL              |                |
| date_inscription    | timestamp   | NO   |     | CURRENT_TIMESTAMP |                |
| validate_secret     | varchar(8)  | NO   |     | NULL              |                |
| author_email        | text        | NO   |     | NULL              |                |
| ELO                 | int(11)     | NO   |     | 1500              |                |
+---------------------+-------------+------+-----+-------------------+----------------+
*/
  private $id;
  public $name;
  //don't link to game in this class
  public $url;
  public $description;
  public $ELO;
  
  public function __construct($name){
    $this->name = $name;
  }
  public function hydrate ($arr){
    foreach ($arr as $key => $value){
      if (property_exists($this,$key)){
	$this->$key = $value;
      }elseif(is_numeric($key)){
	  //rien, on accepte mais prends pas en compte
      }else{
	throw new InvalidArenaException("incorrect array key".$key);
      }
    }
  }
}