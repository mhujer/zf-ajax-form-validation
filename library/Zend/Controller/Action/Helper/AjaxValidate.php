<?php
class Zend_Controller_Action_Helper_AjaxValidate extends Zend_Controller_Action_Helper_Abstract
{
    public function direct(Zend_Form $form)
    {
        $this->_injectJsValidation($form);
        $this->_injectJs();
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $data = $this->getRequest()->getParams();
            $check = array($data['element'] => $data['value']);

            if ($form->isValidPartial($check)) {
                $return = array('result' => 'ok');
            } else {
                $msg = $form->getMessages($data['element'], true);
                $return = array('result' => 'fail', 'messages' => array_values($msg));
            }
            $this->getActionController()->getHelper('json')->sendJson($return);
        }
    }
    
    protected function _injectJsValidation(Zend_Form $form)
    {
        foreach ($form->getElements() as $el) {
            if ($el instanceof Zend_Form_Element_Text) { //@todo Add validation for more elements
                $el->setAttrib('class', ($el->getAttrib('class') ? $el->getAttrib('class') . ' ' : '') . 'zf-ajax-validate');
            }
        }
    }
    
    protected function _injectJs()
    {
        $this->getActionController()->view->headScript()->captureStart();
        ?>
        <?php if (false) {?><script type="text/javascript"><?php } //helper for syntax highlight ?>
        $(document).ready(function() {
            $('.zf-ajax-validate').keyup(function(e){
                validate(e.srcElement);
            });
        });
        
        function validate(element) {
            $.post('.', {element: element.name, value: $(element).val()}, function(data) {
                $(element).parent().find('.zf-ajax-validate-error').remove();
                $(element).parent().find('.zf-ajax-validate-ok').remove();
                if (data.result == 'ok') {
                    o = '<ul class="zf-ajax-validate-ok"><li>OK</li></ul>';
                    $(element).parent().append(o);
                } else {
                    o = '<ul class="zf-ajax-validate-error">';
                    $.each(data.messages, function(index,value) {
                        o += '<li>' + value + '</li>';
                    });
                    o += '<ul>';
                    $(element).parent().append(o);
                }
            });
        }
        <?php if (false) {?></script><?php } //helper for syntax highlight ?>
        <?php
        $this->getActionController()->view->headScript()->captureEnd();
    }
}