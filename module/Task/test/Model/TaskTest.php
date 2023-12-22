<?php
namespace TaskTest\Model;

use Task\Model\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testInitialTaskValuesAreNull()
    {
        $task = new Task();

        $this->assertNull($task->id, '"id" should be null by default');
        $this->assertNull($task->title, '"title" should be null by default');
        $this->assertNull($task->description, '"description" should be null by default');
        $this->assertNull($task->status, '"status" should be null by default');
        $this->assertNull($task->created_at, '"created_at" should be null by default');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $task = new Task();
        $data  = [
            'id'          => 123,
            'title'       => 'some title',
            'description' => 'some description',
            'status'      => 1,
            'created_at' => '2000-01-01 00:00:00',
        ];

        $task->exchangeArray($data);

        $this->assertSame(
            $data['id'],
            $task->id,
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['title'],
            $task->title,
            '"title" was not set correctly'
        );

        $this->assertSame(
            $data['description'],
            $task->description,
            '"description" was not set correctly'
        );

        $this->assertSame(
            $data['status'],
            $task->status,
            '"status" was not set correctly'
        );

        $this->assertSame(
            $data['created_at'],
            $task->created_at,
            '"created_at" was not set correctly'
        );

    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $task = new Task();

        $task->exchangeArray([
            'id'          => 123,
            'title'       => 'some title',
            'description' => 'some description',
            'status'      => 1,
            'created_at' => '2000-01-01 00:00:00',
        ]);
        $task->exchangeArray([]);

        $this->assertNull($task->id, '"id" should default to null');
        $this->assertNull($task->title, '"title" should default to null');
        $this->assertNull($task->description, '"description" should default to null');
        $this->assertNull($task->status, '"id" should default to null');
        $this->assertNull($task->created_at, '"created_at" should default to null');
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $task = new Task();
        $data  = [
            'id'          => 123,
            'title'       => 'some title',
            'description' => 'some description',
            'status'      => 1,
            'created_at' => '2000-01-01 00:00:00',
        ];

        $task->exchangeArray($data);
        $copyArray = $task->getArrayCopy();

        $this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
        $this->assertSame($data['title'], $copyArray['title'], '"title" was not set correctly');
        $this->assertSame($data['description'], $copyArray['description'], '"description" was not set correctly');
        $this->assertSame($data['status'], $copyArray['status'], '"status" was not set correctly');
        $this->assertSame($data['created_at'], $copyArray['created_at'], '"created_at" was not set correctly');
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $task = new Task();

        $inputFilter = $task->getInputFilter();

        $this->assertSame(5, $inputFilter->count());
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('title'));
        $this->assertTrue($inputFilter->has('description'));
        $this->assertTrue($inputFilter->has('status'));
        $this->assertTrue($inputFilter->has('created_at'));
    }
}