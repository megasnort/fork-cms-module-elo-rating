<?php

namespace Backend\Modules\EloRating;


use Backend\Core\Engine\Base\Config as BackendBaseConfig;

/**
 * This is the configuration-object for the Elo-rating module
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Config extends BackendBaseConfig
{
    /**
     * The default action
     *
     * @var    string
     */
    protected $defaultAction = 'Index';

    /**
     * The disabled actions
     *
     * @var    array
     */
    protected $disabledActions = array();
}
