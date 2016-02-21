<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\Areas;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class AreasDAO
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
        $select->from('areas');
        $select->where(
            array(
            'areas.areas_estado' => 'A',
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
                'areas_id' => $id
            )
        );
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar el ID: $id");
        }
        return $row;
    }

    public function guardar(Areas $area)
    {
        $id = (int)$area->getAreasId();

        if ($id == "") {

            $data = array(                
                'areas_nombre' => $area->getAreasNombre(),
                'areas_estado' => $area->getAreasEstado(),
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        } else {
            if ($this->obtenerPorId($id)) {
                $data = array(                
                    'areas_nombre' => $area->getAreasNombre(),
                    'areas_estado' => $area->getAreasEstado(),
                );

                $this->tableGateway->update($data, array('areas_id' => $id));
                return $id;
            } else {
                throw new \Exception('El Id no existe!');
            }
        }
    }

    public function eliminar(Areas $area)
    {
        $this->tableGateway->delete(array('areas_id' => $area->getAreasId()));
    }
}
