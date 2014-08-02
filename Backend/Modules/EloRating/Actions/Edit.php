<?php

namespace Backend\Modules\EloRating\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This is the add-action, it will display a form to fill in a newly played game
 *
 * @author Lester Lievens <lester.lievens@netlash.com>
 * @author Matthias Mullie <forkcms@mullie.eu>
 * @author Annelies Van Extergem <annelies.vanextergem@netlash.com>
 * @author Jelmer Snoeck <jelmer@siphoc.com>
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Edit extends BackendBaseActionEdit
{
    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        if ($this->record = BackendEloRatingModel::get($this->id)) {

            parent::execute();

            $this->loadForm();
            $this->validateForm();

            $this->parse();
            $this->display();

        } else {
            $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
        
    }

    /**
     * Load the form
     */
    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('edit');

        // set hidden values
        // $rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
        // $rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

        // get categories
        $players = BackendEloRatingModel::getActivePlayers();

        $scores = array();

        $scores['1'] = 'winner';
        $scores['0.5'] = 'draw';
        $scores['0'] = 'loser';


        // create elements
        $this->frm->addDropdown('player1', $players, $this->record["player1"])->setDefaultElement('-', null);
        $this->frm->addDropdown('player2', $players, $this->record["player2"])->setDefaultElement('-', null);
        
        $this->frm->addDate('date', $this->record["date"]);
        $this->frm->addTime('time', date('H:i', $this->record["date"]));

        $this->frm->addDropdown('score1', $scores, $this->record["score1"]);
        $this->frm->addDropdown('score2', $scores, $this->record["score2"]);

    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('item', $this->record);

        $p1Name = BackendModel::getModuleSetting('EloRating', 'p1_name', 'white');
        $p2Name = BackendModel::getModuleSetting('EloRating', 'p2_name', 'black');

        $this->tpl->assign('p1_name', $p1Name);
        $this->tpl->assign('p2_name', $p2Name);

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
            $this->frm->getField('player1')->isFilled(BL::err('PlayerIsRequired'));
            $this->frm->getField('player2')->isFilled(BL::err('PlayerIsRequired'));
            $this->frm->getField('score1')->isFilled(BL::err('Score1IsRequired'));
            $this->frm->getField('score2')->isFilled(BL::err('Score2IsRequired'));

            $this->frm->getField('date')->isValid(BL::err('DateIsRequired'));
            $this->frm->getField('time')->isValid(BL::err('TimeIsRequired'));
          
            if ($this->frm->getField('player1')->getValue() == $this->frm->getField('player2')->getValue() && $this->frm->getField('player1')->getValue() != '') {
                 $this->frm->getField('player2')->addError(BL::err('Player1VsPlayer2'));
            }

            if ($this->frm->getField('score1')->getValue() + $this->frm->getField('score2')->getValue() != 1) {
                 $this->frm->getField('score2')->addError(BL::err('InvalidScoreEntered'));
            }

            if ($this->frm->isCorrect()) {

                $item['id'] = $this->id;
                $item['player1'] = $this->frm->getField('player1')->getValue();
                $item['player2'] = $this->frm->getField('player2')->getValue();

                $item['score1'] = $this->frm->getField('score1')->getValue();
                $item['score2'] = $this->frm->getField('score2')->getValue();

                $item['user_id'] = BackendAuthentication::getUser()->getUserId();
                $item['date'] = BackendModel::getUTCDate(null, BackendModel::getUTCTimestamp($this->frm->getField('date'), $this->frm->getField('time')));

                BackendEloRatingModel::update($item);

                $this->redirect(
                    BackendModel::createURLForAction('Index') . '&report=saved&highlight=row-' . $item['id']
                );
            }
        }
    }
}
