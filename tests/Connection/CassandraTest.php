<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2018 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Adapter\Cassandra\Tests\Connection;

use Fusio\Adapter\Cassandra\Connection\Cassandra;
use Fusio\Engine\Form\Builder;
use Fusio\Engine\Form\Container;
use Fusio\Engine\Form\Element\Input;
use Fusio\Engine\Form\Element\Tag;
use Fusio\Engine\Parameters;
use Fusio\Engine\Test\EngineTestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * CassandraTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class CassandraTest extends TestCase
{
    use EngineTestCaseTrait;

    public function testGetConnection()
    {
        /** @var Cassandra $connectionFactory */
        $connectionFactory = $this->getConnectionFactory()->factory(Cassandra::class);

        $config = new Parameters([
            'host'     => ['127.0.0.1'],
            'port'     => '9042',
            'keyspace' => '',
        ]);

        $connection = $connectionFactory->getConnection($config);

        $this->assertInstanceOf(\Cassandra\Session::class, $connection);
    }

    public function testConfigure()
    {
        $connection = $this->getConnectionFactory()->factory(Cassandra::class);
        $builder    = new Builder();
        $factory    = $this->getFormElementFactory();

        $connection->configure($builder, $factory);

        $this->assertInstanceOf(Container::class, $builder->getForm());

        $elements = $builder->getForm()->getProperty('element');
        $this->assertEquals(3, count($elements));
        $this->assertInstanceOf(Tag::class, $elements[0]);
        $this->assertInstanceOf(Input::class, $elements[1]);
        $this->assertInstanceOf(Input::class, $elements[2]);
    }

    public function testPing()
    {
        /** @var Cassandra $connectionFactory */
        $connectionFactory = $this->getConnectionFactory()->factory(Cassandra::class);

        $config = new Parameters([
            'host'     => ['127.0.0.1'],
            'port'     => '9042',
            'keyspace' => '',
        ]);

        $connection = $connectionFactory->getConnection($config);

        $this->assertTrue($connectionFactory->ping($connection));
    }
}
