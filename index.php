<?php

/**
 * @defgroup plugins_reports_sshrcReport
 */

/**
 * @file plugins/reports/sshrcReport/index.php
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_reports_sshrcReport
 * @brief Wrapper for SSHRC Report plugin.
 *
 */

require_once('SSHRCReportPlugin.inc.php');

return new SSHRCReportPlugin();

?>