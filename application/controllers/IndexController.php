<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->doctype('XHTML1_STRICT');
        $this->view->headTitle()->setSeparator(' | ');
        $this->view->headTitle('Webová stránka');
        $this->view->headLink()->appendStylesheet('./styles/styles.css');
        $this->view->headScript()->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js');
    }

    public function indexAction()
    {
        $form = new Application_Form_Ajax();
        $this->_helper->ajaxValidate($form);
        $this->view->form = $form;
    }
}