<?php
namespace Application\Model\Entity;

class Usuarios
{
    private $usuarios_id;
    private $usuarios_username;
    private $usuarios_nombres;
    private $usuarios_password;
    private $usuarios_estado;

    public function getUsuariosId()
    {
        return $this->usuarios_id;
    }

    public function setUsuariosId($usuarios_id)
    {
        $this->usuarios_id = $usuarios_id;

        return $this;
    }

    public function getUsuariosUsername()
    {
        return $this->usuarios_username;
    }

    public function setUsuariosUsername($usuarios_username)
    {
        $this->usuarios_username = $usuarios_username;

        return $this;
    }

    public function getUsuariosNombres()
    {
        return $this->usuarios_nombres;
    }

    public function setUsuariosNombres($usuarios_nombres)
    {
        $this->usuarios_nombres = $usuarios_nombres;

        return $this;
    }

    public function getUsuariosPassword()
    {
        return $this->usuarios_password;
    }

    public function setUsuariosPassword($usuarios_password)
    {
        $this->usuarios_password = $usuarios_password;

        return $this;
    }

    public function getUsuariosEstado()
    {
        return $this->usuarios_estado;
    }

    public function setUsuariosEstado($usuarios_estado)
    {
        $this->usuarios_estado = $usuarios_estado;

        return $this;
    }

    public function exchangeArray($data)
    {
        $this->usuarios_id = (isset($data['usuarios_id'])) ? $data['usuarios_id'] : null;
        $this->usuarios_username = (isset($data['usuarios_username'])) ? $data['usuarios_username'] : null;
        $this->usuarios_nombres = (isset($data['usuarios_nombres'])) ? $data['usuarios_nombres'] : null;
        $this->usuarios_password = (isset($data['usuarios_password'])) ? $data['usuarios_password'] : null;
        $this->usuarios_estado = (isset($data['usuarios_estado'])) ? $data['usuarios_estado'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }



}
