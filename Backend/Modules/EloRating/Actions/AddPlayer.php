<?php

namespace Backend\Modules\EloRating\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This is the add-action, it will display a form to add a player
 *
 * @author Lester Lievens <lester.lievens@netlash.com>
 * @author Matthias Mullie <forkcms@mullie.eu>
 * @author Annelies Van Extergem <annelies.vanextergem@netlash.com>
 * @author Jelmer Snoeck <jelmer@siphoc.com>
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class AddPlayer extends BackendBaseActionAdd
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Load the form
     */
    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('add');

        // create elements
        $this->frm->addText('name');
        $this->frm->addText('start_elo', BackendEloRatingModel::DEFAULT_ELO)->setAttribute('placeholder', BackendEloRatingModel::DEFAULT_ELO);
        $this->frm->addCheckbox('active', true);
        
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('min_elo', BackendEloRatingModel::MIN_ELO);
        $this->tpl->assign('max_elo', BackendEloRatingModel::MAX_ELO);
        $this->tpl->assign('default_elo', BackendEloRatingModel::DEFAULT_ELO);

        // get url
        
        // $url = BackendModel::getURLForBlock($this->URL->getModule(), 'detail');
        // $url404 = BackendModel::getURL(404);

        // // parse additional variables
        // if ($url404 != $url) {
        //     $this->tpl->assign('detailURL', SITE_URL . $url);
        // }
    }

    /**
     * Validate the form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            // validate fields
            $this->frm->getField('name')->isFilled(BL::err('NameIsRequired'));
            
            $start_elo = (int) $this->frm->getField('start_elo')->getValue();

            if ($start_elo < BackendEloRatingModel::MIN_ELO || $start_elo > BackendEloRatingModel::MAX_ELO) {
                 $this->frm->getField('start_elo')->addError(BL::err('StartEloRange'));
            }

            if ($this->frm->isCorrect()) {

                $item['name'] = $this->frm->getField('name')->getValue();
                
                $item['start_elo'] = $start_elo;
                $item['current_elo'] = $start_elo;

                $item['active'] = $this->frm->getField('active')->getChecked() ? 'Y' : 'N';

                $item['id'] = BackendEloRatingModel::insertPlayer($item);
                
                //BackendModel::triggerEvent($this->getModule(), 'after_add', array('item' => $item));

                $this->redirect(
                    BackendModel::createURLForAction('IndexPlayers') . '&report=added&highlight=row-' . $item['id']
                );
            }
        }
    }
}
