<?php
function get_Post_Params($botsCount){
      $keysBots=array('bot1','bot2');
      foreach($keysBots as $botKey){
	if(!isset($_POST[$botKey])){
	  return false;
	}
	if(!is_numeric(($_POST[$botKey]))){

	}
	if(($_POST[$botKey] < 0) OR ($_POST[$botKey] > $botsCount)){
	  error(400,"wrong parameters");
	  die;
	}
      }
      return array('bot1' => $_POST['bot1'],'bot2' => $_POST['bot2']);
}

function generate_numeric_select($start,$end,$selected,$name,$id){
    $out="<select";
    if($name !== ""){
        $out.=' name="'.$name.'"';
    }
    if($id !== ""){
        $out.=' id="'.$id.'"';
    }
    $out.=">";
    
    if($select == -1){
        for($i=$start; $i <= $end; $i++ ){
            $out.='<option value="'.$i.'">'.$i.'</option>';
        }
    }else{
        for($i=$start; $i < $selected; $i++ ){
            $out.='<option value="'.$i.'">'.$i.'</option>';
        } 
        $out.='<option value="'.$selected.'" selected="selected">'.$selected.'</option>';
        for($i=$selected + 1; $i <= $end; $i++ ){
            $out.='<option value="'.$i.'">'.$i.'</option>';
        }
    }
    return $out."</select>";
    
}