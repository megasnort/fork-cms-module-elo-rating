<?php

namespace Backend\Modules\EloRating\Actions;

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
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        if ($this->record = BackendEloRatingModel::get($this->id)) {
            parent::execute();
            BackendEloRatingModel::delete($this->id);
            $this->redirect(BackendModel::createURLForAction('Index') . '&report=deleted');
        } else {
            $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
    }
}
