<?php
namespace Application\Model\Entity;

class Actividades
{

    private $actividades_id;
    private $actividades_nombre;
    private $actividades_fecha;
    private $actividades_estado;
    private $actividades_responsable;    
    private $actividades_area;
    private $actividades_reporta;
    private $actividades_fecha_fin;

    private $usuarios_username;
    private $usuarios_nombres;
    
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

    public function getActividadesResponsable()
    {
        return $this->actividades_responsable;
    }

    public function setActividadesResponsable($actividades_responsable)
    {
        $this->actividades_responsable = $actividades_responsable;

        return $this;
    }

    public function getActividadesArea()
    {
        return $this->actividades_area;
    }

    public function setActividadesArea($actividades_area)
    {
        $this->actividades_area = $actividades_area;

        return $this;
    }

    public function getActividadesReporta()
    {
        return $this->actividades_reporta;
    }

    public function setActividadesReporta($actividades_reporta)
    {
        $this->actividades_reporta = $actividades_reporta;

        return $this;
    }

    public function getActividadesFechaFin()
    {
        return $this->actividades_fecha_fin;
    }

    public function setActividadesFechaFin($actividades_fecha_fin)
    {
        $this->actividades_fecha_fin = $actividades_fecha_fin;

        return $this;
    }

    public function getUsuariosUsername()
    {
        return $this->usuarios_username;
    }

    public function getUsuariosNombres()
    {
        return $this->usuarios_nombres;
    }

    public function exchangeArray($data)
    {
        $this->actividades_id = (isset($data['actividades_id'])) ? $data['actividades_id'] : null;
        $this->actividades_nombre = (isset($data['actividades_nombre'])) ? $data['actividades_nombre'] : null;
        $this->actividades_fecha = (isset($data['actividades_fecha'])) ? $data['actividades_fecha'] : null;
        $this->actividades_estado = (isset($data['actividades_estado'])) ? $data['actividades_estado'] : null;
        $this->actividades_responsable = (isset($data['actividades_responsable'])) ? $data['actividades_responsable'] : null;
        $this->actividades_area = (isset($data['actividades_area'])) ? $data['actividades_area'] : null;
        $this->actividades_reporta = (isset($data['actividades_reporta'])) ? $data['actividades_reporta'] : null;
        $this->actividades_fecha_fin = (isset($data['actividades_fecha_fin'])) ? $data['actividades_fecha_fin'] : null;
        $this->usuarios_username = (isset($data['usuarios_username'])) ? $data['usuarios_username'] : null;
        $this->usuarios_nombres = (isset($data['usuarios_nombres'])) ? $data['usuarios_nombres'] : null;
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
        
}
