<?php
namespace Application\Model\BO;

use Application\Model\DAO\EtiquetasDAO;
use Application\Model\Entity\Etiquetas;
use Application\Model\DAO\ObjetosEtiquetasDAO;
use Application\Model\Entity\ObjetosEtiquetas;


class EtiquetasBO
{

    protected $etiquetasTableGateway;
    protected $objetosEtiquetasTableGateway;    

    public function __construct($etiquetasTableGateway, $objetosEtiquetasTableGateway)
    {
        $this->etiquetasTableGateway = $etiquetasTableGateway;
        $this->objetosEtiquetasTableGateway = $objetosEtiquetasTableGateway;
    }

    public function obtenerTodos()
    {
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        $etiquetas = $etiquetasDAO->obtenerTodos();
        unset($etiquetasDAO);
        return $etiquetas;
    }

    public function obtenerTodosCount()
    {
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        $etiquetas = $etiquetasDAO->obtenerTodosCount();
        unset($etiquetasDAO);
        return $etiquetas;
    }

    public function ingresar($formData)
    {
        $etiqueta = new Etiquetas();
        $etiqueta->exchangeArray($formData);
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        try {
            $etiqueta = $etiquetasDAO->guardar($etiqueta);
        }
        catch(\Exception $e) {
            $etiqueta = 0;
        }
        unset($EtiquetasDAO);
        return $etiqueta;
    }

    public function guardar($formData)
    {
        $db = $this->etiquetasTableGateway->adapter;
        $con = $db->getDriver()->getConnection();
        $con->beginTransaction();

        $etiqueta = new Etiquetas();
        $etiqueta->exchangeArray($formData);
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        try {
            
            $resExistente = $etiquetasDAO->obtenerPorNombre($etiqueta->getEtiquetasNombre());
            
            $objetosEtiquetasDAO = new ObjetosEtiquetasDAO($this->objetosEtiquetasTableGateway);
            if($resExistente->count() == 0){
                //guarda la etiqueta
                $eti = $etiquetasDAO->guardar($etiqueta);                

                
                $bjetosEtiquetas = new ObjetosEtiquetas();
                $bjetosEtiquetas->setObjetosId($formData["objetos_id"]);
                $bjetosEtiquetas->setEtiquetasId($eti);
                // guarda la relacion
                $relacion = $objetosEtiquetasDAO->guardar($bjetosEtiquetas);
            }else{
                
                foreach ($resExistente as $key => $value) {
                    $res = $value;
                }

                $relacion = $objetosEtiquetasDAO->obtenerPorRelacion($formData["objetos_id"], $res->getEtiquetasId());

                if($relacion->count() == 0){
                    //solo relaciona la etiqueta
                    $bjetosEtiquetas = new ObjetosEtiquetas();
                    $bjetosEtiquetas->setObjetosId($formData["objetos_id"]);
                    $bjetosEtiquetas->setEtiquetasId($res->getEtiquetasId());

                    $relacion = $objetosEtiquetasDAO->guardar($bjetosEtiquetas);
                }
            }
            $con->commit();
        }
        catch(\Exception $e) {
            $con->rollback();
            $eti = 0;
        }
        unset($etiquetasDAO);
        unset($etiqueta);
        return $eti;
    }

    public function actualizar($formData)
    {
        $etiqueta = new Etiquetas();
        $etiqueta->exchangeArray($formData);
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        try {
            $etiqueta = $etiquetasDAO->guardar($etiqueta);
        }
        catch(\Exception $e) {
            $etiqueta = 0;
        }
        unset($etiquetasDAO);
        return $etiqueta;
    }

    public function obtenerPorObjeto($objeto)
    {
        $objetosEtiquetasDAO = new ObjetosEtiquetasDAO($this->objetosEtiquetasTableGateway);
        $etiquetas = $objetosEtiquetasDAO->obtenerPorObjeto($objeto);
        unset($objetosEtiquetasDAO);
        return $etiquetas;
    }

    
    public function obtenerObjetosPorEtiquetas($etiqueta)
    {
        $objetosEtiquetasDAO = new ObjetosEtiquetasDAO($this->objetosEtiquetasTableGateway);
        $objetos = $objetosEtiquetasDAO->obtenerPorEtiqueta($etiqueta);
        unset($objetosEtiquetasDAO);
        return $objetos;
    }

    public function obtenerPorId($id)
    {
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        try {
            $etiqueta = $etiquetasDAO->obtenerPorId($id);
        }
        catch(\Exception $e) {
            $etiqueta = 0;
        }
        unset($etiquetasDAO);
        return $etiqueta;
    }

    public function eliminar($id)
    {
        $etiqueta = new Etiquetas();
        $etiqueta->setEtiquetasId($id);

        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        $etiquetasDAO->eliminar($etiqueta);
    }

    public function buscar($objeto)
    {
        $etiquetasDAO = new EtiquetasDAO($this->etiquetasTableGateway);
        $etiquetas = $etiquetasDAO->buscar($objeto);
        unset($etiquetasDAO);
        return $etiquetas;
    }

    public function desvincular($id)
    {
        $objetosEtiquetas = new ObjetosEtiquetas();
        $objetosEtiquetas->setObjetosEtiquetasId($id);

        $objetosEtiquetasDAO = new ObjetosEtiquetasDAO($this->objetosEtiquetasTableGateway);
        $objetosEtiquetasDAO->eliminar($objetosEtiquetas);
    }
}