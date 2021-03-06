<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Customer;

class Info extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $guid;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
    }
}
