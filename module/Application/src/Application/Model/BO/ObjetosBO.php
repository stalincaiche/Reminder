<?php
namespace Application\Model\BO;

use Application\Model\DAO\ObjetosDAO;
use Application\Model\Entity\Objetos;

class ObjetosBO
{

    protected $tableGateway;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $objetosDAO = new ObjetosDAO($this->tableGateway);
        $objetos = $objetosDAO->obtenerTodos();
        unset($objetosDAO);
        return $objetos;
    }

    public function obtenerTodosPorActividad($id)
    {
        $objetosDAO = new ObjetosDAO($this->tableGateway);
        $objetos = $objetosDAO->obtenerTodosPorActividad($id);
        unset($objetosDAO);
        return $objetos;
    }

    public function obtenerPorId($id)
    {
        $objetosDAO = new ObjetosDAO($this->tableGateway);
        try {
            $objetos = $objetosDAO->obtenerPorId($id);
        }
        catch(\Exception $e) {
            $objetos = 0;
        }
        unset($objetosDAO);
        return $objetos;
    }

    public function obtenerPorNombre($nombre)
    {
        $nombre = strtoupper($nombre);
        $objetosDAO = new ObjetosDAO($this->tableGateway);
        try {
            $objetos = $objetosDAO->obtenerPorNombre($nombre);
            
            if($objetos->count() > 0){
                return true;
            }else{
                return false;
            }
        }
        catch(\Exception $e) {
            $objetos = 0;
        }
        unset($objetosDAO);
        return $objetos;
    }

    public function guardar($formData)
    {
        $objeto = new Objetos();
        $objeto->exchangeArray($formData);
        $objetosDAO = new ObjetosDAO($this->tableGateway);
        try {
            $existe = $this->obtenerPorNombre($objeto->getObjetosNombre());
            
            if($existe){
                $objeto = $objetosDAO->actualizar($objeto);                
            }else{
                $objeto = $objetosDAO->guardar($objeto);
            }
        }
        catch(\Exception $e) {
            $objeto = 0;
        }
        unset($objetosDAO);
        return $objeto;
    }

    public function eliminar($id)
    {
        $objeto = new Objetos();
        $objeto->setObjetosId($id);

        $objetosDAO = new ObjetosDAO($this->tableGateway);
        $objetosDAO->eliminar($objeto);
    }

}
