<?php

class Admin_StateControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'State', 'module' => 'admin');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
//        $this->assertQueryContentContains(
//            'div#view-content p',
//            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
//            );
    }

    public function testAddStateAction()
    {
        $params = array('action' => 'addState', 'controller' => 'State', 'module' => 'admin');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
//        $this->assertQueryContentContains(
//            'div#view-content p',
//            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
//            );
    }

    public function testDeleteStateAction()
    {
        $params = array('action' => 'deleteState', 'controller' => 'State', 'module' => 'admin');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        
//        $this->assertQueryContentContains(
//            'div#view-content p',
//            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
//            );
    }

    public function testEditStateAction()
    {
       
         $params = array('action' => 'editState', 'controller' => 'State', 'module' => 'admin');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
//        $this->assertQueryContentContains(
//            'div#view-content p',
//            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
//            );
    }


    public function testWeCanDisplayAddStateForm(){
        $params = array('action' => 'addState', 'controller' => 'State', 'module' => 'admin');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        //check to make sure we don't end up on an error page
        $this->assertNotController('error');
        $this->assertNotAction('error');
         
        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('input[type="text"]', 2);
        $this->assertQueryCount('input[type="submit"]', 1);
          
    }
    
    /**
     * @todo code still needs to be properly 
     */
    public function testAddStateFailsWhenNotPost(){
        
        $this->request->setMethod('get');
        $this->dispatch('/admin/state/add-state');
     //  $this->assertResponseCode(302);
    //    $this->assertRedirect('/admin/state');
    }
    
    
    /**
     * @dataProvider wrongDataProvider
     */
    public function testAddStateFailsWithWrongData($state, $abbv){
        
        $this->request->setMethod('post')
                      ->setPost(array(
                          "name" => $state,
                          "abbv" => $abbv
                      ));
        
        $this->dispatch('/admin/state/add-state');
        
        $this->assertQuery('ul[class="errors"]'); // Page is not submmited and error elems are introduced
    }
    
    public function wrongdataProvider(){
        
        return array (
            array ('', ''), 
            array('', 'MN')
        );
    }
}









