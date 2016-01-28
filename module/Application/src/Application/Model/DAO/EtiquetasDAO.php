<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\Etiquetas;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class EtiquetasDAO
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

    public function obtenerTodosCount()
    {
        $sql = new Sql($this->tableGateway->adapter);
        $subQuery = $sql->select();
        $subQuery->from('objetos_etiquetas');
        $subQuery->columns(
            array(
                'count' => new \Zend\Db\Sql\Expression("COUNT(*)"),
            )
        );        
        $subQuery->where('objetos_etiquetas.etiquetas_id = etiquetas.etiquetas_id');
        
        $mainSelect = $this->tableGateway->getSql()->select();
        $mainSelect = $sql->select()->from('etiquetas');
        $mainSelect->columns(
            array(
                'etiquetas_id',
                'etiquetas_nombre',
                'num' => new \Zend\Db\Sql\Expression("(" . @$subQuery->getSqlString($this->tableGateway->adapter->getPlatform()) . ")"),
            )
        );
        // echo( $mainSelect->getSqlString());exit();
        $resultSet = $this->tableGateway->selectWith($mainSelect);
        return $resultSet;        
    }

    public function obtenerPorNombre($nombre)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('etiquetas');
        $select->where(array(
            'etiquetas.etiquetas_nombre' => $nombre,
        ));        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function guardar(Etiquetas $etiqueta)
    {
        $id = (int)$etiqueta->getEtiquetasId();
        if ($id == "") {

            $data = array(                
                'etiquetas_nombre' => $etiqueta->getEtiquetasNombre(),
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        } else {
            if ($this->obtenerPorId($id)) {
                $data = array(
                    'etiquetas_nombre' => $etiqueta->getEtiquetasNombre(),
                );

                $this->tableGateway->update($data, array('etiquetas_id' => $id));
                return $id;
            } else {
                echo "<pre>";var_dump('error');exit();
                throw new \Exception('El Id no existe!');
            }
        }
    }

    public function obtenerPorId($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(
            array(
                'etiquetas_id' => $id
            )
        );
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar el ID: $id");
        }
        return $row;
    }

    public function eliminar(Etiquetas $etiqueta)
    {
        $this->tableGateway->delete(array('etiquetas_id' => $etiqueta->getEtiquetasId()));
    }

    public function buscar($nombre)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('etiquetas');
        // $select->where(array(
        //     'etiquetas.etiquetas_nombre' => $nombre,
        // ));
        $select->where->addPredicate(
            new \Zend\Db\Sql\Predicate\Like('etiquetas.etiquetas_nombre', '%'.$nombre.'%')
        );
        // echo( $select->getSqlString());exit();
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
