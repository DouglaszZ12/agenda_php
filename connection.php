<?php
class DB{
    public function conectar(){
        $servidor  = 'localhost';
        $usuario   = 'root';
        $senha     = '';
        $dbname    = 'saperx_agenda_teste';
        $mysqli = mysqli_connect(''.$servidor.'', ''.$usuario.'', ''.$senha.'', ''.$dbname.'');
        return $mysqli;
    }
}
?>
