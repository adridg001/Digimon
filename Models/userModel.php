<?php
require_once __DIR__ . '/../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

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

    public function login(string $username, string $password): ?stdClass {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':nombre', $username, PDO::PARAM_STR);
            $sentencia->execute();
            $usuario = $sentencia->fetch(PDO::FETCH_OBJ);

            // Verificar si el usuario existe
            if ($usuario && password_verify($password, $usuario->password)) {
                return $usuario; // Devuelve el usuario si todo es correcto
            }

            return null; // Si no coincide la contraseña o el usuario no existe
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return null;
        }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
            return $sentencia->execute();
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return false;
        }
    }

    public function read(int $id): ?stdClass {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return null;
        }
    }

    public function readAll(): array {
        try {
            $sql = "SELECT * FROM usuarios";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "<br>";
            return [];
        }
    }
}