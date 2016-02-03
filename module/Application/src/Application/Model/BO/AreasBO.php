<?php
namespace Application\Model\BO;

use Application\Model\DAO\AreasDAO;
use Application\Model\Entity\Areas;

class AreasBO
{

    protected $tableGateway;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $areasDAO = new AreasDAO($this->tableGateway);
        $areas = $areasDAO->obtenerTodos();
        unset($areasDAO);
        return $areas;
    }

    public function obtenerPorId($id)
    {
        $areasDAO = new AreasDAO($this->tableGateway);
        try {
            $area = $areasDAO->obtenerPorId($id);
        }
        catch(\Exception $e) {
            $area = 0;
        }
        unset($areasDAO);
        return $area;
    }

    public function guardar($formData)
    {
        $area = new Areas();
        $area->exchangeArray($formData);
        $areasDAO = new AreasDAO($this->tableGateway);
        try {
            $area = $areasDAO->guardar($area);
        }
        catch(\Exception $e) {
            $area = 0;
        }
        unset($areasDAO);
        return $area;
    }

    public function eliminar($id)
    {
        $area = new Areas();
        $area->setAreasId($id);

        $areasDAO = new AreasDAO($this->tableGateway);
        $areasDAO->eliminar($area);
    }

    public function obtenerCombo()
    {
        $areasDAO = new AreasDAO($this->tableGateway);
        $areas = $areasDAO->obtenerActivos();
        $AreasCombo = array();
        //$AreasCombo[0] = "Seleccione";
        foreach ($areas as $key => $value) {
            $AreasCombo[$value->getAreasId()] = $value->getAreasNombre();
        }
        return $AreasCombo;
    }
}
