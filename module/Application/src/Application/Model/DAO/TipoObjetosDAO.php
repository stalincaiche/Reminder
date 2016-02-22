<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\TipoObjetos;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class TipoObjetosDAO
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
        $select->from('tipo_objeto');
        $select->where(
            array(
            'tipo_objeto.tipo_objeto_estado' => 'A',
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
                'tipo_objeto_id' => $id
            )
        );
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar el ID: $id");
        }
        return $row;
    }

    public function guardar(TipoObjetos $actividad)
    {
        $id = (int)$actividad->getTipoObjetoId();

        if ($id == "") {

            $data = array(                
                'tipo_objeto_nombre' => $actividad->getTipoObjetoNombre(),
                'tipo_objeto_estado' => $actividad->getTipoObjetoEstado(),
                'tipo_objeto_icono' => $actividad->getTipoObjetoIcono()
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        } else {
            if ($this->obtenerPorId($id)) {
                $data = array(
                    'tipo_objeto_nombre' => $actividad->getTipoObjetoNombre(),
                    'tipo_objeto_estado' => $actividad->getTipoObjetoEstado(),
                    'tipo_objeto_icono' => $actividad->getTipoObjetoIcono()
                );

                $this->tableGateway->update($data, array('tipo_objeto_id' => $id));
                return $id;
            } else {
                throw new \Exception('El Id no existe!');
            }
        }
    }

    public function eliminar(TipoObjetos $tipo)
    {
        $this->tableGateway->delete(array('tipo_objeto_id' => $tipo->getTipoObjetoId()));
    }
}
