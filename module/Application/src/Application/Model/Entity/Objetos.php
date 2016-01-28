<?php
namespace Application\Model\Entity;

class Objetos
{

    private $objetos_actividad_id;
    private $objetos_id;
    private $objetos_nombre;
    private $objetos_tipo;

    private $tipo_objeto_nombre;
    
    public function getObjetosActividadId()
    {
        return $this->objetos_actividad_id;
    }

    public function setObjetosActividadId($objetos_actividad_id)
    {
        $this->objetos_actividad_id = $objetos_actividad_id;

        return $this;
    }

    public function getObjetosId()
    {
        return $this->objetos_id;
    }

    public function setObjetosId($objetos_id)
    {
        $this->objetos_id = $objetos_id;

        return $this;
    }

    public function getObjetosNombre()
    {
        return $this->objetos_nombre;
    }

    public function setObjetosNombre($objetos_nombre)
    {
        $this->objetos_nombre = $objetos_nombre;

        return $this;
    }

    public function getObjetosTipo()
    {
        return $this->objetos_tipo;
    }

    public function setObjetosTipo($objetos_tipo)
    {
        $this->objetos_tipo = $objetos_tipo;

        return $this;
    }

    public function getTipoObjetoNombre()
    {
        return $this->tipo_objeto_nombre;
    }

    public function setTipoObjetoNombre($tipo_objeto_nombre)
    {
        $this->tipo_objeto_nombre = $tipo_objeto_nombre;

        return $this;
    }

    public function exchangeArray($data)
    {
        $this->objetos_actividad_id = (isset($data['objetos_actividad_id'])) ? $data['objetos_actividad_id'] : null;
        $this->objetos_id = (isset($data['objetos_id'])) ? $data['objetos_id'] : null;
        $this->objetos_nombre = (isset($data['objetos_nombre'])) ? $data['objetos_nombre'] : null;
        $this->objetos_tipo = (isset($data['objetos_tipo'])) ? $data['objetos_tipo'] : null;
        $this->tipo_objeto_nombre = (isset($data['tipo_objeto_nombre'])) ? $data['tipo_objeto_nombre'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
