<?php
    /**
     * Narro is an application that allows online software translation and maintenance.
     * Copyright (C) 2008 Alexandru Szasz <alexxed@gmail.com>
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

    require(__DATAGEN_CLASSES__ . '/NarroTextContextGen.class.php');
    require_once(__DOCROOT__ . __SUBDIRECTORY__ . '/narro_text_list.php');

    /**
    * The NarroTextContext class defined here contains any
    * customized code for the NarroTextContext class in the
    * Object Relational Model.  It represents the "narro_text_context" table
    * in the database, and extends from the code generated abstract NarroTextContextGen
    * class, which contains all the basic CRUD-type functionality as well as
    * basic methods to handle relationships and index-based loading.
    *
    * @package My Application
    * @subpackage DataObjects
    *
    */
    class NarroTextContext extends NarroTextContextGen {
        /**
        * Default "to string" handler
        * Allows pages to _p()/echo()/print() this object, and to define the default
        * way this object would be outputted.
        *
        * Can also be called directly via $objNarroTextContext->__toString().
        *
        * @return string a nicely formatted string representation of this object
        */
        public function __toString() {
            return sprintf('NarroTextContext Object %s',  $this->intContextId);
        }

        public static function GetSearchCondition($strText, $intSearchType, $intFilter) {
            if (strlen($strText)) {
                switch($intSearchType) {
                    case 1:
                        if (strlen($strText) < 4 || preg_match("/^'.*'$/", $strText)) {
                            if (preg_match("/^'.*'$/", $strText))
                                $strText = substr($strText, 1, -1);
                            $strSearchCondition = sprintf("AND narro_text_suggestion.suggestion_value = '%s'", $strText);
                        }
                        else {
                            $strSearchCondition = sprintf("AND narro_text_suggestion.suggestion_value LIKE '%%%s%%'", $strText);
                        }
                        break;
                    case 2:
                        if (strlen($strText) < 4 || preg_match("/^'.*'$/", $strText)) {
                            if (preg_match("/^'.*'$/", $strText))
                                $strText = substr($strText, 1, -1);
                            $strSearchCondition = sprintf("AND narro_text.text_value = '%s'", $strText);
                        }
                        else {
                            $strSearchCondition = sprintf("AND narro_text.text_value LIKE '%%%s%%'", $strText);
                        }
                        break;
                    default:
                        if (strlen($strText) < 4 || preg_match("/^'.*'$/", $strText)) {
                            if (preg_match("/^'.*'$/", $strText))
                                $strText = substr($strText, 1, -1);
                            $strSearchCondition = sprintf("AND (narro_text_suggestion.suggestion_value = '%s' OR narro_text.text_value = '%s')", $strText, $strText);
                        }
                        else {
                            $strSearchCondition = sprintf("AND (narro_text_suggestion.suggestion_value LIKE '%%%s%%' OR narro_text.text_value LIKE '%%%s%%')", $strText, $strText);
                        }
                }
                return $strSearchCondition;
            }
            else
                return false;
        }

        public static function GetContext($intContextId, $strSearchText, $intSearchType, $intFilter, $objSortInfo, $objExtraCondition) {
            switch($intSearchType) {
                case NarroTextListForm::SEARCH_TEXTS:
                    $arrContexts = self::LoadArrayByTextValue($strSearchText, $intFilter, QQ::LimitInfo(1, 0), $objSortInfo, $objExtraCondition);
                    break;
                case NarroTextListForm::SEARCH_SUGGESTIONS:
                    $arrContexts = self::LoadArrayBySuggestionValue($strSearchText, $intFilter, QQ::LimitInfo(1, 0), $objSortInfo, $objExtraCondition);
                    break;
                case NarroTextListForm::SEARCH_CONTEXTS:
                    $arrContexts = self::LoadArrayByContext($strSearchText, $intFilter, QQ::LimitInfo(1, 0), $objSortInfo, $objExtraCondition);
                    break;
                default:
                    $arrContexts = self::QueryArray($objExtraCondition, array(QQ::LimitInfo(1, 0), $objSortInfo));
            }

            if (count($arrContexts) && $arrContexts[0] instanceof NarroTextContext)
                return $arrContexts[0];
            else
                return false;
        }

        public static function LoadBySuggestionSearch($strText, $intProjectId, $intFileId, $intSearchType, $intFilter, $strLimitInfo, $strOrderInfo = 'narro_text_context.context_id ASC', $strExtraCondition = '') {
            $intTime = time();
            // Performing the load manually (instead of using Qcodo Query)

            // Get the Database Object for this Class
            $objDatabase = NarroTextContext::GetDatabase();

            $strSearchCondition = NarroTextContext::GetSearchCondition($strText, $intSearchType, $intFilter);
            if ($intSearchType == 1)
                $strSuggestionJoin = 'narro_text_context.text_id = narro_text_suggestion.text_id AND';

            switch($intFilter) {
                case 2:
                    $strFilter = 'AND narro_text_context.valid_suggestion_id IS NOT NULL';
                    break;
                case 3:
                    $strFilter = 'AND narro_text_context.valid_suggestion_id IS NULL';
                    break;
                case 4:
                    $strSearchCondition = '';
                    $strSuggestionJoin = '';
                    $strFilter = 'AND narro_text_context.has_suggestion=0';
                    break;
                case 5:
                    $strFilter = 'AND narro_text_context.valid_suggestion_id IS NULL AND narro_text_context.has_suggestion=1';
                    break;
                default:
            }


            // Setup the SQL Query
            $strQuery = sprintf("
                SELECT
                    DISTINCT narro_text_context.*
                FROM
                    narro_text_context,
                    %s
                    narro_text
                WHERE
                    %s
                    narro_text_context.text_id = narro_text.text_id AND
                    narro_text_context.active = 1 AND
                    narro_text_context.translatable = 1
                    %s
                    %s
                    %s
                    %s
                    %s
                    %s
                    %s ",
                ($strSuggestionJoin)?'narro_text_suggestion,':'',
                $strSuggestionJoin,
                ($intProjectId)?'AND narro_text_context.project_id=' . $intProjectId:'',
                ($intFileId)?'AND narro_text_context.file_id=' . $intFileId:'',
                $strSearchCondition,
                $strExtraCondition,
                $strFilter,
                ($strOrderInfo)?'ORDER BY ' . $strOrderInfo:'',
                $strLimitInfo);
            // Perform the Query and Instantiate the Result
            $objDbResult = $objDatabase->Query($strQuery);
            error_log(__FUNCTION__ . ': ' . (time() - $intTime) . ' secunde');
            return NarroTextContext::InstantiateDbResult($objDbResult);
        }

        public static function CountBySuggestionSearch($strText, $intProjectId, $intFileId, $intSearchType, $intFilter) {
            // Performing the load manually (instead of using Qcodo Query)

            // Get the Database Object for this Class
            $objDatabase = NarroTextContext::GetDatabase();
            $strSearchCondition = NarroTextContext::GetSearchCondition($strText, $intSearchType, $intFilter);
            if ($intSearchType == 1)
                $strSuggestionJoin = 'narro_text_context.text_id = narro_text_suggestion.text_id AND';

            switch($intFilter) {
                case 2:
                    $strFilter = 'AND narro_text_context.valid_suggestion_id IS NOT NULL';
                    break;
                case 3:
                    $strFilter = 'AND narro_text_context.valid_suggestion_id IS NULL';
                    break;
                case 4:
                    $strSearchCondition = '';
                    $strSuggestionJoin = '';
                    $strFilter = 'AND narro_text_context.has_suggestion=0';
                    break;
                case 5:
                    $strFilter = 'AND narro_text_context.valid_suggestion_id IS NULL AND narro_text_context.has_suggestion=1';
                    break;
                default:
            }


            // Setup the SQL Query
            $strQuery = sprintf("
                SELECT
                    COUNT(DISTINCT narro_text_context.context_id)
                FROM
                    narro_text_context,
                    %s
                    narro_text
                WHERE
                    %s
                    narro_text_context.text_id = narro_text.text_id AND
                    narro_text_context.active = 1 AND
                    narro_text_context.translatable = 1
                    %s
                    %s
                    %s
                    %s ",
                ($strSuggestionJoin)?'narro_text_suggestion,':'',
                $strSuggestionJoin,
                ($intProjectId)?'AND narro_text_context.project_id=' . $intProjectId:'',
                ($intFileId)?'AND narro_text_context.file_id=' . $intFileId:'',
                $strSearchCondition,
                $strFilter);

            // Perform the Query and Instantiate the Result
            $objDbResult = $objDatabase->Query($strQuery);

            $arrRow = $objDbResult->FetchArray();
            if (isset($arrRow[0]))
                return $arrRow[0];
            else
                return 0;
        }

        public static function LoadArrayByContext($strContext, $intFilter, $objLimitInfo = null, $objSortInfo = null, $objExtraCondition = null) {
            if (!is_object($objExtraCondition))
                $objExtraCondition = QQ::All();

            if (!is_object($objSortInfo))
                $objSortInfo = QQ::OrderBy(array(QQN::NarroTextContext()->ContextId, true));

            if (!is_object($objLimitInfo))
                $objLimitInfo = QQ::LimitInfo(20, 0);

            if (trim($strContext) == '')
                $objSearchCondition = QQ::All();
            elseif (preg_match("/^'.*'$/", $strContext))
                $objSearchCondition = QQ::Equal(QQN::NarroTextContext()->Context, substr($strContext, 1, -1));
            else
                $objSearchCondition = QQ::Like(QQN::NarroTextContext()->Context,  '%' . $strContext . '%');

            switch ($intFilter) {
                case NarroTextListForm::SHOW_UNTRANSLATED_TEXTS :
                    $objFilterCondition = QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 0);
                    break;
                case NarroTextListForm::SHOW_VALIDATED_TEXTS :
                    $objFilterCondition = QQ::IsNotNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                case NarroTextListForm::SHOW_TEXTS_THAT_REQUIRE_VALIDATION :
                    $objFilterCondition = QQ::IsNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                default:
                    // no filters
                    $objFilterCondition = QQ::All();
            }

            $arrContext = NarroTextContext::QueryArray(QQ::AndCondition($objSearchCondition, $objFilterCondition, $objExtraCondition), array($objLimitInfo, $objSortInfo));

            return $arrContext;
        }

        public static function CountByContext($strContext, $intFilter, $objExtraCondition = null) {
            if (!is_object($objExtraCondition))
                $objExtraCondition = QQ::All();

            if (trim($strContext) == '')
                $objSearchCondition = QQ::All();
            elseif (preg_match("/^'.*'$/", $strContext))
                $objSearchCondition = QQ::Equal(QQN::NarroTextContext()->Context, substr($strContext, 1, -1));
            else
                $objSearchCondition = QQ::Like(QQN::NarroTextContext()->Context,  '%' . $strContext . '%');

            switch ($intFilter) {
                case NarroTextListForm::SHOW_UNTRANSLATED_TEXTS :
                    $objFilterCondition = QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 0);
                    break;
                case NarroTextListForm::SHOW_VALIDATED_TEXTS :
                    $objFilterCondition = QQ::IsNotNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                case NarroTextListForm::SHOW_TEXTS_THAT_REQUIRE_VALIDATION :
                    $objFilterCondition = QQ::IsNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                default:
                    // no filters
                    $objFilterCondition = QQ::All();
            }

            $intContextCount = NarroTextContext::QueryCount(QQ::AndCondition($objSearchCondition, $objFilterCondition, $objExtraCondition));

            return $intContextCount;
        }

        public static function LoadArrayByTextValue($strTextValue, $intFilter, $objLimitInfo = null, $objSortInfo = null, $objExtraCondition = null) {
            if (!is_object($objExtraCondition))
                $objExtraCondition = QQ::All();

            if (!is_object($objSortInfo))
                $objSortInfo = QQ::OrderBy(array(QQN::NarroTextContext()->ContextId, true));

            if (!is_object($objLimitInfo))
                $objLimitInfo = QQ::LimitInfo(20, 0);

            if (trim($strTextValue) == '')
                $objSearchCondition = QQ::All();
            elseif (preg_match("/^'.*'$/", $strTextValue))
                $objSearchCondition = QQ::Equal(QQN::NarroTextContext()->Text->TextValue, substr($strTextValue, 1, -1));
            else
                $objSearchCondition = QQ::Like(QQN::NarroTextContext()->Text->TextValue,  '%' . $strTextValue . '%');

            switch ($intFilter) {
                case NarroTextListForm::SHOW_UNTRANSLATED_TEXTS :
                    $objFilterCondition = QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 0);
                    break;
                case NarroTextListForm::SHOW_VALIDATED_TEXTS :
                    $objFilterCondition = QQ::IsNotNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                case NarroTextListForm::SHOW_TEXTS_THAT_REQUIRE_VALIDATION :
                    $objFilterCondition = QQ::AndCondition(QQ::IsNull(QQN::NarroTextContext()->ValidSuggestionId), QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 1));
                    break;
                default:
                    // no filters
                    $objFilterCondition = QQ::All();
            }

            $arrContext = NarroTextContext::QueryArray(QQ::AndCondition($objSearchCondition, $objFilterCondition, $objExtraCondition), array($objLimitInfo, $objSortInfo));

            return $arrContext;
        }

        public static function CountByTextValue($strTextValue, $intFilter, $objExtraCondition = null) {
            if (!is_object($objExtraCondition))
                $objExtraCondition = QQ::All();

            if (trim($strTextValue) == '')
                $objSearchCondition = QQ::All();
            elseif (preg_match("/^'.*'$/", $strTextValue))
                $objSearchCondition = QQ::Equal(QQN::NarroTextContext()->Text->TextValue, substr($strTextValue, 1, -1));
            else
                $objSearchCondition = QQ::Like(QQN::NarroTextContext()->Text->TextValue,  '%' . $strTextValue . '%');

            switch ($intFilter) {
                case NarroTextListForm::SHOW_UNTRANSLATED_TEXTS :
                    $objFilterCondition = QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 0);
                    break;
                case NarroTextListForm::SHOW_VALIDATED_TEXTS :
                    $objFilterCondition = QQ::IsNotNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                case NarroTextListForm::SHOW_TEXTS_THAT_REQUIRE_VALIDATION :
                    $objFilterCondition = QQ::AndCondition(QQ::IsNull(QQN::NarroTextContext()->ValidSuggestionId), QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 1));
                    break;
                default:
                    // no filters
                    $objFilterCondition = QQ::All();
            }

            $intContextCount = NarroTextContext::QueryCount(QQ::AndCondition($objSearchCondition, $objFilterCondition, $objExtraCondition));

            return $intContextCount;
        }

        public static function LoadArrayBySuggestionValue($strSuggestion, $intFilter, $objLimitInfo = null, $objSortInfo = null, $objExtraCondition = null) {
            if (!is_object($objExtraCondition))
                $objExtraCondition = QQ::All();

            if (!is_object($objSortInfo))
                $objSortInfo = QQ::OrderBy(array(QQN::NarroTextContext()->ContextId, true));

            if (!is_object($objLimitInfo))
                $objLimitInfo = QQ::LimitInfo(20, 0);

            if (trim($strSuggestion) == '')
                $objSearchCondition = QQ::All();
            elseif (preg_match("/^'.*'$/", $strSuggestion))
                $objSearchCondition = QQ::Equal(QQN::NarroTextContext()->Text->NarroTextSuggestionAsText->SuggestionValue, substr($strSuggestion, 1, -1));
            else
                $objSearchCondition = QQ::Like(QQN::NarroTextContext()->Text->NarroTextSuggestionAsText->SuggestionValue,  '%' . $strSuggestion . '%');

            switch ($intFilter) {
                case NarroTextListForm::SHOW_UNTRANSLATED_TEXTS :
                    $objFilterCondition = QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 0);
                    break;
                case NarroTextListForm::SHOW_VALIDATED_TEXTS :
                    $objFilterCondition = QQ::IsNotNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                case NarroTextListForm::SHOW_TEXTS_THAT_REQUIRE_VALIDATION :
                    $objFilterCondition = QQ::AndCondition(QQ::IsNull(QQN::NarroTextContext()->ValidSuggestionId), QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 1));
                    break;
                default:
                    // no filters
                    $objFilterCondition = QQ::All();
            }

            $arrContext = NarroTextContext::QueryArray(QQ::AndCondition($objSearchCondition, $objFilterCondition, $objExtraCondition), array($objLimitInfo, $objSortInfo, QQ::GroupBy(QQN::NarroTextContext()->ContextId)));

            return $arrContext;
        }

        public static function CountBySuggestionValue($strSuggestion, $intFilter, $objExtraCondition = null) {
            if (!is_object($objExtraCondition))
                $objExtraCondition = QQ::All();
            if (trim($strSuggestion) == '')
                $objSearchCondition = QQ::All();
            elseif (preg_match("/^'.*'$/", $strSuggestion))
                $objSearchCondition = QQ::Equal(QQN::NarroTextContext()->Text->NarroTextSuggestionAsText->SuggestionValue, substr($strSuggestion, 1, -1));
            else
                $objSearchCondition = QQ::Like(QQN::NarroTextContext()->Text->NarroTextSuggestionAsText->SuggestionValue,  '%' . $strSuggestion . '%');

            switch ($intFilter) {
                case NarroTextListForm::SHOW_UNTRANSLATED_TEXTS :
                    $objFilterCondition = QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 0);
                    break;
                case NarroTextListForm::SHOW_VALIDATED_TEXTS :
                    $objFilterCondition = QQ::IsNotNull(QQN::NarroTextContext()->ValidSuggestionId);
                    break;
                case NarroTextListForm::SHOW_TEXTS_THAT_REQUIRE_VALIDATION :
                    $objFilterCondition = QQ::AndCondition(QQ::IsNull(QQN::NarroTextContext()->ValidSuggestionId), QQ::Equal(QQN::NarroTextContext()->HasSuggestion, 1));
                    break;
                default:
                    // no filters
                    $objFilterCondition = QQ::All();
            }

            $intContextCount = NarroTextContext::QueryCount(QQ::AndCondition($objSearchCondition, $objFilterCondition, $objExtraCondition), array(QQ::GroupBy(QQN::NarroTextContext()->ContextId)));

            return $intContextCount;
        }
    }
?>