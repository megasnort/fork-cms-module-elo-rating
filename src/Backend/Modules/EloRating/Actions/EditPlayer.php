<?php

namespace Backend\Modules\EloRating\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\EloRating\Engine\Model as BackendEloRatingModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;


/**
 * This is the Edit-action, it will display a form to add a player
 *
 * @author Stef Bastiaansen <stef@megasnort.com>
 */
class EditPlayer extends BackendBaseActionAdd
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var array
     */
    private $record;

    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        if ($this->record = BackendEloRatingModel::getPlayer($this->id)) {
            parent::execute();
            $this->loadForm();
            $this->validateForm();
            $this->parse();
            $this->display();
        } else {
            $this->redirect(BackendModel::createURLForAction('IndexPlayers') . '&error=non-existing');
        }
    }

    private function loadForm()
    {
        $this->frm = new BackendForm('edit');

        $this->frm->addText('name', $this->record["name"]);
        $this->frm->addText('start_elo', $this->record["start_elo"])->setAttribute('placeholder', BackendEloRatingModel::DEFAULT_ELO);
        $this->frm->addCheckbox('active', $this->record["active"] == 'Y');
    }

    protected function parse()
    {
        parent::parse();
        $this->tpl->assign('min_elo', BackendEloRatingModel::MIN_ELO);
        $this->tpl->assign('max_elo', BackendEloRatingModel::MAX_ELO);
        $this->tpl->assign('default_elo', BackendEloRatingModel::DEFAULT_ELO);
        $this->tpl->assign('item', $this->record);
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
                $item['id'] = $this->id;
                $item['name'] = $this->frm->getField('name')->getValue();
                $item['meta_id'] = $this->record["meta_id"];
                $item['start_elo'] = $start_elo;
                $item['current_elo'] = $start_elo;
                $item['active'] = $this->frm->getField('active')->getChecked() ? 'Y' : 'N';

                if ($item['active'] == 'Y') {
                    $languages = BL::getActiveLanguages();

                    foreach ($languages as $lang) {
                        BackendSearchModel::saveIndex($this->getModule(), $item['id'], array('title' => $item['name'], 'text' => $item['name']), $lang);
                    }
                }

                BackendEloRatingModel::updatePlayer($item);

                $this->redirect(
                    BackendModel::createURLForAction('IndexPlayers') . '&report=saved&highlight=row-' . $item['id']
                );
            }
        }
    }
}
