<?php
namespace Application\Model\DAO;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Entity\ObjetosEtiquetas;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

use Zend\Db\Sql\Predicate\Expression;

class ObjetosEtiquetasDAO
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

    public function obtenerPorRelacion($objeto, $etiqueta)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos_etiquetas');
        $select->where(array(
            'objetos_etiquetas.objetos_id' => $objeto,
            'objetos_etiquetas.etiquetas_id' => $etiqueta,
        ));        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function guardar(ObjetosEtiquetas $objetoEtiqueta)
    {
        $id = (int)$objetoEtiqueta->getObjetosEtiquetasId();

        if ($id == "") {

            $data = array(                
                'objetos_id' => $objetoEtiqueta->getObjetosId(),
                'etiquetas_id' => $objetoEtiqueta->getEtiquetasId(),
            );

            $this->tableGateway->insert($data);
            $lastId = $this->tableGateway->adapter->getDriver()->getConnection()->getLastGeneratedValue();
            return $lastId;
        }
    }

    public function obtenerPorObjeto($objeto)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos_etiquetas');
        $select->where(array(
            'objetos_etiquetas.objetos_id' => $objeto,
        ));
        $select->join(
            'etiquetas', 'etiquetas.etiquetas_id = objetos_etiquetas.etiquetas_id', array(
            'etiquetas_nombre','etiquetas_id'
            ),
            'left'
        );        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function obtenerPorEtiqueta($etiqueta)
    {
        $sql = new Sql($this->tableGateway->adapter);
        $select = $sql->select();
        $select->from('objetos_etiquetas');
        $select->where(array(
            'objetos_etiquetas.etiquetas_id' => $etiqueta,
        ));
        $select->join(
            'objetos', 'objetos.objetos_id = objetos_etiquetas.objetos_id', array(
            'objetos_nombre', 'objetos_actividad_id','objetos_tipo'
            ),
            'left'
        );
        $select->join(
            'tipo_objeto', 'tipo_objeto.tipo_objeto_id = objetos.objetos_tipo', array(
            'tipo_objeto_nombre','tipo_objeto_icono'
            ),
            'left'
        );
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function eliminar(ObjetosEtiquetas $objetos_etiqueta)
    {
        $this->tableGateway->delete(array('objetos_etiquetas_id' => $objetos_etiqueta->getObjetosEtiquetasId()));
    }
}
