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
            $el->addDecorator(
                'HtmlTag',
                array(
                    'tag' => 'p',
                    'class' => 'hint',
                    'id' => 'info_' . $el->getName(),
                    'placement' => 'APPEND'
                )
            );
            $el->setAttrib('class', ($el->getAttrib('class') ? $el->getAttrib('class') . ' ' : '') . 'zf-ajax-validate');
        }
    }
    
    protected function _injectJs()
    {
        $this->getActionController()->view->headScript()->captureStart();
        ?>
        <?php if (false) {?><script type="text/javascript"><?php } //helper pro syntax highlight ?>
        $(document).ready(function() {
            $('.zf-ajax-validate').keyup(function(e){
                validate(e.srcElement);
            });
        });
        
        function validate(element) {
            $.post('.', {element: element.name, value: $(element).val()}, function(data) {
                if (data.result == 'ok') {
                    $('#info_' + element.name).addClass("zf-ajax-validate-ok");
                    $('#info_' + element.name).removeClass("zf-ajax-validate-error");
                    $('#info_' + element.name).html('OK');
                } else {
                    $('#info_' + element.name).removeClass("zf-ajax-validate-ok");
                    $('#info_' + element.name).addClass("zf-ajax-validate-error");
                    var out = '';
                    $.each(data.messages, function(index,value) {
                        out += '<p>' + value + '</p>';
                    });
                    $('#info_' + element.name).html(out);
                }
            });
        }
        <?php if (false) {?></script><?php } //helper pro syntax highlight ?>
        <?php
        $this->getActionController()->view->headScript()->captureEnd();
    }
}