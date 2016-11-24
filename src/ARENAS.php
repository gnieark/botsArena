<?php
class InvalidArenaException extends UnexpectedValueException{
}

class ARENA{
      
  public $name;
  public $bots;
  
  private $path;
  private $id;
  private $url;
  private $title;
  private $metaDescription;
  private $jsFile;
  private $cssFile;
  private $ludusUrl;
        
     /*
     'id'    => "tictactoe",
        'url'   => "/tictactoe",
        'title' => "Tic Tac Toe",
        'metaDescription'   => 'Affrontements de bots au TicTacToe, morpion',
        'jsFile'=> "js.js",
        'cssFile'=> "style.css",
        'ludusUrl' => "/testBotScripts/tictactoe.html"
  */
  public  function get_id(){
    return $this->id;
  }
  public function get_css_code(){
    return file_get_contents($this->path.$this->cssFile);
  }
  public function get_js_code(){
       return file_get_contents($this->path.$this->jsFile);
  }
  public function get_title(){
    return $this->title;
  }
  public function get_meta_description(){
    return $this->metaDescription;
  }
  public function get_ludus_url(){
    return $this->ludusUrl;
  }
  public function get_doc($lang){
    if(file_exists($this->path."doc-".$lang.".html")) return file_get_contents($this->path."doc-".$lang.".html");
    if(file_exists($this->path."doc-fr.html")) return file_get_contents($this->path."doc-fr.html");
    return "";
  }
  
  
  public function __construct($name){
    $this->name = $name;
    $this->path =  __DIR__."/arenas/".$name."/";
    
    if(!is_dir($this->path)){
      throw new InvalidArenaException("No path containing arena ".$name." found ".__DIR__."/".$name."/");
    }
    
    $this->bots = new SplStack();
    
  }
  
  public function hydrate($arr){
    foreach ($arr as $key => $value){
      if (property_exists($this,$key)){
	$this->$key = $value;
      }else{
	throw new InvalidArenaException("incorrect array key");
      }
    }
  }
  public function addBot(BOT $bot){
    $this->bots->push($bot);
  }
}