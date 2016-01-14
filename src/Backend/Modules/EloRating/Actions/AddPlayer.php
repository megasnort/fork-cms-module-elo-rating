<?php

namespace Backend\Modules\EloRating\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;

/**
 * This is the add-action, it will display a form to add a player
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class AddPlayer extends BackendBaseActionAdd
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

        $this->frm->addText('name');
        $this->frm->addText('start_elo', BackendEloRatingModel::DEFAULT_ELO)->setAttribute('placeholder', BackendEloRatingModel::DEFAULT_ELO);
        $this->frm->addCheckbox('active', true);
    }

    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('min_elo', BackendEloRatingModel::MIN_ELO);
        $this->tpl->assign('max_elo', BackendEloRatingModel::MAX_ELO);
        $this->tpl->assign('default_elo', BackendEloRatingModel::DEFAULT_ELO);
    }

    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            $this->frm->getField('name')->isFilled(BL::err('NameIsRequired'));

            $start_elo = (int)$this->frm->getField('start_elo')->getValue();

            if ($start_elo < BackendEloRatingModel::MIN_ELO || $start_elo > BackendEloRatingModel::MAX_ELO) {
                $this->frm->getField('start_elo')->addError(BL::err('StartEloRange'));
            }

            if ($this->frm->isCorrect()) {

                $item['name'] = $this->frm->getField('name')->getValue();
                $item['start_elo'] = $start_elo;
                $item['current_elo'] = $start_elo;
                $item['active'] = $this->frm->getField('active')->getChecked() ? 'Y' : 'N';
                $item['id'] = BackendEloRatingModel::insertPlayer($item);

                if ($item['active'] == 'Y') {
                    $languages = BL::getActiveLanguages();

                    foreach ($languages as $lang) {
                        BackendSearchModel::saveIndex(
                            $this->getModule(),
                            $item['id'],
                            array('title' => $item['name'], 'text' => $item['name']),
                            $lang
                        );
                    }
                }

                $this->redirect(
                    BackendModel::createURLForAction('IndexPlayers') . '&report=added&highlight=row-' . $item['id']
                );
            }
        }
    }
}
