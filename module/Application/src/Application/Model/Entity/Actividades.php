<?php
namespace Application\Model\Entity;

class Actividades
{

    private $actividades_id;
    private $actividades_nombre;
    private $actividades_fecha;
    private $actividades_estado;
    
    public function getActividadesId()
    {
        return $this->actividades_id;
    }

    public function setActividadesId($actividades_id)
    {
        $this->actividades_id = $actividades_id;

        return $this;
    }

    public function getActividadesNombre()
    {
        return $this->actividades_nombre;
    }

    public function setActividadesNombre($actividades_nombre)
    {
        $this->actividades_nombre = $actividades_nombre;

        return $this;
    }

    public function getActividadesFecha()
    {
        return $this->actividades_fecha;
    }

    public function setActividadesFecha($actividades_fecha)
    {
        $this->actividades_fecha = $actividades_fecha;

        return $this;
    }

    public function getActividadesEstado()
    {
        return $this->actividades_estado;
    }

    public function setActividadesEstado($actividades_estado)
    {
        $this->actividades_estado = $actividades_estado;

        return $this;
    }    

    public function exchangeArray($data)
    {
        $this->actividades_id = (isset($data['actividades_id'])) ? $data['actividades_id'] : null;
        $this->actividades_nombre = (isset($data['actividades_nombre'])) ? $data['actividades_nombre'] : null;
        $this->actividades_fecha = (isset($data['actividades_fecha'])) ? $data['actividades_fecha'] : null;
        $this->actividades_estado = (isset($data['actividades_estado'])) ? $data['actividades_estado'] : null;
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
        
}
