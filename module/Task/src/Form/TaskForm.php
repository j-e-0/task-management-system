<?php

namespace Task\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class TaskForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('task');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
        $this->add([
            'name' => 'description',
            'type' => 'text',
            'options' => [
                'label' => 'Description',
            ],
        ]);
        $this->add([
            'name' => 'status',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    '1' => 'Pending',
                    '2' => 'Completed',
                ],
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}