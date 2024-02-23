<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class UserControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        /** @var Router */
        $router = $client->getContainer()->get(RouterInterface::class);

        $crawler = $client->request('GET', $router->generate('register'));

        $this->assertResponseIsSuccessful();
        
        //$this->assertSelectorTextContains('h1', 'Hello World');
        $titles = $client->getCrawler()->filter('h1');
        $this->assertCount(1, $titles);
        $this->assertEquals('Create your account.', $titles->text());
    }

    public function testRegistrationPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // When form is not valid
        $client->submitForm('Create your account.', [
            'user[firstName]' => 'Joseph',
        ]);

        $this->assertCount(3, $client->getCrawler()->filter('.invalid-feedback'));
    }
}
