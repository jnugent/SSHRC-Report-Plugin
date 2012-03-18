<?php

/**
 * @file SSHRCReportSettingsForm.inc.php
 *
 * Copyright (c) 2003-2010 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SSHRCReportSettingsForm
 * @ingroup plugins_generic_sshrcReport
 *
 * @brief Form for journal managers generate SSHRC Reports
 */

// $Id$


import('lib.pkp.classes.form.Form');

class SSHRCReportSettingsForm extends Form {

	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $journalId int
	 */
	function SSHRCReportSettingsForm(&$plugin, $journalId) {
		$this->journalId =& $journalId;
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
	}

	/**
	 * Initialize form data.
	 */
	function initData() {

		$plugin =& $this->plugin;
		$journal =& Request::getJournal();

		$this->_data = array(
			'impact' => $journal->getSetting('impact'),
			'researchRecord' => $journal->getSetting('researchRecord'),
			'editorialBoardFunc' => $journal->getSetting('editorialBoardFunc'),
		);
	}


	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('impact', 'researchRecord', 'editorialBoardFunc'));
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin =& $this->plugin;
		$journal =& Request::getJournal();

		$journal->updateSetting('impact', $this->getData('impact'), 'string', true);
		$journal->updateSetting('researchRecord', $this->getData('researchRecord'), 'string', true);
		$journal->updateSetting('editorialBoardFunc', $this->getData('editorialBoardFunc'), 'string', true);

		parent::execute();
	}
}

?>
