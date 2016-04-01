<?php
/**
 * app/code/local/MasteringMagento/Example/Helper/Data.php
 *
 * This example code is provided for use with the Mastering Magento video
 * series, by Packt Publishing.
 *
 * @author    Franklin P. Strube <franklin.strube@gmail.com>
 * @category  MasteringMagento
 * @package   Example
 * @copyright Copyright (c) 2012 Packt Publishing (http://packtpub.com)
 */
class MasteringMagento_Example_Helper_Checkout extends Mage_Core_Helper_Abstract
{

    /**
     * Override onestep checkout step data
     *
     * @return array
     */
    public function getCheckoutSteps()
    {
        $_steps = array(); // Original parent steps
        $steps = array(); // Custom steps
        if ( $block = Mage::app()->getLayout()->getBlock('checkout.onepage') ) {
            $_steps = $block->getSteps();
        }

        // Inject our own step into the steps (before the 'billing' step)
        foreach ( $_steps as $stepId => $step ) {
            // This will put our step before billing (but after login)
            if ( $stepId == 'billing' ) {
                $steps['event_registration'] = array(
                    'label' => $this->__('Event Registration'),
                    'allow' => 1,
                    'isShow' => 1
                );
                unset($step['allow']);
            }

            $steps[$stepId] = $step;
        }

        return $steps;
    }

    /**
     * Get active step
     *
     * @return string
     */
    public function getActiveStep()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn() ? 'event_registration' : 'login';
    }
}
