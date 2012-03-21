<?php

/**
 * @file SSHRCReportPlugin.inc.php
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SSHRCReportPlugin
 * @ingroup plugins_generic_sshrcReport
 *
 * @brief Generates a SSHRC Report for a journal
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class SSHRCReportPlugin extends GenericPlugin {
	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			$this->addLocaleData();
			HookRegistry::register ('Templates::Admin::Index::AdminFunctions', array(&$this, 'displayMenuOption'));
			HookRegistry::register ('Templates::Manager::Index::ManagementPages', array(&$this, 'displayMenuOption'));
			HookRegistry::register('TinyMCEPlugin::getEnableFields', array(&$this, 'getTinyMCEEnabledFields'));
			return true;
		} else {
			return false;
		}
	}

	function getDisplayName() {
		return __('plugins.generic.sshrcReport.displayName');
	}

	function getDescription() {
		return __('plugins.generic.sshrcReport.description');
	}

	function getName() {
		return 'SSHRCReportPlugin';
	}

	/**
	 * Set the page's breadcrumbs, given the plugin's tree of items
	 * to append.
	 * @param $subclass boolean
	 */
	function setBreadcrumbs($isSubclass = false) {
		$templateMgr =& TemplateManager::getManager();
		$pageCrumbs = array(
			array(
				Request::url(null, 'user'),
				'navigation.user'
			),
			array(
				Request::url(null, 'manager'),
				'user.role.manager'
			)
		);
		if ($isSubclass) $pageCrumbs[] = array(
			Request::url(null, 'manager', 'plugins'),
			'manager.plugins'
		);

		$templateMgr->assign('pageHierarchy', $pageCrumbs);
	}

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('plugins.generic.sshrcReport.manager.settings'));
			$verbs[] = array('report', __('plugins.generic.sshrcReport.manager.report'));
		}
		return parent::getManagementVerbs($verbs);
	}

	/**
	 * build the report
	 */
	function buildSSHRCReport($hookName, $params) {

		$templateMgr =& $params[0];
		$template =& $params[1];
		$journal =& Request::getJournal();

		if (!empty($journal)) {

			$article = $templateMgr->get_template_vars('article');
			$galley = $templateMgr->get_template_vars('galley');
		}
		return false;
	}

 	/*
 	 * Execute a management verb on this plugin
 	 * @param $verb string
 	 * @param $args array
	 * @param $message string Location for the plugin to put a result msg
	 * @param $messageParams array extra information for the management verb.
 	 * @return boolean
 	 */
	function manage($verb, $args, &$message, &$messageParams) {
		if (!parent::manage($verb, $args, $message, $messageParams)) return false;

		$journal =& Request::getJournal();
		$templateMgr =& TemplateManager::getManager();

		switch ($verb) {

			case 'settings':
				$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));

				$this->import('SSHRCReportSettingsForm');
				$form = new SSHRCReportSettingsForm($this, $journal->getId());
				if (Request::getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						Request::redirect(null, 'manager', 'plugin');
						return false;
					} else {
						$this->setBreadCrumbs(true);
						$form->display();
					}
				} else {
					$this->setBreadCrumbs(true);
					$form->initData();
					$form->display();
				}
				return true;
			case 'report':

				// get the journal settings that are available for this report.
				// mission or mandate
				$templateMgr->assign('focusScopeDesc', $journal->getSetting('focusScopeDesc'));

				// process for conducting peer review and selecting articles
				$templateMgr->assign('authorGuidelines', $journal->getSetting('authorGuidelines'));
				$templateMgr->assign('reviewPolicy', $journal->getSetting('reviewPolicy'));
				$templateMgr->assign('reviewGuidelines', $journal->getSetting('reviewGuidelines')); // optional?

				// publication plan
				$templateMgr->assign('pubFreqPolicy', $journal->getSetting('pubFreqPolicy'));

				// retrieve the settings that are specific to this plugin (but stored in the journal)
				$templateMgr->assign('impact', $journal->getSetting('impact'));
				$templateMgr->assign('researchRecord', $journal->getSetting('researchRecord'));
				$templateMgr->assign('editorialBoardFunc', $journal->getSetting('editorialBoardFunc'));

				// subscriber information
				$institutionalSubscriptionDao =& DAORegistry::getDAO('InstitutionalSubscriptionDAO');
				$individualSubscriptionDao =& DAORegistry::getDAO('IndividualSubscriptionDAO');
				$journalStatisticsDao =& DAORegistry::getDAO('JournalStatisticsDAO');

				$subscriptionStats = $journalStatisticsDao->getSubscriptionStatistics($journal->getId());
				$registeredUsers = $journalStatisticsDao->getUserStatistics($journal->getId());

				$templateMgr->assign('numberOfReaders', $registeredUsers['reader']);
				$templateMgr->assign('subscriptionStats', $subscriptionStats);

				// grab the current issue, and the articles published in it.
				$issueDao =& DAORegistry::getDAO('IssueDAO');
				$issue =& $issueDao->getCurrentIssue($journal->getId(), true);

				$publishedArticleDao =& DAORegistry::getDAO('PublishedArticleDAO');
				$publishedArticles =& $publishedArticleDao->getPublishedArticles($issue->getId());

				$articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');

				$issueFiles = array();
				import('classes.file.PublicFileManager');
				$publicFileManager = new PublicFileManager();
				$filesDir = $publicFileManager->getJournalFilesPath($journal->getId());

				// grab the paths to the files associated with the galleys in each article.
				foreach ($publishedArticles as $article) {
					$galleys =& $article->getLocalizedGalleys();
					foreach ($galleys as $galley) {
						$articleFile =& $articleFileDao->getArticleFile($galley->getFileId());
						$issueFiles[] =& str_replace($filesDir, '', $articleFile->getFilePath());
					}
				}

				$report = $templateMgr->fetch($this->getTemplatePath() . 'report.tpl');

				// save the report out to disk so we can include it in the archive.
				import('lib.pkp.classes.file.FileManager');
				import('classes.file.TemporaryFileManager');

				$temporaryFileManager = new TemporaryFileManager();
				$fileManager = new FileManager();

				$sshrcReportTempFile = tempnam($temporaryFileManager->getBasePath(), 'SHR');
				if (is_writeable($sshrcReportTempFile)) {
					$fp = fopen($sshrcReportTempFile, 'wb');
					fwrite($fp, $report);
					fclose($fp);
				} else {
					fatalError('misconfigured directory permissions on: ' . $temporaryFileManager->getBasePath());
				}

				// add the report file to our issue files list.
				$realReportFile = $temporaryFileManager->getBasePath() . 'SSHRCReport-' . date('Ymd') . '.html';
				$fileManager->copyFile($sshrcReportTempFile, $realReportFile);

				// Create a temporary file.
				$archivePath = tempnam($temporaryFileManager->getBasePath(), 'sf-');

				// assemble our archive.  First, put the galley files inside.
				exec(Config::getVar('cli', 'tar') . ' -c ' .
					'-f ' . escapeshellarg($archivePath) . ' ' .
					'-C ' . escapeshellarg($filesDir) . ' ' .
					implode(' ', array_map('escapeshellarg', $issueFiles))
				);

				// now, add the report.  Different command, since it is in a different directory
				// this command also ultimately compresses the final archive.
				exec(Config::getVar('cli', 'tar') . ' -r ' .
						'-f ' . escapeshellarg($archivePath) . ' ' .
						'-C ' . escapeshellarg($temporaryFileManager->getBasePath()) . ' ' .
						escapeshellarg(str_replace($temporaryFileManager->getBasePath(), '', $realReportFile))
				);

				if (file_exists($archivePath)) {
					$fileManager->downloadFile($archivePath, 'application/x-tar', false, 'sshrcReport.tar');
					$fileManager->deleteFile($archivePath);
				} else {
					fatalError('Creating archive failed!');
				}

				// clean up
				$fileManager->deleteFile($sshrcReportTempFile);
				$fileManager->deleteFile($archivePath);
				$fileManager->deleteFile($realReportFile);

				return true;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
	}

	function displayMenuOption($hookName, $args) {

		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];

		$journal =& Request::getJournal();
		if (isset($journal)) {
			$output .= '<li>&#187; <a href="' . Request::url(null, 'manager', 'plugin', array('generic', $this->getName(), 'report')) . '">' .
				__('plugins.generic.sshrcReport.manager.report') . '</a></li>';
		}
		return false;
	}

	/**
	 * Hook into TinyMCE for the text areas on the settings form.
	 * @param String $hookName
	 * @param array $args
	 * @return boolean
	 */
	function getTinyMCEEnabledFields($hookName, $args) {

		$tinyMCEPlugin =& $args[0];
		$fields =& $args[1];

		$fields = array('impact', 'researchRecord', 'editorialBoardFunc');
		return false;
	}
}
?>
