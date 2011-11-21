<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rideorama\Entity;

class UserTest extends \ModelTestCase{
    
   
       public function testCanCreateUser()
    {
        $this->assertInstanceOf('Rideorama\Entity\User',new User());
    }
   
      public function testCanSaveFirstAndLastNameAndRetrieveThem()
    {

        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($this->getTestUser());
        $em->flush();

        $users = $em->createQuery('select u from Rideorama\Entity\User u')->execute();
        $this->assertEquals(1,count($users));

        $this->assertEquals('Peter',$users[0]->first_name);
        $this->assertEquals('Hawkins',$users[0]->last_name);
    }

    
     

}

