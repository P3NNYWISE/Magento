<?php
class TestingMagento_Holamundo_IndexController extends Mage_Core_Controller_Front_Action {        
    public function indexAction() {
        echo 'Hello Index... :P!';
    }


    public function goodbyeAction() {
        echo '<h1> bye bye Index... :P! </h1>';

    }
public function paramsAction() {
    echo '<dl>';            
    foreach($this->getRequest()->getParams() as $key=>$value) {
        echo '<dt><strong>Param: </strong>'.$key.'</dt>';
        echo '<dl><strong>Value: </strong>'.$value.'</dl>';
    }
    echo '</dl>';
}


}