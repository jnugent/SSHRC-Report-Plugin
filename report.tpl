{**
 * plugins/generic/sshrcReport/report.tpl
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * SSHRC Report output
 *
 *}
<h2>{translate key="plugins.reports.sshrcReport.reports.sshrcReport"}</h2>

<h3>{translate key="plugins.reports.sshrcReport.reports.focusScopeDesc"}</h3>

{$focusScopeDesc[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.authorGuidelines"}</h3>

{$authorGuidelines[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.reviewPolicy"}</h3>

{$reviewPolicy[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.reviewGuidelines"}</h3>

{$reviewGuidelines[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.pubFreqPolicy"}</h3>

{$pubFreqPolicy[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.impact"}</h3>

{$impact[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.researchRecord"}</h3>

{$researchRecord[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.editorialBoardFunc"}</h3>

{$editorialBoardFunc[$currentLocale]}

<h3>{translate key="plugins.reports.sshrcReport.reports.subscribers}</h3>

<p>
{translate key="plugins.reports.sshrcReport.reports.subscriberCountString" numberOfReaders=$numberOfReaders}<br />
{translate key="plugins.reports.sshrcReport.reports.institutionCountString" institutionalSubscriptionCount=$institutionalSubscriptionCount}<br />
{translate key="plugins.reports.sshrcReport.reports.individualCountString" individualSubscriptionCount=$individualSubscriptionCount}<br />
</p>
<p>{translate key="plugins.reports.sshrcReport.reports.subscriptionBreakdown"}</p>
<ul>
	{foreach from=$subscriptionStats item=stat}
		<li>{$stat.name} => {$stat.count}</li>
	{/foreach}
</ul>
