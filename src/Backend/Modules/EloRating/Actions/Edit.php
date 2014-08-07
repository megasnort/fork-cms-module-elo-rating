<?php

namespace Backend\Modules\EloRating\Actions;


use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
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
class Edit extends BackendBaseActionEdit
{

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


    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('edit');

        // set hidden values
        // $rbtHiddenValues[] = array('label' => BL::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
        // $rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

        // get categories
        $players = BackendEloRatingModel::getActivePlayers((int) $this->record["player1"], (int) $this->record["player2"]);

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

        $this->frm->addTextarea('comment', $this->record["comment"]);

        $this->frm->addCheckbox('active', $this->record["active"] == 'Y');

    }

    
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('item', $this->record);
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
            if ($this->frm->getField('player1')->getValue() == $this->frm->getField('player2')->getValue()  && $this->frm->getField('player1')->getValue() != '') {
                 $this->frm->getField('player2')->addError(BL::err('Player1VsPlayer2'));
            }

            // The total submitted score should always be 1 (0+1, 0.5+0.5, 1+0)
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

                $item['active'] = $this->frm->getField('active')->getChecked() ? 'Y' : 'N';
                $item['comment'] = $this->frm->getField('comment')->getValue();

                BackendEloRatingModel::update($item);

                $this->redirect(
                    BackendModel::createURLForAction('Index') . '&report=saved&highlight=row-' . $item['id']
                );
            }
        }
    }
}
