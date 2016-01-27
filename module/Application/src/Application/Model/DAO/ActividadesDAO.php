<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\Actividades;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class ActividadesDAO
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

    public function obtenerActivas()
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('actividades');
        $select->where(array(
            'actividades.actividades_estado' => 'A',
        ));        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function obtenerPorId($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(
            array(
                'actividades_id' => $id
            )
        );
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar el ID: $id");
        }
        return $row;
    }

    public function guardar(Actividades $actividad)
    {
        $id = (int)$actividad->getActividadesId();

        if ($id == "") {

            $data = array(                
                'actividades_nombre' => $actividad->getActividadesNombre(),
                //'actividades_fecha' => $actividad->getActividadesFecha(),
                'actividades_fecha' => date("Y-m-d H:i:s"),
                'actividades_estado' => $actividad->getActividadesEstado()           
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        } else {
            if ($this->obtenerPorId($id)) {
                $data = array(
                    'actividades_nombre' => $actividad->getActividadesNombre(),
                    //'actividades_fecha' => $actividad->getActividadesFecha(),
                    'actividades_estado' => $actividad->getActividadesEstado()
                );

                $this->tableGateway->update($data, array('actividades_id' => $id));
                return $id;
            } else {
                throw new \Exception('El Id no existe!');
            }
        }
    }

    public function eliminar(Actividades $actividad)
    {
        $this->tableGateway->delete(array('actividades_id' => $actividad->getActividadesId()));
    }

    public function finalizar(Actividades $actividad)
    {
        $data = array(
            'actividades_estado' => 'F'
        );

        $this->tableGateway->update($data, array('actividades_id' => $actividad->getActividadesId()));
    }
}
