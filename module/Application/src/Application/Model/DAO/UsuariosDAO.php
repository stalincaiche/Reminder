<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\Usuarios;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class UsuariosDAO
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function obtenerActivos()
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('usuarios');
        $select->where(
            array(
            'usuarios.usuarios_estado' => 'A',
            )
        );        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function obtenerPorId($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(
            array(
                'usuarios_id' => $id
            )
        );
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar el ID: $id");
        }
        return $row;
    }

    public function guardar(Usuarios $usuario)
    {
        $id = (int)$usuario->getUsuariosId();

        if ($id == "") {

            $data = array(                
                'usuarios_username' => $usuario->getUsuariosUsername(),
                'usuarios_nombres' => $usuario->getUsuariosNombres(),
                'usuarios_password' => $usuario->getUsuariosPassword(),
                'usuarios_estado' => $usuario->getUsuariosEstado(),
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        } else {
            if ($this->obtenerPorId($id)) {
                $data = array(                
                    'usuarios_username' => $usuario->getUsuariosUsername(),
                    'usuarios_nombres' => $usuario->getUsuariosNombres(),
                    'usuarios_password' => $usuario->getUsuariosPassword(),
                    'usuarios_estado' => $usuario->getUsuariosEstado(),
                );

                $this->tableGateway->update($data, array('usuarios_id' => $id));
                return $id;
            } else {
                throw new \Exception('El Id no existe!');
            }
        }
    }

    public function eliminar(Usuarios $usuario)
    {
        $this->tableGateway->delete(array('usuarios_id' => $usuario->getUsuariosId()));
    }
}
