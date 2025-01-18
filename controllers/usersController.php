<?php
require_once "models/userModel.php";

class UsersController { 
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function crear (array $arrayUser):void {
        $id=$this->model->insert ($arrayUser);
        ($id==null)?header("location:index.php?tabla=user&accion=crear&error=true&id={$id}"): header("location:index.php?tabla=user&accion=ver&id=".$id);
        exit ();
    }

    public function ver(int $id): ?stdClass
    {
        return $this->model->read($id);
    }

    public function listar (){
        return $this->model->readAll ();
   }

   public function borrar(int $id ): void
{   $usuario= $this->ver($id);
    $borrado = $this->model->delete($id);
    $redireccion = "location:index.php?accion=listar&tabla=user&evento=borrar&id={$id}&usuario={$usuario->usuario}&name={$usuario->name}";
    
    if ($borrado == false) $redireccion .=  "&error=true";
    header($redireccion);
    exit();
}

public function editar (int $id, array $arrayUser):void {
    $usuario= $this->ver($id);
    $editadoCorrectamente=$this->model->edit ($id, $arrayUser);
    //lo separo para que se lea mejor en el word
    $redireccion="location:index.php?tabla=user&accion=editar";
    $redireccion.="&evento=modificar&id={$id}&usuario={$usuario->usuario}&name={$usuario->name}";
    $redireccion.=($editadoCorrectamente==false)?"&error=true":"";
    //vuelvo a la pagina donde estaba
    header ($redireccion);
    exit();
    }

    public function buscar (string $usuario, string $campo, string $orden):array {
        return $this->model->search ($usuario, $campo, $orden);
    }
}