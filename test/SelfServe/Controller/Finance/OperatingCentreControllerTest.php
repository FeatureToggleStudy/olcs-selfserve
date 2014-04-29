<?php

/**
 * Test case for operating centre pages
 *
 * @author Jakub.Igla
 * @todo implement DBUNIT
 */

namespace SelfServe\test\LicenceType;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use SelfServe\test\Bootstrap;

/**
 * Test case for operating centre pages
 *
 * @author Jakub Igla <jakub.igla@valtech.co.uk>
 */
class OperatingCentreControllerTest extends AbstractHttpControllerTestCase
{

    const APPLICATION_ID = 1;
    const OP_CENTRE_ID = 1;

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );

        $this->controller = new \SelfServe\Controller\Finance\OperatingCentreController();

        $this->request = new Request();
        $this->response = new Response();
        $this->routeMatch = new RouteMatch(array('controller' => 'SelfServe\Finance\OperatingCentreController'));
        $this->event = new MvcEvent();
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);

        $this->controller->setServiceLocator(Bootstrap::getServiceManager());
    }

    public function testAddActionCanBeAccessed()
    {
        $this->dispatch('/selfserve/' . self::APPLICATION_ID . '/finance/index/operating-centre/add');

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('selfserve');
        $this->assertControllerName('selfserve\finance\operatingcentrecontroller');
        $this->assertControllerClass('OperatingCentreController');
        $this->assertMatchedRouteName('selfserve/finance/operating_centre_action');
        $this->assertActionName('add');
    }

    public function testForm()
    {
        $form = $this->getOlcsForm('operating-centre');

        //valid data
        $mockData = array(
            'authorised-vehicles' => array(
                'no-of-vehicles' => 69,
                'no-of-trailers' => 23,
                'parking-spaces-confirmation' => '1',
                'permission-confirmation' => '1',
            ),
        );
        $form->setData($mockData);
        $valid = $form->isValid();
        $this->assertEquals(true, $valid);

        $form = $this->getOlcsForm('operating-centre');
        //invalid data
        $mockData = array(
            'authorised-vehicles' => array(
                'no-of-vehicles' => 0,
                'no-of-trailers' => 0,
                'parking-spaces-confirmation' => '1',
                'permission-confirmation' => '1',
            ),
        );
        $form->setData($mockData);
        $valid = $form->isValid();
        $this->assertEquals(false, $valid);

        $form = $this->getOlcsForm('operating-centre');
        //invalid data
        $mockData = array(
            'authorised-vehicles' => array(
                'no-of-vehicles' => 23,
                'no-of-trailers' => 13,
                'parking-spaces-confirmation' => '0',
                'permission-confirmation' => '1',
            ),
        );
        $form->setData($mockData);
        $valid = $form->isValid();
        $this->assertEquals(false, $valid);

        $form = $this->getOlcsForm('operating-centre');
        //invalid data
        $mockData = array(
            'authorised-vehicles' => array(
                'no-of-vehicles' => 23,
                'no-of-trailers' => 13,
                'parking-spaces-confirmation' => '1',
                'permission-confirmation' => '0',
            ),
        );
        $form->setData($mockData);
        $valid = $form->isValid();
        $this->assertEquals(false, $valid);
    }

    protected function getOlcsForm($name)
    {
        $class = new \ReflectionClass('\Common\Controller\FormActionController');
        $method = $class->getMethod('getForm');
        $method->setAccessible(true);
        $form = $method->invokeArgs($this->controller, array($name));

        $form->remove('crsf');
        $form->remove('version');

        return $form;
    }
}
