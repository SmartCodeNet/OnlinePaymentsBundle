<?php

namespace GG\OnlinePaymentsBundle\BlueMedia\ValueObject;

use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\StaticHydrator;
use GG\OnlinePaymentsBundle\BlueMedia\Hydrator\ValueObject;

class CustomerData implements ValueObjectInterface
{
    /**
     * @var StringValue|null
     */
    protected $fName;

    /**
     * @var StringValue|null
     */
    protected $lName;

    /**
     * @var StringValue|null
     */
    protected $streetName;

    /**
     * @var StringValue|null
     */
    protected $streetHouseNo;

    /**
     * @var StringValue|null
     */
    protected $streetStaircaseNo;

    /**
     * @var StringValue|null
     */
    protected $streetPremiseNo;

    /**
     * @var StringValue|null
     */
    protected $postalCode;

    /**
     * @var StringValue|null
     */
    protected $city;

    /**
     * @var StringValue|null
     */
    protected $nrb;

    /**
     * @var StringValue|null
     */
    protected $senderData;

    public function __construct(
        StringValue $fName = null,
        StringValue $lName = null,
        StringValue $streetName = null,
        StringValue $streetHouseNo = null,
        StringValue $streetStaircaseNo = null,
        StringValue $streetPremiseNo = null,
        StringValue $postalCode = null,
        StringValue $city = null,
        StringValue $nrb = null,
        StringValue $senderData = null
    ) {
        $this->fName = $fName;
        $this->lName = $lName;
        $this->streetName = $streetName;
        $this->streetHouseNo = $streetHouseNo;
        $this->streetStaircaseNo = $streetStaircaseNo;
        $this->streetPremiseNo = $streetPremiseNo;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->nrb = $nrb;
        $this->senderData = $senderData;
    }

    public static function fromNative(): void
    {
        throw new \BadMethodCallException("Not implemented");
    }

    public function toNative()
    {
        return StaticHydrator::extract(ValueObject::class, $this);
    }

    public function __toString()
    {
        return "";
    }
}
