<?php
namespace Application\Model\Entity;

class TipoObjetos
{
    private $tipo_objeto_id;
    private $tipo_objeto_nombre;
    private $tipo_objeto_estado;
    private $tipo_objeto_icono;    
        
    public function getTipoObjetoId()
    {
        return $this->tipo_objeto_id;
    }

    public function setTipoObjetoId($tipo_objeto_id)
    {
        $this->tipo_objeto_id = $tipo_objeto_id;

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

    public function getTipoObjetoEstado()
    {
        return $this->tipo_objeto_estado;
    }

    public function setTipoObjetoEstado($tipo_objeto_estado)
    {
        $this->tipo_objeto_estado = $tipo_objeto_estado;

        return $this;
    }

    
    public function getTipoObjetoIcono()
    {
        return $this->tipo_objeto_icono;
    }

    public function setTipoObjetoIcono($tipo_objeto_icono)
    {
        $this->tipo_objeto_icono = $tipo_objeto_icono;

        return $this;
    }

    public function exchangeArray($data)
    {
        $this->tipo_objeto_id = (isset($data['tipo_objeto_id'])) ? $data['tipo_objeto_id'] : null;
        $this->tipo_objeto_nombre = (isset($data['tipo_objeto_nombre'])) ? $data['tipo_objeto_nombre'] : null;
        $this->tipo_objeto_estado = (isset($data['tipo_objeto_estado'])) ? $data['tipo_objeto_estado'] : null;
        $this->tipo_objeto_icono = (isset($data['tipo_objeto_icono'])) ? $data['tipo_objeto_icono'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
