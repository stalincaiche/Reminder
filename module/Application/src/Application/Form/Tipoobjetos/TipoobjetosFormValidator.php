<?php
namespace Application\Form\Tipoobjetos;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

class TipoobjetosFormValidator extends InputFilter
{

    public function __construct()
    {

        $this->add(
            array(
                'name' => 'tipo_objeto_nombre',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ) ,
                    array(
                        'name' => 'StringTrim'
                    ) ,
                ) ,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => 'Debe ingresar un nombre para el Tipo',
                            )
                        ) ,
                    ) ,
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 45,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'El nombre debe tener minimo 4 caracteres',
                                StringLength::TOO_LONG => 'El nombre debe tener maximo 45 caracteres',
                            )
                        ) ,
                    ) ,
                ) ,
            )
        );        
    }
}
