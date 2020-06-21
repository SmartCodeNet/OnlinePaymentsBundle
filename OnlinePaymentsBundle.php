<?php

namespace GG\OnlinePaymentsBundle;

use GG\OnlinePaymentsBundle\DependencyInjection\OnlinePaymentsBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OnlinePaymentsBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new OnlinePaymentsBundleExtension();
    }
}
