<?php

namespace Tangara\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FOSProfileControllerTest extends WebTestCase
{
  public function testIndex()
    {
        /*
$user = new User();
$user->setRole('ROLE_ADMIN');
$user->setUsername('tangara-admin');
$user->setPassword('password');
*/
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue(true);
    }
}
