<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

/**
 * Class TaskControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     *  Adding new task test.
     */
    public function testPostTaskAction()
    {
        $client = $this->createAuthenticatedClient('test', 'test');

        $client->request('POST', '/api/task', [
            "content"   => 'New task test',
            "completed" => 0,
        ]);

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('taskId', $result);

        $this->assertEquals(true, $result['success']);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetTaskAction()
    {
        $client         = $this->createAuthenticatedClient('test', 'test');
        $taskRepository = $this->entityManager->getRepository('AppBundle:Task');
        $task           = $taskRepository->getLastInsertedTaskId();

        $client->request('GET', '/api/task/' . $task['id']);

        $data = json_decode($client->getResponse()->getContent(), true);

        $result = array_shift($data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('createdAt', $result);
        $this->assertArrayHasKey('completed', $result);

        $this->assertEquals('New task test', $result['content']);
        $this->assertEquals($task['id'], $result['id']);
        $this->assertEquals(false, $result['completed']);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUpdateTaskAction()
    {
        $client = $this->createAuthenticatedClient('test', 'test');

        $taskRepository = $this->entityManager->getRepository('AppBundle:Task');
        $task           = $taskRepository->getLastInsertedTaskId();

        $client->request('PUT', '/api/task/' . $task['id'], [
            "content"   => 'New task update',
            "completed" => 1,
        ]);

        $data = json_decode($client->getResponse()->getContent(), true);

        /** @var $updatedTask
         *  Task after update.
         */

        $taskData    = $taskRepository->findTaskById($task['id']);
        $updatedTask = array_shift($taskData);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('taskId', $data);

        $this->assertEquals(true, $data['success']);
        $this->assertEquals($task['id'], $data['taskId']);

        $this->assertEquals('New task update', $updatedTask['content']);
        $this->assertEquals(true, $updatedTask['completed']);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testDeleteTaskAction()
    {
        $client = $this->createAuthenticatedClient('test', 'test');

        $taskRepository = $this->entityManager->getRepository('AppBundle:Task');
        $task           = $taskRepository->getLastInsertedTaskId();

        $client->request('DELETE', '/api/task/' . $task['id']);

        $data = json_decode($client->getResponse()->getContent(), true);

        $taskData = $taskRepository->findTaskById($task['id']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, count($data));
        $this->assertEquals(0, count($taskData));

        $this->assertArrayNotHasKey('taskId', $data);
    }


    /**
     * @param $username
     * @param $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username, $password)
    {
        $client    = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');

        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');

        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(['username' => $username]);

        $loginManager->loginUser($firewallName, $user);

        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.token_storage')->getToken()));

        $container->get('session')->save();

        $client->request(
            'POST',
            '/login_check',
            [
                '_username' => $username,
                '_password' => $password,
            ]
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }
}