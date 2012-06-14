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

import('classes.plugins.ReportPlugin');

class SSHRCReportPlugin extends ReportPlugin {
	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			$this->addLocaleData();
			HookRegistry::register('TinyMCEPlugin::getEnableFields', array(&$this, 'getTinyMCEEnabledFields'));
			return true;
		} else {
			return false;
		}
	}

	function getDisplayName() {
		return __('plugins.reports.sshrcReport.displayName');
	}

	function getDescription() {
		return __('plugins.reports.sshrcReport.description');
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
		$verbs[] = array('settings', __('plugins.reports.sshrcReport.manager.settings'));
		$verbs[] = array('report', __('manager.statistics.reports'));

		return $verbs;
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

	/**
	 * This is a convenience redirect from the 'Stats and Reports' menu option.  It just calls
	 * manage() with the necessary bits for that verb action.
 	 * @param $args array
 	 * @return boolean
	 */
	function display(&$args) {
		return $this->manage('report', $args, $message);
	}

 	/*
 	 * Execute a management verb on this plugin
 	 * @param $verb string
 	 * @param $args array
	 * @param $message string Location for the plugin to put a result msg
 	 * @return boolean
 	 */
	function manage($verb, $args, &$message) {

		$journal =& Request::getJournal();
		$templateMgr =& TemplateManager::getManager();

		switch ($verb) {

			case 'settings':

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
				$templateMgr->assign('subscriptionInfo', $journal->getSetting('subscriptionInfo'));

				// include the toggles indicating which OJS fields to include in the report.
				$templateMgr->assign('includedFields', $journal->getSetting('sshrcPluginOJSFields'));

				// subscriber information
				$institutionalSubscriptionDao =& DAORegistry::getDAO('InstitutionalSubscriptionDAO');
				$individualSubscriptionDao =& DAORegistry::getDAO('IndividualSubscriptionDAO');
				$journalStatisticsDao =& DAORegistry::getDAO('JournalStatisticsDAO');

				$registeredUsers = $journalStatisticsDao->getUserStatistics($journal->getId());

				$templateMgr->assign('numberOfReaders', isset($registeredUsers['reader']) ? $registeredUsers['reader'] : 0);

				$institutionalSubscriptions =& $institutionalSubscriptionDao->getSubscriptionsByJournalId($journal->getId());

				$validTotal = 0;
				$total = 0;
				$subscriptionStats = array(); // placeholder.

				while ($subscription =& $institutionalSubscriptions->next()) {
					$subscriptionName = $subscription->getSubscriptionTypeName();
					$typeId = $subscription->getTypeId();
					$this->_initializeSubscriptionArrayCell($subscriptionStats, $typeId, $subscriptionName);
					$endDate = $subscription->getDateEnd();
					if (strtotime($endDate) > time()) {
						$subscriptionStats[$typeId]['valid']++;
						$validTotal ++;
					} else {
						$subscriptionStats[$typeId]['invalid']++;
					}
					$total ++;
					unset($subscription);
				}

				$templateMgr->assign('institutionalSubscriptionCount', $total);
				$templateMgr->assign('validInstitutionalSubscriptionCount', $validTotal);

				$individualSubscriptions =& $individualSubscriptionDao->getSubscriptionsByJournalId($journal->getId());

				$validTotal = 0;
				$total = 0;

				while ($subscription =& $individualSubscriptions->next()) {
					$subscriptionName = $subscription->getSubscriptionTypeName();
					$typeId = $subscription->getTypeId();
					$this->_initializeSubscriptionArrayCell($subscriptionStats, $typeId, $subscriptionName);
					if ($subscription->isValid()) {
						$subscriptionStats[$typeId]['valid']++;
						$validTotal ++;
					} else {
						$subscriptionStats[$typeId]['invalid']++;
					}
					$total ++;
					unset($subscription);
				}

				$templateMgr->assign('individualSubscriptionCount', $total);
				$templateMgr->assign('validIndividualSubscriptionCount', $validTotal);
				$templateMgr->assign('subscriptionStats', $subscriptionStats);

				// grab the first two issues, and the articles published in it.
				$issueDao =& DAORegistry::getDAO('IssueDAO');
				$publishedArticleDao =& DAORegistry::getDAO('PublishedArticleDAO');

				$issues =& $issueDao->getPublishedIssues($journal->getId());
				$firstTwoIssues = array_slice($issues->toArray(), 0, 2);

				$publishedArticles = array();

				foreach ($firstTwoIssues as $issue) {
					$publishedArticles =& array_merge($publishedArticles, $publishedArticleDao->getPublishedArticles($issue->getId()));
				}

				$articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');

				$issueFiles = array();
				import('classes.file.PublicFileManager');
				$publicFileManager =& new PublicFileManager();
				$filesDir = $publicFileManager->getJournalFilesPath($journal->getId());

				// grab the paths to the files associated with the galleys in each article.
				foreach ($publishedArticles as $article) {
					$galleys =& $article->getLocalizedGalleys();
					foreach ($galleys as $galley) {
						$articleFile =& $articleFileDao->getArticleFile($galley->getFileId());
						$issueFiles[] =& str_replace($filesDir, '', $articleFile->getFilePath());
					}
				}

				// fetch the report template.
				$report = $templateMgr->fetch($this->getTemplatePath() . 'report.tpl');

				// save the report out to disk so we can include it in the archive.
				import('lib.pkp.classes.file.FileManager');
				import('classes.file.TemporaryFileManager');

				$temporaryFileManager =& new TemporaryFileManager();
				$fileManager =& new FileManager();

				$sshrcReportTempFile = tempnam(dirname($temporaryFileManager->filesDir), 'SHR');

				if (is_writeable($sshrcReportTempFile)) {
					$fp = fopen($sshrcReportTempFile, 'wb');
					fwrite($fp, $report);
					fclose($fp);
				} else {
					fatalError('misconfigured directory permissions on files directory.');
				}

				// add the report file to our issue files list.
				$realReportFile = dirname($sshrcReportTempFile) . '/SSHRCReport-' . date('Ymd') . '.html';
				$fileManager->copyFile($sshrcReportTempFile, $realReportFile);

				// Create a temporary file for the archive.
				$archiveTmpPath = tempnam(dirname($temporaryFileManager->filesDir), 'sf-');

				// create our archive and put our report in it, initially
				exec(Config::getVar('cli', 'tar') . ' -c ' .
						'-f ' . escapeshellarg($archiveTmpPath) . ' ' .
						'-C ' . escapeshellarg(dirname($realReportFile)) . ' ' .
						escapeshellarg(str_replace(dirname($realReportFile) . '/', '', $realReportFile)));

				// now, add the galley files.  Different command this time. -r to append to an existing archive.
				foreach ($issueFiles as $file) {
					exec(Config::getVar('cli', 'tar') . ' -r ' .
							'-f ' . escapeshellarg($archiveTmpPath) . ' ' .
							'-C ' . escapeshellarg(dirname($file)) . ' ' .
							escapeshellarg(str_replace(dirname($file) . '/', '', $file)));
					}

				// rename our archive file.
				$archivePath = dirname($archiveTmpPath) . '/sshrcReport.tar';
				$fileManager->copyFile($archiveTmpPath, $archivePath);

				if (file_exists($archivePath)) {
					$fileManager->downloadFile($archivePath, 'application/x-tar', false);
				} else {
					fatalError('Creating archive failed!');
				}

				// clean up
				$fileManager->deleteFile($sshrcReportTempFile);
				$fileManager->deleteFile($archivePath);
				$fileManager->deleteFile($archiveTmpPath);
				$fileManager->deleteFile($realReportFile);

				return true;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
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

		$fields = array('impact', 'researchRecord', 'editorialBoardFunc', 'subscriptionInfo');
		return false;
	}

	/**
	 * private function to initialize the statistics array for each subscription type.
	 * @param array $subscriptionStats (reference)
	 * @param int $subscriptionTypeId
	 * @param string $subscriptionName
	 */
	function _initializeSubscriptionArrayCell(&$subscriptionStats, $subscriptionTypeId, $subscriptionName) {
		if (!isset($subscriptionStats[$subscriptionTypeId]['valid'])) {
			$subscriptionStats[$subscriptionTypeId]['valid'] = 0;
		}
		if (!isset($subscriptionStats[$subscriptionTypeId]['invalid'])) {
			$subscriptionStats[$subscriptionTypeId]['invalid'] = 0;
		}

		$subscriptionStats[$subscriptionTypeId]['name'] = $subscriptionName;
	}
}
?>
