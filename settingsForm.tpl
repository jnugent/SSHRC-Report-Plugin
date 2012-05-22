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
<div id="description">{translate key="plugins.reports.sshrcReport.manager.settings.description"}</div>

<div class="separator"></div>

{translate key="plugins.reports.sshrcReport.manager.settings.instructions"}

<form method="post">
{include file="common/formErrors.tpl"}

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

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
{include file="common/footer.tpl"}
