<?php

namespace Task\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\DateTimeFormatter;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;
use Laminas\Validator\InArray;

class Task
{
    public $id;
    public $title;
    public $description;
    public $created_at;
    public $status;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id           = !empty($data['id']) ? $data['id'] : null;
        $this->title        = !empty($data['title']) ? $data['title'] : null;
        $this->description  = !empty($data['description']) ? $data['description'] : null;
        $this->created_at   = !empty($data['created_at']) ? $data['created_at'] : null;
        $this->status       = !empty($data['status']) ? $data['status'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'created_at'   => $this->created_at,
            'status'       => $this->status,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'description',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'status',
            'required' => true,
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => [1,2]
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'created_at',
            'required' => false,
            'filters' => [
                ['name' => DateTimeFormatter::class],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}