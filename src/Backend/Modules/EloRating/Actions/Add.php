<?php

namespace Backend\Modules\EloRating\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This is the add-action, it will display a form to fill in a newly played game
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Add extends BackendBaseActionAdd
{
    public function execute()
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }


    private function loadForm()
    {

        $this->frm = new BackendForm('add');

        $players = BackendEloRatingModel::getActivePlayers();

        $scores = array();

        $scores['1'] = 'winner';
        $scores['0.5'] = 'draw';
        $scores['0'] = 'loser';


        $this->frm->addDropdown('player1', $players)->setDefaultElement('-', null);
        $this->frm->addDropdown('player2', $players)->setDefaultElement('-', null);
        $this->frm->addDate('date');
        $this->frm->addTime('time');

        $this->frm->addDropdown('score1', $scores);
        $this->frm->addDropdown('score2', $scores)->setSelected('0');

    }


    protected function parse()
    {
        parent::parse();

        $p1Name = BackendModel::getModuleSetting('EloRating', 'p1_name', 'white');
        $p2Name = BackendModel::getModuleSetting('EloRating', 'p2_name', 'black');

        $this->tpl->assign('p1_name', $p1Name);
        $this->tpl->assign('p2_name', $p2Name);

    }


    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();


            $this->frm->getField('player1')->isFilled(BL::err('PlayerIsRequired'));
            $this->frm->getField('player2')->isFilled(BL::err('PlayerIsRequired'));
            $this->frm->getField('score1')->isFilled(BL::err('Score1IsRequired'));
            $this->frm->getField('score2')->isFilled(BL::err('Score2IsRequired'));

            $this->frm->getField('date')->isValid(BL::err('DateIsRequired'));
            $this->frm->getField('time')->isValid(BL::err('TimeIsRequired'));
          
            // Both players should be different. The error should only be displayed when a value is actually submitted (otherwise: two errors)
            if ($this->frm->getField('player1')->getValue() == $this->frm->getField('player2')->getValue() && $this->frm->getField('player1')->getValue() != '') {
                 $this->frm->getField('player2')->addError(BL::err('Player1VsPlayer2'));
            }
    
            // The total submitted score should always be 1 (0+1, 0.5+0.5, 1+0)
            if ($this->frm->getField('score1')->getValue() + $this->frm->getField('score2')->getValue() != 1) {
                 $this->frm->getField('score2')->addError(BL::err('InvalidScoreEntered'));
            }

            if ($this->frm->isCorrect()) {

                
                $item['player1'] = $this->frm->getField('player1')->getValue();
                $item['player2'] = $this->frm->getField('player2')->getValue();

                $item['score1'] = $this->frm->getField('score1')->getValue();
                $item['score2'] = $this->frm->getField('score2')->getValue();

                $item['user_id'] = BackendAuthentication::getUser()->getUserId();
                $item['date'] = BackendModel::getUTCDate(null, BackendModel::getUTCTimestamp($this->frm->getField('date'), $this->frm->getField('time')));

                $item['id'] = BackendEloRatingModel::insert($item);
                

                //Save meta. Do it without the form.
                

                

                $this->redirect(
                    BackendModel::createURLForAction('Index') . '&report=added&highlight=row-' . $item['id']
                );
            }
        }
    }
}
