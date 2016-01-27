<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RetornaEtiquetas extends AbstractHelper
{
    private $EtiquetasBO;
        
    function __construct($EtiquetasBO)
    {
        $this->EtiquetasBO = $EtiquetasBO;
    }

    public function __invoke($objeto)
    {
        $recurso = $this->EtiquetasBO->obtenerPorObjeto($objeto);
        return $recurso;
    }
}
