<?php
class Application_Form_Ajax extends Zend_Form
{
    public function init()
    {
        $this->addElement(new Zend_Form_Element_Text(array(
            'name' => 'test1',
            'label' => 'Jméno',
            'required' => true,
            'validators' => array(
                new Zend_Validate_StringLength(3, 20),
            ),
        )));
        
        $this->addElement(new Zend_Form_Element_Text(array(
            'name' => 'email',
            'label' => 'E-mail',
            'required' => true,
            'validators' => array(
                new Zend_Validate_EmailAddress(),
            ),
        )));
    }
}