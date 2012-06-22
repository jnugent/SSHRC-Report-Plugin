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

{if $includedFields.focusScopeDesc}
	<h3>{translate key="plugins.reports.sshrcReport.reports.focusScopeDesc"}</h3>
	{$focusScopeDesc[$currentLocale]}
{/if}

{if $includedFields.authorGuidelines}
	<h3>{translate key="plugins.reports.sshrcReport.reports.authorGuidelines"}</h3>
	{$authorGuidelines[$currentLocale]}
{/if}

{if $includedFields.reviewPolicy}
	<h3>{translate key="plugins.reports.sshrcReport.reports.reviewPolicy"}</h3>
	{$reviewPolicy[$currentLocale]}
{/if}

{if $includedFields.reviewGuidelines}
	<h3>{translate key="plugins.reports.sshrcReport.reports.reviewGuidelines"}</h3>
	{$reviewGuidelines[$currentLocale]}
{/if}

{if $includedFields.pubFreqPolicy}
	<h3>{translate key="plugins.reports.sshrcReport.reports.pubFreqPolicy"}</h3>
	{$pubFreqPolicy[$currentLocale]}
{/if}

{if $impact}
<h3>{translate key="plugins.reports.sshrcReport.reports.impact"}</h3>

{$impact[$currentLocale]}
{/if}

{if $researchRecord}
<h3>{translate key="plugins.reports.sshrcReport.reports.researchRecord"}</h3>

{$researchRecord[$currentLocale]}
{/if}

{if $editorialBoardFunc}
<h3>{translate key="plugins.reports.sshrcReport.reports.editorialBoardFunc"}</h3>

{$editorialBoardFunc[$currentLocale]}
{/if}

{if $subscriptionInfo}
<h3>{translate key="plugins.reports.sshrcReport.reports.subscriptionInfo"}</h3>

{$subscriptionInfo[$currentLocale]}
{/if}

<h3>{translate key="plugins.reports.sshrcReport.reports.subscribers}</h3>

<p>
{translate key="plugins.reports.sshrcReport.reports.subscriberCountString" numberOfReaders=$numberOfReaders}<br />
{translate key="plugins.reports.sshrcReport.reports.institutionCountString" institutionalSubscriptionCount=$institutionalSubscriptionCount}<br />
{translate key="plugins.reports.sshrcReport.reports.individualCountString" individualSubscriptionCount=$individualSubscriptionCount}<br />
{translate key="plugins.reports.sshrcReport.reports.validInstitutionCountString" validInstitutionalSubscriptionCount=$validInstitutionalSubscriptionCount}<br />
{translate key="plugins.reports.sshrcReport.reports.validIndividualCountString" validIndividualSubscriptionCount=$validIndividualSubscriptionCount}<br />
</p>
<p>{translate key="plugins.reports.sshrcReport.reports.subscriptionBreakdown"}</p>
<ul>
	{foreach from=$subscriptionStats item=stat}
		<li>{$stat.name} => {$stat.valid} valid, {$stat.invalid} invalid</li>
	{/foreach}
</ul>
