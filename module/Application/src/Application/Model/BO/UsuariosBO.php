<?php
namespace Application\Model\BO;

use Application\Model\DAO\UsuariosDAO;
use Application\Model\Entity\Usuarios;

class UsuariosBO
{

    protected $tableGateway;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $usuariosDAO = new UsuariosDAO($this->tableGateway);
        $usuarios = $usuariosDAO->obtenerTodos();
        unset($usuariosDAO);
        return $usuarios;
    }

    public function obtenerPorId($id)
    {
        $usuariosDAO = new UsuariosDAO($this->tableGateway);
        try {
            $usuario = $usuariosDAO->obtenerPorId($id);
        }
        catch(\Exception $e) {
            $usuario = 0;
        }
        unset($usuariosDAO);
        return $usuario;
    }

    public function guardar($formData)
    {
        $usuario = new Usuarios();
        $usuario->exchangeArray($formData);
        $usuariosDAO = new UsuariosDAO($this->tableGateway);
        try {
            $usuario = $usuariosDAO->guardar($usuario);
        }
        catch(\Exception $e) {
            $usuario = 0;
        }
        unset($usuariosDAO);
        return $usuario;
    }

    public function eliminar($id)
    {
        $usuario = new Usuarios();
        $usuario->setUsuariosId($id);

        $usuariosDAO = new UsuariosDAO($this->tableGateway);
        $usuariosDAO->eliminar($usuario);
    }

    public function obtenerCombo()
    {
        $usuariosDAO = new UsuariosDAO($this->tableGateway);
        $usuarios = $usuariosDAO->obtenerActivos();
        $usuariosCombo = array();
        //$usuariosCombo[0] = "Seleccione";
        foreach ($usuarios as $key => $value) {
            $usuariosCombo[$value->getUsuariosId()] = $value->getUsuariosNombres();
        }
        return $usuariosCombo;
    }
}
