<?php

use Phalcon\Http\Request;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Patients extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $sex;

    /**
     *
     * @var integer
     */
    public $religion;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $nik;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phalcon_project");
        $this->setSource("patients");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Patients[]|Patients|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Patients|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            [
                'name',
                'sex',
                'religion',
                'phone',
                'address',
                'nik',
            ],
            new PresenceOf(
                [
                    'message' => [
                        'name' => 'The name is required',
                        'sex' => 'The sex is required',
                        'religion' => 'The religion is required',
                        'phone' => 'The phone is required',
                        'address' => 'The address is required',
                        'nik' => 'The nik is required',
                    ],
                ]
            )
        );

        $validator->add(
            'name',
            new Regex(
                [
                    'message' => 'The name must contain only letters',
                    'pattern' => '/^[a-zA-Z ]+$/u',
                ]
            )
        );

        $validator->add(
            [
                'name',
                'phone',
                'address',
                'nik',
            ],
            new  StringLength(
                [
                    'max' => [
                        'name' => 30,
                        'phone' => 13,
                        'address' => 100,
                        'nik' => 16,
                    ],
                    'min' => [
                        'name' => 2,
                        'phone' => 9,
                        'address' => 5,
                        'nik' => 16,
                    ],
                    'messageMaximum' => [
                        'name' => 'We don\'t like really long names',
                        'phone' => 'The length of phone number can\'t greater then 13 characters,',
                        'address' => 'The length of address can\'t greater then 13 characters',
                        'nik' => 'The length of nik can\'t greater then 16 characters',
                    ],
                    'messageMinimum' => [
                        'name' => 'We want more than just their initials',
                        'phone' => 'The minimum length of phone number is 9 characters',
                        'address' => 'The minimum length of address is 5 characters',
                        'nik' => 'The minimum length of nik is 16 characters',
                    ],
                ]
            )
        );

        $validator->add(
            [
                'sex',
                'religion',
                'phone',
                'nik',
            ],
            new DigitValidator(
                [
                    'message' => [
                        'sex' => 'The sex must be numeric',
                        'religion' => 'The religion must be numeric',
                        'phone' => 'The phone must be numeric',
                        'nik' => 'The nik must be numeric',
                    ],
                ]
            )
        );

        $validator->add(
            [
                'sex',
                'religion',
            ],
            new Between(
                [
                    'minimum' => [
                        'sex' => 1,
                        'religion' => 1,
                    ],
                    'maximum' => [
                        'sex' => 2,
                        'religion' => 6,
                    ],
                    'message' => [
                        'sex' => 'The sex must be between 1 and 2',
                        'religion' => 'The religion must be between 1 and 6',
                    ]
                ]
            )
        );

        $validator->add(
            'phone',
            new UniquenessValidator(
                [
                    'model' => $this->id ? self::findFirst($this->id) : new self,
                    'message' => 'The patient phone already exist',
                ]
            )
        );

        $validator->add(
            'nik',
            new UniquenessValidator(
                [
                    'model' => $this->id ? self::findFirst($this->id) : new self,
                    'message' => 'The patient nik already exist',
                ]
            )
        );

        $method = $_SERVER['REQUEST_METHOD'];
        file_put_contents('method.txt', $method);

        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);
            return $validator->validate($_PUT);
        }

        return $validator->validate($_POST);
    }

    protected function dataHandler()
    {
        $request = new Request();
        
        $this->name = 'PUT' === $_SERVER['REQUEST_METHOD'] ? $request->getPut('name') : $request->get('name');
        $this->sex = 'PUT' === $_SERVER['REQUEST_METHOD'] ? $request->getPut('sex') : $request->get('sex');
        $this->religion = 'PUT' === $_SERVER['REQUEST_METHOD'] ? $request->getPut('religion') : $request->get('religion');
        $this->phone = 'PUT' === $_SERVER['REQUEST_METHOD'] ? $request->getPut('phone') : $request->get('phone');
        $this->address = 'PUT' === $_SERVER['REQUEST_METHOD'] ? $request->getPut('address') : $request->get('address');
        $this->nik = 'PUT' === $_SERVER['REQUEST_METHOD'] ? $request->getPut('nik') : $request->get('nik');
        
        return $this;
    }

    public function storeData()
    {
        return $this->dataHandler()->create();
    }

    public function updateDate()
    {
        return $this->dataHandler()->update();
    }
}
