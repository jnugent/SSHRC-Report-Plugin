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

<p>{translate key="plugins.reports.sshrcReport.reports.sshrcReportDescription"}</p>

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
{if $validInstitutionalSubscriptionCount}
	{translate key="plugins.reports.sshrcReport.reports.validInstitutionCountString" validInstitutionalSubscriptionCount=$validInstitutionalSubscriptionCount}<br />
{/if}
{if $validIndividualSubscriptionCount}
	{translate key="plugins.reports.sshrcReport.reports.validIndividualCountString" validIndividualSubscriptionCount=$validIndividualSubscriptionCount}<br />
{/if}
{if $pendingInstitutionalSubscriptionCount}
	{translate key="plugins.reports.sshrcReport.reports.pendingInstitutionCountString" pendingInstitutionalSubscriptionCount=$pendingInstitutionalSubscriptionCount}<br />
{/if}
{if $pendingIndividualSubscriptionCount}
	{translate key="plugins.reports.sshrcReport.reports.pendingIndividualCountString" pendingIndividualSubscriptionCount=$pendingIndividualSubscriptionCount}<br />
{/if}
</p>
<p>{translate key="plugins.reports.sshrcReport.reports.subscriptionBreakdown"}</p>
<table cellpadding="5">
	<tr>
		<th>{translate key="plugins.reports.sshrcReport.reports.subscriptionName"}</th>
		<th>{translate key="plugins.reports.sshrcReport.reports.active"}</th>
		<th>{translate key="plugins.reports.sshrcReport.reports.pending"}</th>
		<th>{translate key="plugins.reports.sshrcReport.reports.averagePending"}</th>
	</tr>
	{foreach from=$subscriptionStats item=stat}
		<tr>
			<td>{$stat.name}</td><td style="text-align: center;">{$stat.valid}</td>
			<td style="text-align: center;">{$stat.invalid}</td><td style="text-align: center;">{if $stat.invalid > 0}{math equation="x/y" x=$stat.daysPastDue|@array_sum y=$stat.daysPastDue|@count format="%.1f"}{/if}</td>
		</tr>
		{/foreach}
</table>
