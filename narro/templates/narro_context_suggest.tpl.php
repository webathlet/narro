<?php
    /**
     * Narro is an application that allows online software translation and maintenance.
     * Copyright (C) 2008-2010 Alexandru Szasz <alexxed@gmail.com>
     * http://code.google.com/p/narro/
     *
     * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public
     * License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any
     * later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
     * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
     * more details.
     *
     * You should have received a copy of the GNU General Public License along with this program; if not, write to the
     * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
     */

    $strPageTitle = sprintf((QApplication::HasPermissionForThisLang('Can suggest', $this->objNarroContextInfo->Context->ProjectId))?t('Translate "%s"'):t('See suggestions for "%s"'),
        (strlen($this->objNarroContextInfo->Context->Text->TextValue)>30)?mb_substr($this->objNarroContextInfo->Context->Text->TextValue, 0, 30) . '...':$this->objNarroContextInfo->Context->Text->TextValue);

    require('configuration/header.inc.php')
?>

    <?php $this->RenderBegin() ?>
        <?php QApplication::ExecuteJavaScript(sprintf("if (location.hash.match('/c[0-9]+/')) {iContext=location.hash.replace(/i[0-9]+/,'');iContext=iContext.replace('#c','');iPos=location.hash.replace(/#c[0-9]+i/,'');sLocation=location.href.replace('&c=%d', '&c=' + iContext);sLocation=sLocation.replace('&ci=%d', '&ci=' + iPos);sLocation=sLocation.replace(location.hash,'');location.hash='';location.replace(sLocation);};", $this->objNarroContextInfo->ContextId, $this->intCurrentContext)); ?>
        <?php $this->pnlHeader->Render() ?>
        <?php $this->pnlBreadcrumb->Render(); ?>
        <br class="item_divider" />

        <?php echo t('Text to translate'); ?>:
        <div class="green3dbg" style="border:1px dotted #DDDDDD;padding:5px;" title="Textul original">
            <?php $this->pnlOriginalText->Render(); ?>
            <?php $this->btnCopyOriginal->Render(); ?>
            <?php $this->btnComments->Render(); ?>
        </div>
        <div class="white3dbg" style="border:1px solid #DDDDDD; padding:5px<?php if ($this->objNarroContextInfo->Context->Active == 0) echo ';color:gray;' ?>" title="<?php _t('Details about the place where the text appears'); ?>">
            <?php $this->pnlContext->Render(); ?>
        </div>
        <?php $this->pnlSuggestionList->Render(); ?>
        <?php $this->pnlSimilarSuggestionList->Render(); ?>
        <br />
        <?php if (QApplication::HasPermissionForThisLang('Can suggest', $this->objNarroContextInfo->Context->ProjectId)) { ?>
            <?php $this->pnlPluginMessages->Render(); ?>
            <?php echo t('Your translation'); ?>:
            <table cellspacing="3" border="0" style="border-width:0px;border-collapse:separate;width:100%;margin:0px">
            <tr>
            <td width="100%" valign="top" style="padding-left:0px;border:0px">
                <?php $this->txtSuggestionValue->Render("Rows=10"); ?>
            </td>
            <td valign="top" style="padding-left:0px;border:0px;white-space:nowrap;">
                <?php if (QApplication::HasPermissionForThisLang('Can approve', $this->objNarroContextInfo->Context->ProjectId)) { ?>
                    <?php $this->chkApprove->Render() ?>
                    <?php $this->chkGoToNext->Render(); ?>
                <?php } ?>
                <?php $this->chkShowOtherLanguages->Render(); ?>
                <?php $this->chkShowSimilarSuggestions->Render(); ?>
            </td>
            </tr>
            </table>
        <?php } else
                  echo sprintf(t('You can add suggestions if you are logged in. <a href="%s">Register</a> or <a href="%s">Log in</a> if you already have an account or an OpenId.'), 'narro_register.php?l=' . QApplication::$Language->LanguageCode, 'narro_login.php?l=' . QApplication::$Language->LanguageCode) . '<br /><br />';
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->btnPrevious100->Render() ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->btnPrevious->Render() ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->lblProgress->Render(); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->btnNext->Render(); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->btnNext100->Render(); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->pnlDiacritics->Render(); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php $this->btnSave->Render() ?>
        <?php $this->btnSaveIgnore->Render() ?>
        <br />
        <?php $this->pnlProgress->Render() ?>
        <?php $this->lblMessage->Render() ?>
        <br />
        <?php $this->pnlComments->Render() ?>
        <?php if(QApplication::GetUserId() != NarroUser::ANONYMOUS_USER_ID && $this->txtSuggestionValue->Display) $this->txtSuggestionValue->Focus(); ?>
    <?php $this->RenderEnd() ?>

<?php require('configuration/footer.inc.php'); ?>
