<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\SettingsBundle\Tests\Unit\Controller;

use ONGR\SettingsBundle\Controller\SettingsListController;
use ONGR\ElasticsearchBundle\Test\ElasticsearchTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

class SettingsListControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EngineInterface
     */
    public function getTemplateEngine()
    {
        $template = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $template->expects($this->any())->method('renderResponse')->will($this->returnArgument(1));

        return $template;
    }

    /**
     * Test for list action.
     */
    public function testListAction()
    {
        $container = new ContainerBuilder();

        $container->set('es.manager', $this->getManagerWithRepositoryMock());
        $container->set('templating', $this->getTemplateEngine());
        $container->set('router', $this->getMock('Symfony\\Component\\Routing\\RouterInterface'));

        $controller = new SettingsListController();

        $controller->setContainer($container);
        $requestQuery = ['q' => 'testName'];
        $result = $controller->listAction(new Request($requestQuery));
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('testName', $result['filters']['search']->getState()->getValue());
    }

    /**
     * Returns manager instance with injected connection if does not exist creates new one.
     *
     * @return Manager
     */
    protected function getManagerWithRepositoryMock()
    {
        $managerMock = $this->getManagerMock();

        $repositoryMock = $this->getOrmRepositoryMock();
        $managerMock->expects(
            $this->once()
        )->method('getRepository')
            ->willReturn($repositoryMock);

        return $managerMock;
    }

    /**
     * Returns mock of ORM repository.
     *
     * @return Repository
     */
    protected function getOrmRepositoryMock()
    {
        $mock = $this->getMock(
            'ONGR\ElasticsearchBundle\ORM\Repository',
            ['getBundlesMapping', 'find', 'remove', 'execute'],
            [$this->getManagerMock(), ['ONGRSettingsBundle:Setting'] ]
        );

        $mock->expects(
            $this->once()
        )->method('execute')
            ->willReturn($this->getDocumentIteratorMock());

        return $mock;
    }

    /**
     * Returns mock of Document Iterator.
     *
     * @return Manager
     */
    protected function getDocumentIteratorMock()
    {
        return $this->getMock(
            'ONGR\ElasticsearchBundle\Result\DocumentIterator',
            null,
            [ null, null, [], [] ]
        );
    }

    /**
     * Returns mock of ORM Manager.
     *
     * @return Manager
     */
    protected function getManagerMock()
    {
        return $this->getMock(
            'ONGR\ElasticsearchBundle\ORM\Manager',
            ['getRepository', 'persist', 'commit', 'flush', 'refresh'],
            [ null, null, [], [] ]
        );
    }
}
