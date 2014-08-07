<?php

namespace Frontend\Modules\EloRating\Widgets;


use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;

use Frontend\Core\Engine\Form as FrontendForm;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This is a widget that allows users to submit their own games
 *
 * @author Stef Bastiaansen <stef@megasnort.be>
 */
class AddGame extends FrontendBaseWidget
{

    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->loadForm();
        $this->parse();
    }


    private function loadForm()
    {
        // create form
        $this->frm = new FrontendForm('addGameForm');

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

        $this->frm->addTextarea('comment', '');
        $this->frm->addPassword('password', '');

        // parse the form
        $this->frm->parse($this->tpl);


    }

    private function parse()
    {
        
       
    }
}
