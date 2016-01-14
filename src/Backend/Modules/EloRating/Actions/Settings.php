<?php

namespace Backend\Modules\EloRating\Actions;


use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;

/**
 * This is the settings-action, it will display a set of settings to customize the Elo-ratings module
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class Settings extends BackendBaseActionEdit
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
        $this->frm = new BackendForm('settings');

        $this->frm->addDropdown(
            'minimum_played_games',
            array_combine(range(1, 10), range(1, 10)),
            BackendModel::get('fork.settings')->get($this->URL->getModule(), 'minimum_played_games', 5)
        );
        $this->frm->addDropdown(
            'top_ranking_count',
            array_combine(range(2, 20), range(2, 20)),
            BackendModel::get('fork.settings')->get($this->URL->getModule(), 'top_ranking_count', 5)
        );

        $this->frm->addDropdown(
            'top_latest_games',
            array_combine(range(2, 20), range(2, 20)),
            BackendModel::get('fork.settings')->get($this->URL->getModule(), 'top_latest_games', 5)
        );

        $this->frm->addText('password');

        $this->frm->addCheckbox(
            'immediate_recalculation',
            BackendModel::get('fork.settings')->get($this->URL->getModule(), 'immediate_recalculation', 'N') == 'Y'
        );
    }

    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {

            $this->frm->getField('minimum_played_games')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('top_ranking_count')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('top_latest_games')->isFilled(BL::err('FieldIsRequired'));

            if (strlen($this->frm->getField('password')->getValue()) > 15) {
                $this->frm->getField('password')->addError(BL::err('Password'));
            }

            if ($this->frm->isCorrect()) {

                if (strlen($this->frm->getField('password')->getValue()) > 0) {
                    BackendModel::setModuleSetting(
                        $this->URL->getModule(),
                        'password',
                        sha1(
                            $this->frm->getField('password')->getValue() . BackendEloRatingModel::SALT . strlen($this->frm->getField('password')->getValue())
                        )
                    );
                }

                BackendModel::get('fork.settings')->get(
                    $this->URL->getModule(),
                    'immediate_recalculation',
                    $this->frm->getField('immediate_recalculation')->getChecked() ? 'Y' : 'N'
                );

                BackendModel::get('fork.settings')->get(
                    $this->URL->getModule(),
                    'minimum_played_games',
                    (int) $this->frm->getField('minimum_played_games')->getValue()
                );

                BackendModel::get('fork.settings')->get(
                    $this->URL->getModule(),
                    'top_ranking_count',
                    (int) $this->frm->getField('top_ranking_count')->getValue()
                );

                BackendModel::get('fork.settings')->get(
                    $this->URL->getModule(),
                    'top_latest_games',
                    (int) $this->frm->getField('top_latest_games')->getValue()
                );

                $this->redirect(BackendModel::createURLForAction('Settings') . '&report=saved');
            }
        }
    }
}
