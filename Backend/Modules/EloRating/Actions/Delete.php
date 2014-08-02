<?php

namespace Backend\Modules\EloRating\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This action will delete a game
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Delete extends BackendBaseActionDelete
{
    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        if ($this->record = BackendEloRatingModel::get($this->id)) {
           
            parent::execute();
            
            // delete item
            BackendEloRatingModel::delete($this->id);

            /*
            BackendModel::triggerEvent(
                $this->getModule(),
                'after_delete',
                array('item' => $this->record)
            );
            */

            $this->redirect(
                BackendModel::createURLForAction('Index') . '&report=deleted'
            );
        } else {
            $this->redirect(
                BackendModel::createURLForAction('Index') . '&error=non-existing'
            );
        }
    }
}
