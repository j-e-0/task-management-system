<?php

namespace Task\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class TaskTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getTask($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveTask(Task $task)
    {
        $data = [
            'title'        => $task->title,
            'description'  => $task->description,
            'status'       => $task->status,
        ];

        $id = (int) $task->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getTask($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update Task with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteTask($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}