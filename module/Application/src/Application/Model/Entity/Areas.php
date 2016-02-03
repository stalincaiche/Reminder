<?php
namespace Application\Model\Entity;

class Areas
{
    private $areas_id;
    private $areas_nombre;
    private $areas_estado;
    
    public function getAreasId()
    {
        return $this->areas_id;
    }

    public function setAreasId($areas_id)
    {
        $this->areas_id = $areas_id;

        return $this;
    }

    public function getAreasNombre()
    {
        return $this->areas_nombre;
    }

    public function setAreasNombre($areas_nombre)
    {
        $this->areas_nombre = $areas_nombre;

        return $this;
    }

    public function getAreasEstado()
    {
        return $this->areas_estado;
    }

    public function setAreasEstado($areas_estado)
    {
        $this->areas_estado = $areas_estado;

        return $this;
    }

    public function exchangeArray($data)
    {
        $this->areas_id = (isset($data['areas_id'])) ? $data['areas_id'] : null;
        $this->areas_nombre = (isset($data['areas_nombre'])) ? $data['areas_nombre'] : null;
        $this->areas_estado = (isset($data['areas_estado'])) ? $data['areas_estado'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
