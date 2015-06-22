<?php
/**
 * Cms Popin Observer
 *
 * @category    Sdn
 * @package     Sdn_Popin
 * @author      Svetli Nikolov <me@svetli.name>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Sdn_Popin_Model_Observer
{
    const POPIN_TYPE_VAR    = 'type';
    const POPIN_TYPE_JSON   = 'json';
    const POPIN_TYPE_HTML   = 'html';

    public function handlePageRender($observer)
    {
        $controller = $observer->getEvent()->getControllerAction();
        $type = $controller->getRequest()->getParam(self::POPIN_TYPE_VAR, false);

        if($controller->getRequest()->isXmlHttpRequest())
        {
            if ($type == self::POPIN_TYPE_HTML)
            {
                Mage::app()->getFrontController()
                    ->getResponse()
                    ->setHeader('content-type', 'text/html');

                $controller->getResponse()
                    ->setBody($observer->getPage()->getContent())
                    ->sendResponse();

                exit;
            }
            else if ($type == self::POPIN_TYPE_JSON)
            {
                Mage::app()->getFrontController()
                    ->getResponse()
                    ->setHeader('content-type', 'application/json');

                $controller->getResponse()
                    ->setBody(Mage::helper('core')->jsonEncode(array(
                        'success' => true,
                        'content' => $observer->getPage()->getContent()
                    )))
                    ->sendResponse();

                exit;
            }
        }
    }
}