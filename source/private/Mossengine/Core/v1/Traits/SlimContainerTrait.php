<?php
namespace Mossengine\Core\v1\Traits;

use Mossengine\Core\v1\Models\Account;
use SlimFacades\Container as SlimContainer;

/**
 * Trait SlimContainerTrait
 * @package Mossengine\Core\v1\Traits
 */
trait SlimContainerTrait
{
    /**
     * returns the containers account or sets the account to be the first parameter value and then returns
     *
     * @param Account|null $modelAccount
     * @return Account|null
     */
    public function slimAccount(Account $modelAccount = null) {
        return $this->slimBag('account', ($modelAccount instanceof Account ? $modelAccount : null));
    }
    public function slimAccountUnset() {
        return $this->slimBagUnset('account');
    }

    /**
     * returns the value from the containers bag at the defined key with option to set the value as it gets the value
     *
     * @param null $stringKey
     * @param null $mixedValue
     * @return null
     */
    public function slimBag($stringKey = null, $mixedValue = null) {
        if (!isset(SlimContainer::self()->bag) || !is_array(SlimContainer::self()->bag)) {
            SlimContainer::self()->bag = [];
        }

        return SlimContainer::self()->bag[$stringKey] = (!empty($mixedValue) ? $mixedValue : (isset(SlimContainer::self()->bag[$stringKey]) ? SlimContainer::self()->bag[$stringKey] : null));
    }
    public function slimBagUnset($stringKey = null) {
        if (isset(SlimContainer::self()->bag) && is_array(SlimContainer::self()->bag)) {
            unset(SlimContainer::self()->bag[$stringKey]);
        }

        return true;
    }
}