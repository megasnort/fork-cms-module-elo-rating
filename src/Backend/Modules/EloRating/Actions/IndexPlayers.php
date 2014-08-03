<?php

namespace Backend\Modules\EloRating\Actions;


use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\DataGridDB as BackendDataGridDB;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This action it will display the overview of the players
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class IndexPlayers extends BackendBaseActionIndex
{

    public function execute()
    {
        parent::execute();
        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }


    private function loadDataGrid()
    {
        $this->dataGrid = new BackendDataGridDB(
            BackendEloRatingModel::QRY_PLAYERS,
            BL::getWorkingLanguage()
        );

        $this->dataGrid->setSortParameter('desc');
        $this->dataGrid->setSortingColumns(array('name','current_elo','games_played','won','lost','draws'), 'current_elo');

 
        $this->dataGrid->setHeaderLabels(array(
            'current_elo' => BL::getLabel('EloRating')
        ));
 
      
        if (BackendAuthentication::isAllowedAction('EditPlayer')) {

            $this->dataGrid->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('EditPlayer') . '&amp;id=[id]',
                BL::getLabel('Edit')
            );
        }
    }


    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('dgPlayers', (string) $this->dataGrid->getContent());
    }
}
