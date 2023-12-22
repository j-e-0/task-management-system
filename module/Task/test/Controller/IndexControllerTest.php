<?php

declare(strict_types=1);

namespace TaskTest\Controller;

use Task\Controller\IndexController;
use Task\Model\TaskTable;
use Task\Model\Task;

use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Laminas\ServiceManager\ServiceManager;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Argument;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    use ProphecyTrait;

    protected $traceError = false;
    protected $taskTable;

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(TaskTable::class, $this->mockTaskTable()->reveal());
    
        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockTaskTable()
    {
        $this->taskTable = $this->prophesize(TaskTable::class);
        return $this->taskTable;
    }

    public function setUp(): void
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    public function testIndexActionCanBeAccessed(): void
    {
        $this->taskTable->fetchAll()->willReturn([]);

        $this->dispatch('/');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('task');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testAddActionCanBeAccessed(): void 
    {
        $this->dispatch('/task/add', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('task');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('task');
    }

    public function testEditActionCanBeAccessed(): void 
    {
        $id = 1;
        $this->taskTable->getTask($id)->willReturn(new Task());

        $this->dispatch("/task/edit/$id", 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('task');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('task');
    }

    public function testDeleteActionCanBeAccessed(): void 
    {
        $id = 1;
        $this->taskTable->getTask($id)->willReturn(new Task());

        $this->dispatch("/task/delete/$id", 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('task');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('task');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout(): void
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('body h1');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $this->taskTable
            ->saveTask(Argument::type(Task::class))
            ->shouldBeCalled();

        $postData = [
            'title'       => 'Task Title 1',
            'description' => 'Task Description 1',
            'status'      => 1,
            'id'          => 1,
        ];
        $this->dispatch('/task/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/task');
    }

    public function testEditActionRedirectsAfterValidPost()
    {
        $id = 1;
        
        $this->taskTable
        ->saveTask(Argument::type(Task::class))
        ->shouldBeCalled();

        $this->taskTable->getTask($id)->willReturn(new Task());   
 
        $postData = [
            'title'       => 'Task Title 1',
            'description' => 'Task Description 1',
            'status'      => 1,
            'id'          => $id,
        ];
        
        $this->dispatch("/task/edit/$id", 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/task');
    }

    public function testDeleteActionRedirectsAfterAcceptPost()
    {
        $id = 1;
        
        $this->taskTable
        ->deleteTask(Argument::exact($id))
        ->shouldBeCalled();

        $postData = [
            'del'       => 'Yes',
            'id'        => $id
        ];
        
        $this->dispatch("/task/delete/$id", 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/task');
    }

    public function testDeleteActionRedirectsAfterUnacceptPost()
    {
        $id = 1;
        
        $this->taskTable
        ->deleteTask(Argument::exact($id))
        ->shouldNotBeCalled();

        $postData = [
            'del'       => 'No',
            'id'        => $id
        ];
        
        $this->dispatch("/task/delete/$id", 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/task');
    }

    public function testInvalidRouteDoesNotCrash(): void
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
