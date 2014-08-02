<?php

namespace Backend\Modules\EloRating\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

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
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();
        $this->loadDataGrid();
        $this->parse();
        $this->display();

    }

    /**
     * Load the datagrids
     */
    private function loadDataGrid()
    {
        $this->dataGrid = new BackendDataGridDB(
            BackendEloRatingModel::QRY_GAMES,
            BL::getWorkingLanguage()
        );


        $this->dataGrid->setSortParameter('desc'); 
        $this->dataGrid->setSortingColumns(array('date'), 'date');

        // set column functions
        $this->dataGrid->setColumnFunction(
            array(new BackendDataGridFunctions(), 'getLongDate'),
            array('[date]'),
            'date',
            true
        );

        if (BackendAuthentication::isAllowedAction('Edit')) {

            $this->dataGrid->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('Edit') . '&amp;id=[id]',
                BL::getLabel('Edit')
            );
        }

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

    /**
     * Parse the datagrid 
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('dgGames', (string) $this->dataGrid->getContent());
    }

}
