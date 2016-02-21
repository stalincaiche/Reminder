<?php
namespace Application\Form\Login;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;

class LoginFormValidator extends InputFilter
{

    public function __construct()
    {

        $this->add(
            array(
                'name' => 'username',
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
                                NotEmpty::IS_EMPTY => 'Debe ingresar un nombre de Usuario',
                            )
                        ) ,
                    ) ,
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 100,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'El nombre de usuario debe tener minimo 4 caracteres',
                                StringLength::TOO_LONG => 'El nombre debe tener maximo 100 caracteres',
                            )
                        ) ,
                    ) ,
                ) ,
            )
        );

        $this->add(
            array(
                'name' => 'password',
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
                                NotEmpty::IS_EMPTY => 'Debe ingresar una Contraseña',
                            )
                        ) ,
                    ) ,
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 100,
                            'messages' => array(
                                StringLength::TOO_SHORT => 'La contraseña debe tener minimo 4 caracteres',
                                StringLength::TOO_LONG => 'La contraseña debe tener maximo 100 caracteres',
                            )
                        ) ,
                    ) ,
                ) ,
            )
        );
    }
}
