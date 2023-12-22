<?php

declare(strict_types=1);

namespace Task\Controller;

use Task\Model\TaskTable;
use Task\Form\TaskForm;
use Task\Model\Task;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $table;

    public function __construct(TaskTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel(
            [
                'tasks' => $this->table->fetchAll(),
            ]
        );
    }

    public function addAction()
    {
        $form = new TaskForm();
        $form->get('submit')->setValue('Add');

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $task = new Task();
        $form->setInputFilter($task->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $task->exchangeArray($form->getData());
        $this->table->saveTask($task);
        return $this->redirect()->toRoute('task');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(0 === $id){
            return $this->redirect()->toRoute('task', ['action' => 'add']);
        }

        try {
            $task = $this->table->getTask($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('task', ['action' => 'index']);
        }

        $form = new TaskForm();
        $form->bind($task);
        $form->get('submit')->setAttribute('value', 'Edit');

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($task->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveTask($task);
        } catch (\Exception $e) {
        }

        // Redirect to task list
        return $this->redirect()->toRoute('task', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('task');
        }

        /** @var \Laminas\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteTask($id);
            }

            // Redirect to list of tasks
            return $this->redirect()->toRoute('task');
        }

        return [
            'id'    => $id,
            'task' => $this->table->getTask($id),
        ];
    }
}
