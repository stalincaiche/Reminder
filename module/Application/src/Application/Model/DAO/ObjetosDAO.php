<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\Objetos;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class ObjetosDAO
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodos()
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos');
        $select->join(
            'tipo_objeto', 'tipo_objeto.tipo_objeto_id = objetos.objetos_tipo', array(
            'tipo_objeto_nombre',
            ),
            'left'
        );        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function obtenerTodosPorActividad($actividad_id)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos');
        $select->where(array(
            'objetos.objetos_actividad_id' => $actividad_id,
        ));
        $select->join(
            'tipo_objeto', 'tipo_objeto.tipo_objeto_id = objetos.objetos_tipo', array(
            'tipo_objeto_nombre',
            ),
            'left'
        );        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function obtenerPorNombre($nombre)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos');
        $select->where(array(
            'objetos.objetos_nombre' => $nombre,
        ));        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function obtenerPorId($id)
    {
        $id = (int)$id;
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos');
        $select->where(array(
            'objetos.objetos_id' => $id,
        ));        
        $select->join(
            'tipo_objeto', 'tipo_objeto.tipo_objeto_id = objetos.objetos_tipo', array(
            'tipo_objeto_nombre',
            ),
            'left'
        );
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->current();
    }

    public function guardar(Objetos $objeto)
    {
        $id = (int)$objeto->getObjetosId();

        if ($id == "") {

            $data = array(                
                'objetos_actividad_id' => $objeto->getObjetosActividadId(),
                'objetos_nombre' => strtoupper($objeto->getObjetosNombre()),
                'objetos_tipo' => $objeto->getObjetosTipo(),
            );
            
            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            
            return $lastId;
        }
    }

    public function actualizar(Objetos $objeto)
    {
        $id = (int)$objeto->getObjetosId();

        
        if ($this->obtenerPorId($id)) {
            $data = array(
                'objetos_nombre' => strtoupper($objeto->getObjetosNombre()),
                'objetos_tipo' => $objeto->getObjetosTipo(),
            );
            $this->tableGateway->update($data, array('objetos_id' => $id));
            return $id;
        } else {
            throw new \Exception('El Id no existe!');
        }
        
    }

    public function eliminar(Objetos $objeto)
    {
        $this->tableGateway->delete(array('objetos_id' => $objeto->getObjetosId()));
    }
}
