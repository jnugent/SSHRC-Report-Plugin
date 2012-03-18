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
{assign var="pageTitle" value="plugins.generic.sshrcReport.manager.sshrcReportSettings"}
{include file="common/header.tpl"}
{/strip}
<div id="sshrcReportSettings">
<div id="description">{translate key="plugins.generic.sshrcReport.manager.settings.description"}</div>

<div class="separator"></div>

<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

<table class="data" width="100%">
	<tr valign="top">
		<td class="label">{fieldLabel name="impact" key="plugins.generic.sshrcReport.form.impact"}</td>
		<td class="value"><textarea name="impact[{$formLocale|escape}]" id="impact" rows="5" cols="40" class="textArea">{$impact[$formLocale]|escape}</textarea></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="researchRecord" key="plugins.generic.sshrcReport.form.researchRecord"}</td>
		<td class="value"><textarea name="researchRecord[{$formLocale|escape}]" id="researchRecord" rows="5" cols="40" class="textArea">{$researchRecord[$formLocale]|escape}</textarea></td>
	</tr>
	<tr valign="top">
		<td class="label">{fieldLabel name="editorialBoardFunc" key="plugins.generic.sshrcReport.form.editorialBoardFunc"}</td>
		<td class="value"><textarea name="editorialBoardFunc[{$formLocale|escape}]" id="editorialBoardFunc" rows="5" cols="40" class="textArea">{$editorialBoardFunc[$formLocale]|escape}</textarea></td>
	</tr>
</table>

<br/>

<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/><input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
{include file="common/footer.tpl"}
