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
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('actividades');
        $select->join(
            'usuarios', 'usuarios.usuarios_id = actividades.actividades_responsable', array(
            'usuarios_username', 'usuarios_nombres'
            ),
            'left'
        );
        $resultSet = $this->tableGateway->selectWith($select);
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
        $select->join(
            'usuarios', 'usuarios.usuarios_id = actividades.actividades_responsable', array(
            'usuarios_username', 'usuarios_nombres'
            ),
            'left'
        );
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

        if($actividad->getActividadesFecha()){
            $fecha = $actividad->getActividadesFecha();    
        }else{
            $fecha = null;
        }

        if($actividad->getActividadesFechaFin()){
            $fecha_fin = $actividad->getActividadesFechaFin();    
        }else{
            $fecha_fin = null;
        }

        if ($id == "") {

            $data = array(                
                'actividades_nombre' => $actividad->getActividadesNombre(),
                'actividades_fecha' => $fecha,
                'actividades_estado' => $actividad->getActividadesEstado(),
                'actividades_responsable' => $actividad->getActividadesResponsable(),
                'actividades_area' =>  $actividad->getActividadesArea(),
                'actividades_reporta' =>  $actividad->getActividadesReporta(),
                'actividades_fecha_fin' =>  $fecha_fin,
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        } else {
            if ($this->obtenerPorId($id)) {
                $data = array(
                    'actividades_nombre' => $actividad->getActividadesNombre(),
                    'actividades_fecha' => $fecha,
                    'actividades_estado' => $actividad->getActividadesEstado(),
                    'actividades_responsable' => $actividad->getActividadesResponsable(),
                    'actividades_area' =>  $actividad->getActividadesArea(),
                    'actividades_reporta' =>  $actividad->getActividadesReporta(),
                    'actividades_fecha_fin' =>  $fecha_fin,
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

    public function activar(Actividades $actividad)
    {
        $data = array(
            'actividades_estado' => 'A'
        );
        // var_dump($actividad->getActividadesId());exit();
        $this->tableGateway->update($data, array('actividades_id' => $actividad->getActividadesId()));
    }
}
