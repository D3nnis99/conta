<?php 
class DB{   
    public static $conect;
    public static $BaseDatos;
    public static $Servidor;
    public static $Usuario;
    public static $Clave;
    function DB(){    
       self::$BaseDatos = "planillas";
        self::$Servidor = "localhost";
        self::$Usuario = "root";
        self::$Clave = "";
    }
 
    function conectar() {
        if(!($con=@mysqli_connect(self::$Servidor,self::$Usuario,self::$Clave))){
        echo"Error al conectar a la base de datos";
        exit();
        }
        if (!@mysqli::select_db(self::$BaseDatos,$con)){
        echo "Error al seleccionar la base de datos";
        exit();
        }
        self::$conect=$con;
        return true;
    }
}
?>