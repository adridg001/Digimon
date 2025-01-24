<?php
require_once('config/db.php');

class UserModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }
        public function insert(array $user): ?int {
            try {
                $sql = "INSERT INTO usuarios (nombre, password) VALUES (:nombre, :password);";
                $sentencia = $this->conexion->prepare($sql);
                $arrayDatos = [
                    ":nombre" => $user["nombre"],
                    ":password" => password_hash($user["password"], PASSWORD_DEFAULT)
                ];
                $resultado = $sentencia->execute($arrayDatos);
                return ($resultado == true) ? $this->conexion->lastInsertId() : null;
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "<br>";
                return null;
            }
        }
   /* public function insert(array $user):?int //devuelve entero o null
    { 
        try {
        $sql="INSERT INTO usuarios(nombre, password)  VALUES (:nombre, :password);"; //inyección posicional
       
        $sentencia = $this->conexion->prepare($sql);
        $arrayDatos=[
               ":nombre"=>$user["nombre"],
                ":password"=>password_hash($user["password"],PASSWORD_DEFAULT),
        ];
        $resultado = $sentencia->execute($arrayDatos);
      
        /*Pasar en el mismo orden de los ? execute devuelve un booleano. 
        True en caso de que todo vaya bien, falso en caso contrario.
        //Así podriamos evaluar
         return ($resultado==true)?$this->conexion->lastInsertId():null ;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }*/
       

    public function read(int $id): ?stdClass
    {
       $sentencia = $this->conexion->prepare("SELECT * FROM usuarios WHERE id=:id");
       $arrayDatos = [":id" => $id];
       $resultado = $sentencia->execute($arrayDatos);
       // ojo devuelve true si la consulta se ejecuta correctamente
       // eso no quiere decir que hayan resultados
       if (!$resultado) return null;
       //como sólo va a devolver un resultado uso fetch
       // DE Paso probamos el FETCH_OBJ
       $user = $sentencia->fetch(PDO::FETCH_OBJ); 
       //fetch duevelve el objeto stardar o false si no hay persona
       return ($user == false) ? null : $user;
    }

    public function readAll():array 
    {
    $sentencia = $this->conexion->query("SELECT * FROM usuarios;");
    //usamos método query
    $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);    
    return $usuarios;
 }

 public function delete (int $id):bool
{
    $sql="DELETE FROM usuarios WHERE id =:id";
    try {
        $sentencia = $this->conexion->prepare($sql);
        //devuelve true si se borra correctamente
        //false si falla el borrado
        $resultado= $sentencia->execute([":id" => $id]);
        return ($sentencia->rowCount ()<=0)?false:true;
    }  catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
        return false;
    }
}

public function edit (int $idAntiguo, array $arrayUsuario):bool{
    try {
            $sql="UPDATE usuarios SET name = :name, email=:email, ";
            $sql.= "usuario = :usuario, password= :password ";
            $sql.= " WHERE id = :id;";
            $arrayDatos=[
                    ":id"=>$idAntiguo,
                    ":usuario"=>$arrayUsuario["usuario"],
                    ":password"=>$arrayUsuario["password"],
                    ":name"=>$arrayUsuario["name"],
                    ":email"=>$arrayUsuario["email"],
                    ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos); 
    } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
            }
}

public function search (string $usuario, string $campo, string $orden):array{
    $sentencia = $this->conexion->prepare("SELECT * FROM usuarios WHERE usuario LIKE :usuario WHERE campo LIKE :campo");
    //ojo el si ponemos % siempre en comillas dobles "
    switch ($orden) {
        case 'empieza':
            $arrayDatos=[":usuario"=>"$usuario%" ];
            break;
        case 'termina':
                $arrayDatos=[":usuario"=>"$usuario%" ];
            break;
        case 'contiene':
                $arrayDatos=[":usuario"=>"$usuario%" ];
            break;
        case 'igual':
                $arrayDatos=[":usuario"=>"$usuario" ];
            
            break;
    }
    $arrayDatos=[":usuario"=>"%$usuario%" ];
    $resultado = $sentencia->execute($arrayDatos);
    if (!$resultado) return [];
    $users = $sentencia->fetchAll(PDO::FETCH_ASSOC); 
    return $users; 
    }
    public function login(string $usuario,string $password): ?stdClass {
        $sentencia = $this->conexion->prepare("SELECT * FROM usuarios WHERE nombre=:nombre");
        $arrayDatos = [
            ":nombre" => $usuario,
        ];
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return null;
        $user = $sentencia->fetch(PDO::FETCH_OBJ);
        //fetch duevelve el objeto stardar o false si no hay persona
        // return ($user == false || !password_verify($password, $user->password)) ? null : $user;
        return ($user == false) ? null : $user;
    }

    public function exists(string $campo, string $valor):bool{
        $sentencia = $this->conexion->prepare("SELECT * FROM users WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }
}