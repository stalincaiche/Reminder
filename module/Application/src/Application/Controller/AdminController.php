<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{

	public function indexAction()
    {
        return new ViewModel();
    }

    public function colaborarAction()
    {
    	$layout = $this->layout();
        $layout->setTemplate('layout/layout_colaborar');
        return new ViewModel();
    }
}