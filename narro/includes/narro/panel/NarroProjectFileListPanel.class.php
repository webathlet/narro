<?php
    /**
     * Narro is an application that allows online software translation and maintenance.
     * Copyright (C) 2008-2011 Alexandru Szasz <alexxed@gmail.com>
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

    class NarroProjectFileListPanel extends QPanel {
        protected $objProject;
        protected $objFile;
        public $pnlBreadcrumb;

        public $dtgFile;
        protected $objParentFile;

        // DataGrid Columns
        protected $colFileName;
        protected $colPercentTranslated;
        protected $colExport;

        public $chkShowHierarchy;
        public $chkShowFolders;

        public $txtSearch;
        public $btnSearch;

        public function __construct(NarroProject $objNarroProject, string $strCurrentPath = null, $objParentObject, $strControlId = null) {
            // Call the Parent
            try {
                parent::__construct($objParentObject, $strControlId);
            } catch (QCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }

            $this->pnlBreadcrumb = new NarroBreadcrumbPanel($this);
            $this->pnlBreadcrumb->strSeparator = ' / ';

            $this->objProject = $objNarroProject;

            $this->chkShowHierarchy = new QCheckBox($this);
            $this->chkShowHierarchy->Checked = (QApplication::QueryString('s') == '');
            $this->chkShowHierarchy->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'dtgFile_SetConditions'));

            $this->chkShowFolders = new QCheckBox($this);
            $this->chkShowFolders->Checked = true;
            $this->chkShowFolders->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'dtgFile_SetConditions'));

            $this->strTemplate = __NARRO_INCLUDES__ . '/narro/panel/NarroProjectFileListPanel.tpl.php';

            $this->ChangeDirectory($strCurrentPath);

            $this->btnSearch = new QButton($this);
            $this->btnSearch->Text = t('Search');
            $this->btnSearch->AddAction(new QClickEvent(), new QServerControlAction($this, 'btnSearch_Click'));
            $this->btnSearch->PrimaryButton = true;

            $this->txtSearch = new QTextBox($this);
            $this->txtSearch->Text = QApplication::QueryString('s');

            $this->ChangeDirectory(QApplication::QueryString('pf'));

            $this->dtgFile_Create();
            $this->dtgFile_SetConditions();

        }

        public function ChangeDirectory($strPath) {

            if ($strPath)
                $this->objParentFile = NarroFile::QuerySingle(
                    QQ::AndCondition(
                        QQ::Equal(QQN::NarroFile()->ProjectId, $this->objProject->ProjectId),
                        QQ::Equal(QQN::NarroFile()->Active, 1),
                        QQ::Equal(QQN::NarroFile()->FilePath, $strPath)
                    )
                );

            $this->pnlBreadcrumb->Visible = false;
            $this->pnlBreadcrumb->setElements(
                NarroLink::ProjectFileList($this->objProject->ProjectId, null, null, '..')
            );

            if ($this->objParentFile) {
                $arrPaths = explode('/', $this->objParentFile->FilePath);
                $strProgressivePath = '';
                if (is_array($arrPaths)) {
                    /**
                     * remove the first part that is empty because paths begin with /
                     * and the last part that will be displayed unlinked
                     */
                    unset($arrPaths[count($arrPaths) - 1]);
                    unset($arrPaths[0]);
                    foreach($arrPaths as $intCnt =>$strPathPart) {
                        $strProgressivePath .= '/' . $strPathPart;
                        $this->pnlBreadcrumb->addElement(
                            NarroLink::ProjectFileList(
                                    $this->objProject->ProjectId,
                                    $strProgressivePath,
                                    null,
                                    $strPathPart
                            )
                        );
                    }
                }
            }

            if ($this->objParentFile instanceof NarroFile) {
                $this->pnlBreadcrumb->addElement($this->objParentFile->FileName);
                $this->pnlBreadcrumb->Visible = true;
            }
        }

        public function dtgFile_PercentTranslated_Render(NarroFileProgress $objProgress) {

            $strOutput = '';

            if (!$objProgressBar = $this->dtgFile->GetChildControl('prg' . $objProgress->FileId)) {
                $objWaitIcon = new QWaitIcon($this->dtgFile, 'wait' . $objProgress->FileId);
                $objWaitIcon->Text = t('Counting texts and translations...');

                $objProgressBar = new NarroTranslationProgressBar($this->dtgFile, 'prg' . $objProgress->FileId);
                $objProgressBar->ActionParameter = $objProgress->FileId;
                $objProgressBar->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnRefresh_Click', $objWaitIcon));
            }

            $objWaitIcon = $this->dtgFile->GetChildControl('wait' . $objProgress->FileId);

            $objProgressBar->Total = $objProgress->TotalTextCount;
            $objProgressBar->Translated = $objProgress->ApprovedTextCount;
            $objProgressBar->Fuzzy = $objProgress->FuzzyTextCount;

            $strOutput .= $objProgressBar->Render(false);
            $strOutput .= $objWaitIcon->Render(false);

            QApplication::$PluginHandler->DisplayInFileListInProgressColumn($objProgress->File);

            if (is_array(QApplication::$PluginHandler->PluginReturnValues)) {
                $strOutput .= '';
                foreach(QApplication::$PluginHandler->PluginReturnValues as $strPluginName=>$mixReturnValue) {
                    if (count($mixReturnValue) == 2 && $mixReturnValue[0] instanceof NarroProject && is_string($mixReturnValue[1]) && $mixReturnValue[1] != '') {
                        $strOutput .= sprintf('<span style="font-size:small" title="%s">%s</span><br />', $strPluginName, $mixReturnValue[1]);
                    }
                }
                $strOutput .= '';
            }

            $this->btnRefresh_Click('', '', $objProgress->FileId);

            return $strOutput;

        }

        public function dtgFile_FileNameColumn_Render(NarroFileProgress $objProgress) {
            if ($objProgress->File->TypeId == NarroFileType::Folder)
                return sprintf('<img src="%s" style="vertical-align:middle" /> %s',
                    __NARRO_IMAGE_ASSETS__ . '/folder.png',
                    NarroLink::ProjectFileList(
                        $this->objProject->ProjectId,
                        $objProgress->File->FilePath,
                        null,
                        $objProgress->File->FileName
                    )
                );
            else {

                switch($objProgress->File->TypeId) {
                    case NarroFileType::MozillaDtd:
                            $strIcon = 'dtd_file.gif';
                            break;
                    case NarroFileType::MozillaInc:
                            $strIcon = 'inc_file.gif';
                            break;
                    case NarroFileType::MozillaIni:
                            $strIcon = 'ini_file.gif';
                            break;
                    default:
                            $strIcon = 'dtd_file.gif';
                }

                return sprintf('<img src="%s" style="vertical-align:middle" /> %s',
                    __NARRO_IMAGE_ASSETS__ . '/' . $strIcon,
                    NarroLink::Translate(
                        $objProgress->File->ProjectId,
                        $objProgress->File->FilePath,
                        NarroTranslatePanel::SHOW_ALL,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        $objProgress->File->FileName
                    )
                );
            }
        }

        public function dtgFile_ExportColumn_Render(NarroFileProgress $objProgress) {
            if ($objProgress->File->TypeId == NarroFileType::Folder)
                return '';

            $strControlId = 'chkExport' . $objProgress->FileId;
            $chkExport = $this->dtgFile->GetChildControl($strControlId);
            if (!$chkExport) {
                $chkExport = new QCheckBox($this->dtgFile, $strControlId);
                $chkExport->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'chkExport_Click'));
            }
            $chkExport->ActionParameter = $objProgress->File->FileId;
            $chkExport->Checked = NarroFileProgress::CountByFileIdLanguageIdExport($objProgress->File->FileId, QApplication::GetLanguageId(), 1);

            return $chkExport->Render(false);
        }

        public function chkExport_Click($strFormId, $strControlId, $intFileId) {
            $chkExport = $this->dtgFile->GetChildControl($strControlId);
            $objFileProgress = NarroFileProgress::LoadByFileIdLanguageId($intFileId, QApplication::GetLanguageId());
            if ($objFileProgress) {
                $objFileProgress->Export = !$objFileProgress->Export;
                $objFileProgress->Save();
            }
        }

        protected function dtgFile_Create() {
            $this->colExport = new QDataGridColumn(t('Export'), '<?= $_CONTROL->ParentControl->dtgFile_ExportColumn_Render($_ITEM) ?>', array('OrderByClause' => QQ::OrderBy(QQN::NarroFile()->NarroFileProgressAsFile->Export), 'ReverseOrderByClause' => QQ::OrderBy(QQN::NarroFile()->NarroFileProgressAsFile->Export, false)));
            $this->colExport->HtmlEntities = false;

            // Setup DataGrid
            $this->dtgFile = new NarroFileProgressDataGrid($this);

            // Datagrid Paginator
            $this->dtgFile->Paginator = new QPaginator($this->dtgFile);
            $this->dtgFile->ItemsPerPage = QApplication::$User->getPreferenceValueByName('Items per page');
            $this->dtgFile->PaginatorAlternate = new QPaginator($this->dtgFile);
            $this->dtgFile->SortColumnIndex = 0;
            $this->dtgFile->ShowFilter = false;

            $this->colFileName = $this->dtgFile->MetaAddColumn(QQN::NarroFileProgress()->File->FileName);
            $this->colFileName->HtmlEntities = false;
            $this->colFileName->Html = '<?= $_CONTROL->ParentControl->dtgFile_FileNameColumn_Render($_ITEM) ?>';
            $this->colFileName->Name = t('File name');

            $this->colFileName = $this->dtgFile->MetaAddColumn(QQN::NarroFileProgress()->ProgressPercent);
            $this->colFileName->HtmlEntities = false;
            $this->colFileName->Html = '<?= $_CONTROL->ParentControl->dtgFile_PercentTranslated_Render($_ITEM) ?>';
            $this->colFileName->Name = t('Translation Progress');

            if (QApplication::HasPermission('Can manage project', $this->objProject->ProjectId, QApplication::GetLanguageId()))
                $this->dtgFile->AddColumn($this->colExport);
        }

        public function dtgFile_SetConditions() {

            if ($this->txtSearch->Text == '')
                $objCommonCondition = QQ::AndCondition(
                    QQ::Equal(QQN::NarroFileProgress()->File->Active, 1),
                    QQ::Equal(QQN::NarroFileProgress()->LanguageId, QApplication::GetLanguageId()),
                    QQ::Equal(QQN::NarroFileProgress()->File->ProjectId, $this->objProject->ProjectId)
                );
            else {
                $objCommonCondition = QQ::AndCondition(
                    QQ::Equal(QQN::NarroFileProgress()->File->Active, 1),
                    QQ::Equal(QQN::NarroFileProgress()->LanguageId, QApplication::GetLanguageId()),
                    QQ::Equal(QQN::NarroFileProgress()->File->ProjectId, $this->objProject->ProjectId),
                    QQ::Like(QQN::NarroFileProgress()->File->FileName, sprintf('%%%s%%', $this->txtSearch->Text))
                );
            }

            // Remember!  We need to first set the TotalItemCount, which will affect the calcuation of LimitClause below
            if (!$this->chkShowHierarchy->Checked) {
                if ($this->chkShowFolders->Checked)
                    $this->dtgFile->AdditionalConditions = $objCommonCondition;
                else
                    $this->dtgFile->AdditionalConditions = QQ::AndCondition($objCommonCondition, QQ::NotEqual(QQN::NarroFileProgress()->File->TypeId, NarroFileType::Folder));
            }
            elseif ($this->objParentFile) {
                $objParentCondition = QQ::Equal(QQN::NarroFileProgress()->File->ParentId, $this->objParentFile->FileId);
                if ($this->chkShowFolders->Checked)
                    $this->dtgFile->AdditionalConditions = QQ::AndCondition($objCommonCondition, $objParentCondition);
                else
                    $this->dtgFile->AdditionalConditions = QQ::AndCondition($objCommonCondition, $objParentCondition, QQ::NotEqual(QQN::NarroFileProgress()->File->TypeId, NarroFileType::Folder));
            }
            else {
                $objParentCondition = QQ::IsNull(QQN::NarroFileProgress()->File->ParentId);
                if ($this->chkShowFolders->Checked)
                    $this->dtgFile->AdditionalConditions = QQ::AndCondition($objCommonCondition, $objParentCondition);
                else
                    $this->dtgFile->AdditionalConditions = QQ::AndCondition($objCommonCondition, $objParentCondition, QQ::NotEqual(QQN::NarroFileProgress()->File->TypeId, NarroFileType::Folder));
            }

            $this->MarkAsModified();
        }

        public function btnSearch_Click() {
            QApplication::Redirect(NarroLink::ProjectFileList($this->objProject->ProjectId, ($this->objParentFile instanceof NarroFile)?$this->objParentFile->FilePath:'', $this->txtSearch->Text));
        }

        public function btnRefresh_Click($strFormId, $strControlId, $intFileId) {
            $objFile = NarroFile::Load($intFileId);
            if ($objFile) {
                $intTotalTexts = $objFile->CountAllTextsByLanguage();
                $intApprovedTexts = $objFile->CountApprovedTextsByLanguage();
                $intTranslatedTexts = $objFile->CountTranslatedTextsByLanguage();
                $objProgressBar = $this->dtgFile->GetChildControl('prg' . $intFileId);
                if ($objProgressBar) {
                    $objProgressBar->Total = $intTotalTexts;
                    $objProgressBar->Translated = $intApprovedTexts;
                    $objProgressBar->Fuzzy = $intTranslatedTexts;
                    $objProgressBar->MarkAsModified();
                }
            }
        }
    }
?>
