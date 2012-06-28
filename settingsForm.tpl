{**
 * plugins/generic/sshrcReport/settingsForm.tpl
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * SSHRC Report plugin settings
 *
 *}
{strip}
{assign var="pageTitle" value="plugins.reports.sshrcReport.manager.sshrcReportSettings"}
{include file="common/header.tpl"}
{/strip}
<div id="sshrcReportSettings">
{url|assign:"reportUrl" op="statistics"}
{url|assign:"analyticsUrl" op="plugins" path="generic"}
<div id="description">{translate key="plugins.reports.sshrcReport.manager.settings.description" reportUrl=$reportUrl analyticsUrl=$analyticsUrl}</div>

<div class="separator"></div>

<h2>{translate key="plugins.reports.sshrcReport.manager.settings.existingInformation"}</h2>

<p>{translate key="plugins.reports.sshrcReport.manager.settings.existingInformationInstructions"}</p>

<form method="post">

{include file="common/formErrors.tpl"}

<table class="data" width="100%">
	{url|assign:"url" op="setup"}
	{foreach from=$ojsFields item="setting" key="field"}
		{assign var="localeKey" value="plugins.reports.sshrcReport.form.include."|concat:$field}
		<tr>
			<td class="value"><input type="checkbox" name="{$field}" {if $setting}checked="checked"{/if} /></td>
			<td class="value">{fieldLabel name=$field key=$localeKey url=$url}</td>
		</tr>
	{/foreach}
</table>

<div class="separator"></div>

<h2>{translate key="plugins.reports.sshrcReport.manager.settings.additionalInformation"}</h2>

<p>{translate key="plugins.reports.sshrcReport.manager.settings.additionalInformationInstructions"}</p>

<table class="data" width="100%">
	<tr valign="top">
		<td class="label">{fieldLabel name="impact" key="plugins.reports.sshrcReport.form.impact"}</td>
		<td class="value"><textarea name="impact[{$formLocale|escape}]" id="impact" rows="5" cols="40" class="textArea richContent">{$impact[$formLocale]|escape}</textarea></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="researchRecord" key="plugins.reports.sshrcReport.form.researchRecord"}</td>
		<td class="value"><textarea name="researchRecord[{$formLocale|escape}]" id="researchRecord" rows="5" cols="40" class="textArea richContent">{$researchRecord[$formLocale]|escape}</textarea></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="editorialBoardFunc" key="plugins.reports.sshrcReport.form.editorialBoardFunc"}</td>
		<td class="value"><textarea name="editorialBoardFunc[{$formLocale|escape}]" id="editorialBoardFunc" rows="5" cols="40" class="textArea richContent">{$editorialBoardFunc[$formLocale]|escape}</textarea></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="subscriptionInfo" key="plugins.reports.sshrcReport.form.subscriptionInfo"}</td>
		<td class="value"><textarea name="subscriptionInfo[{$formLocale|escape}]" id="subscriptionInfo" rows="5" cols="40" class="textArea richContent">{$subscriptionInfo[$formLocale]|escape}</textarea></td>
	</tr>
</table>

<br/>

<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/><input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>
</div>
{include file="common/footer.tpl"}
