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
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;

/**
 * This is the settings-action, it will display a set of settings to customize the Elo-ratings module
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 * @author Dave Lens <dave.lens@netlash.com>
 * @author Jelmer Snoeck <jelmer@siphoc.com>
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

        // add fields for pagination
        $this->frm->addDropdown(
            'minimum_played_games',
            array_combine(range(1, 10), range(1, 10)),
            BackendModel::getModuleSetting($this->URL->getModule(), 'minimum_played_games', 5)
        );
        $this->frm->addDropdown(
            'top_ranking_count',
            array_combine(range(2, 20), range(2, 20)),
            BackendModel::getModuleSetting($this->URL->getModule(), 'top_ranking_count', 5)
        );
        
    }

    protected function parse()
    {
        parent::parse();

    }

    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {

            // validation
            $this->frm->getField('minimum_played_games')->isFilled(BL::err('FieldIsRequired'));
            $this->frm->getField('top_ranking_count')->isFilled(BL::err('FieldIsRequired'));


            if ($this->frm->isCorrect()) {
                // set our settings
                BackendModel::setModuleSetting($this->URL->getModule(), 'minimum_played_games', (int) $this->frm->getField('minimum_played_games')->getValue());
                BackendModel::setModuleSetting($this->URL->getModule(), 'top_ranking_count', (int) $this->frm->getField('top_ranking_count')->getValue());
                
                BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

                // redirect to the settings page
                $this->redirect(BackendModel::createURLForAction('Settings') . '&report=saved');
            }
        }
    }
}
