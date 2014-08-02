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
 * This action will delete a player
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class DeletePlayer extends BackendBaseActionDelete
{
    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        if ($this->record = BackendEloRatingModel::getPlayer($this->id)) {
           
            parent::execute();
            
            // delete item
            BackendEloRatingModel::deletePlayer($this->id);


            $this->redirect(
                BackendModel::createURLForAction('IndexPlayers') . '&report=deleted'
            );
        } else {
            $this->redirect(
                BackendModel::createURLForAction('IndexPlayers') . '&error=non-existing'
            );
        }
    }
}
