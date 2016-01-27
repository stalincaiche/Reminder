<?php
namespace Application\Model\BO;

use Application\Model\DAO\ActividadesDAO;
use Application\Model\Entity\Actividades;

class ActividadesBO
{

    protected $tableGateway;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $actividadesDAO = new ActividadesDAO($this->tableGateway);
        $actividades = $actividadesDAO->obtenerTodos();
        unset($actividadesDAO);
        return $actividades;
    }

    public function obtenerActivas()
    {
        $actividadesDAO = new ActividadesDAO($this->tableGateway);
        $actividades = $actividadesDAO->obtenerActivas();
        unset($actividadesDAO);
        return $actividades;
    }


    public function obtenerPorId($id)
    {
        $actividadesDAO = new ActividadesDAO($this->tableGateway);
        try {
            $actividades = $actividadesDAO->obtenerPorId($id);
        }
        catch(\Exception $e) {
            $actividades = 0;
        }
        unset($actividadesDAO);
        return $actividades;
    }

    public function guardar($formData)
    {
        $actividad = new Actividades();
        $actividad->exchangeArray($formData);
        $actividadesDAO = new ActividadesDAO($this->tableGateway);
        try {
            $actividad = $actividadesDAO->guardar($actividad);
        }
        catch(\Exception $e) {
            $actividad = 0;
        }
        unset($actividadesDAO);
        return $actividad;
    }

    public function eliminar($id)
    {
        $actividad = new Actividades();
        $actividad->setActividadesId($id);

        $actividadesDAO = new ActividadesDAO($this->tableGateway);
        $actividadesDAO->eliminar($actividad);
    }

    public function finalizar($id)
    {
        $actividad = new Actividades();
        $actividad->setActividadesId($id);

        $actividadesDAO = new ActividadesDAO($this->tableGateway);
        $actividadesDAO->finalizar($actividad);
    }

}
