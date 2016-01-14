<?php

namespace Frontend\Modules\EloRating\Widgets;


use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;
use Frontend\Core\Engine\Language as FL;

use Frontend\Core\Engine\Form as FrontendForm;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This is a widget that allows users to submit their own games
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class AddGame extends FrontendBaseWidget
{
    /**
     * @var FrontendForm
     */
    private $frm;


    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->loadForm();
    }

    private function loadForm()
    {
        $this->frm = new FrontendForm('addGameForm');

        $players = BackendEloRatingModel::getActivePlayers();

        $scores = array();

        $scores['1'] = FL::lbl('Won');
        $scores['0.5'] = FL::lbl('Draw');
        $scores['0'] = FL::lbl('Lost');

        $this->frm->addDropdown('player1', $players)->setDefaultElement('-', null);
        $this->frm->addDropdown('player2', $players)->setDefaultElement('-', null);
        $this->frm->addText('date', date('d/m/Y'));
        $this->frm->addTime('time');

        $this->frm->addDropdown('score1', $scores);
        $this->frm->addDropdown('score2', $scores)->setSelected('0');

        $this->frm->addTextarea('comment', '');
        $this->frm->addPassword('password', '');

        // parse the form
        $this->frm->parse($this->tpl);


    }
}
