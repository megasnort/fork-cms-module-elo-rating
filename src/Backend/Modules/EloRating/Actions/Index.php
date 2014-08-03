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
 * This is the index-action (default), it will display the overview of the games
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Index extends BackendBaseActionIndex
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
            BackendEloRatingModel::QRY_GAMES,
            BL::getWorkingLanguage()
        );


        $this->dataGrid->setSortParameter('desc');
        $this->dataGrid->setSortingColumns(array('date'), 'date');

        // get the date to show not as a unix timestamp but as a readable date
        $this->dataGrid->setColumnFunction(
            array(new BackendDataGridFunctions(), 'getLongDate'),
            array('[date]'),
            'date',
            true
        );

        // show the edit button
        if (BackendAuthentication::isAllowedAction('Edit')) {

            $this->dataGrid->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('Edit') . '&amp;id=[id]',
                BL::getLabel('Edit')
            );
        }

        // show the delete button
        if (BackendAuthentication::isAllowedAction('Delete')) {

            $this->dataGrid->addColumn(
                'delete',
                null,
                BL::getLabel('Delete'),
                BackendModel::createURLForAction('Delete') . '&amp;id=[id]',
                BL::getLabel('Delete')
            );
        }
    }

    
    protected function parse()
    {
        parent::parse();
        $this->tpl->assign('dgGames', (string) $this->dataGrid->getContent());
    }
}
