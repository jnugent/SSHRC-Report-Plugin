{**
 * plugins/generic/sshrcReport/report.tpl
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * SSHRC Report output
 *
 *}
<h2>{translate key="plugins.generic.sshrcReport.report.sshrcReport"}</h2>

<h3>{translate key="plugins.generic.sshrcReport.report.focusScopeDesc"}</h3>

{$focusScopeDesc[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.authorGuidelines"}</h3>

{$authorGuidelines[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.reviewPolicy"}</h3>

{$reviewPolicy[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.reviewGuidelines"}</h3>

{$reviewGuidelines[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.pubFreqPolicy"}</h3>

{$pubFreqPolicy[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.impact"}</h3>

{$impact[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.researchRecord"}</h3>

{$researchRecord[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.editorialBoardFunc"}</h3>

{$editorialBoardFunc[$currentLocale]}

<h3>{translate key="plugins.generic.sshrcReport.report.subscribers}</h3>

{translate key="plugins.generic.sshrcReport.report.subscriberCountString" numberOfReaders=$numberOfReaders}
<p>{translate key="plugins.generic.sshrcReport.report.subscriptionBreakdown"}</p>
<ul>
	{foreach from=$subscriptionStats item=stat}
		<li>{$stat.name} => {$stat.count}</li>
	{/foreach}
</ul>
