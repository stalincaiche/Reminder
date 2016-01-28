<?php
namespace Application\Model\BO;

use Application\Model\DAO\TipoObjetosDAO;
use Application\Model\Entity\TipoObjetos;

class TipoObjetosBO
{

    protected $tableGateway;

    public function __construct($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $tipoObjetosDAO = new TipoObjetosDAO($this->tableGateway);
        $tipoObjetos = $tipoObjetosDAO->obtenerTodos();
        unset($tipoObjetosDAO);
        return $tipoObjetos;
    }

    public function obtenerPorId($id)
    {
        $tipoObjetosDAO = new TipoObjetosDAO($this->tableGateway);
        try {
            $tipo = $tipoObjetosDAO->obtenerPorId($id);
        }
        catch(\Exception $e) {
            $tipo = 0;
        }
        unset($tipoObjetosDAO);
        return $tipo;
    }

    public function guardar($formData)
    {
        $tipoObjeto = new TipoObjetos();
        $tipoObjeto->exchangeArray($formData);
        $tipoObjetosDAO = new TipoObjetosDAO($this->tableGateway);
        try {
            $tipoObjeto = $tipoObjetosDAO->guardar($tipoObjeto);
        }
        catch(\Exception $e) {
            $tipoObjeto = 0;
        }
        unset($tipoObjetosDAO);
        return $tipoObjeto;
    }

    public function eliminar($id)
    {
        $tipoObjeto = new TipoObjetos();
        $tipoObjeto->setTipoObjetoId($id);

        $tipoObjetosDAO = new TipoObjetosDAO($this->tableGateway);
        $tipoObjetosDAO->eliminar($tipoObjeto);
    }

    public function obtenerCombo()
    {
        $tipoObjetosDAO = new TipoObjetosDAO($this->tableGateway);
        $tiposObjeto = $tipoObjetosDAO->obtenerActivos();
        $tiposObjetoCombo = array();
        //$tiposObjetoCombo[0] = "Seleccione";
        foreach ($tiposObjeto as $key => $value) {
            $tiposObjetoCombo[$value->getTipoObjetoId()] = $value->getTipoObjetoNombre();
        }
        return $tiposObjetoCombo;
    }
}
