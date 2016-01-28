<?php
namespace Application\Model\Entity;

class Etiquetas
{
    private $etiquetas_id;
    private $etiquetas_nombre;
    private $num;
        
    public function getEtiquetasId()
    {
        return $this->etiquetas_id;
    }

    public function setEtiquetasId($etiquetas_id)
    {
        $this->etiquetas_id = $etiquetas_id;

        return $this;
    }

    public function getEtiquetasNombre()
    {
        return $this->etiquetas_nombre;
    }

    public function setEtiquetasNombre($etiquetas_nombre)
    {
        $this->etiquetas_nombre = $etiquetas_nombre;

        return $this;
    }

    public function getNum()
    {
        return $this->num;
    }

    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    public function exchangeArray($data)
    {
        $this->etiquetas_id = (isset($data['etiquetas_id'])) ? $data['etiquetas_id'] : null;
        $this->etiquetas_nombre = (isset($data['etiquetas_nombre'])) ? $data['etiquetas_nombre'] : null;        
        $this->num = (isset($data['num'])) ? $data['num'] : null;        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
