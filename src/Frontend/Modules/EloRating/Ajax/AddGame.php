<?php

namespace Frontend\Modules\EloRating\Ajax;

use Frontend\Core\Engine\Base\AjaxAction as FrontendBaseAJAXAction;
use Frontend\Modules\EloRating\Engine\Model as FrontendEloRatingModel;
use Frontend\Core\Engine\Model as FrontendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;


class AddGame extends FrontendBaseAJAXAction
{
    public function execute()
    {
        $player1 = (int)\SpoonFilter::getPostValue('player1', null, '', 'int');
        $player2 = (int)\SpoonFilter::getPostValue('player2', null, '', 'int');

        $player1def = false;
        $player2def = false;

        $score1 = (float)\SpoonFilter::getPostValue('score1', null, '', 'float');
        $score2 = (float)\SpoonFilter::getPostValue('score2', null, '', 'float');

        $date = (string)\SpoonFilter::getPostValue('date', null, '', 'string');
        $time = (string)\SpoonFilter::getPostValue('time', null, '', 'string');

        $comment = (string)\SpoonFilter::getPostValue('comment', null, '', 'string');

        // shorten password (sending really big password and hashing them isn't server friendly
        $password = substr((string)\SpoonFilter::getPostValue('password', null, '', 'string'), 0, 15);

        $players = BackendEloRatingModel::getActivePlayers();

        $scores = array(0, 0.5, 1);

        foreach ($players as $id => $name) {
            if ($player1 == $id) {
                $player1def = $id;
            } else if ($player2 == $id) {
                $player2def = $id;
            }
        }

        if ($player1def == false || $player2def == false) {
            $this->output(self::BAD_REQUEST, null, 'InvalidPlayers');
            return;
        }

        if (!in_array($score1, $scores) || !in_array($score2, $scores) || ($score1 + $score2 != 1)) {
            $this->output(self::BAD_REQUEST, null, 'InvalidScore');
            return;
        }

        $date = explode('/', $date);

        if (count($date) != 3) {
            $this->output(self::BAD_REQUEST, null, 'InvalidDate');
            return;
        }

        // sort year day and month manually because months and days sometimes give troubles in American notation
        $date = $date[2] . '/' . $date[1] . '/' . $date[0];
        $datetime = FrontendModel::getUTCDate(null, strtotime($date . ' ' . $time));

        if (!(preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2}/', $date) && preg_match('/[0-9]{1,2}\:[0-9]{1,2}/', $time))) {
            $this->output(self::BAD_REQUEST, null, 'InvalidDate');
            return;
        }

        if (sha1($password . BackendEloRatingModel::SALT . strlen($password)) == FrontendModel::get('fork.settings')->get('EloRating', 'password', '')) {
            $item = array(
                'player1' => $player1def,
                'player2' => $player2def,
                'score1' => $score1,
                'score2' => $score2,
                'comment' => $comment,
                'date' => $datetime
            );

            FrontendEloRatingModel::addGame($item);

            $this->output(self::OK, null, 'GameAdded');
        } else {
            $this->output(self::BAD_REQUEST, null, 'WrongPassword');
        }
    }
}
