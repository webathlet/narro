<?php
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the NarroContext class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single NarroContext object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a NarroContextMetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 * 
	 * @package Narro
	 * @subpackage MetaControls
	 * property-read NarroContext $NarroContext the actual NarroContext data class being edited
	 * property QLabel $ContextIdControl
	 * property-read QLabel $ContextIdLabel
	 * property QListBox $TextIdControl
	 * property-read QLabel $TextIdLabel
	 * property QListBox $ProjectIdControl
	 * property-read QLabel $ProjectIdLabel
	 * property QTextBox $ContextControl
	 * property-read QLabel $ContextLabel
	 * property QTextBox $ContextMd5Control
	 * property-read QLabel $ContextMd5Label
	 * property QListBox $FileIdControl
	 * property-read QLabel $FileIdLabel
	 * property QDateTimePicker $CreatedControl
	 * property-read QLabel $CreatedLabel
	 * property QDateTimePicker $ModifiedControl
	 * property-read QLabel $ModifiedLabel
	 * property QCheckBox $ActiveControl
	 * property-read QLabel $ActiveLabel
	 * property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
	 */

	class NarroContextMetaControlGen extends QBaseClass {
		// General Variables
		protected $objNarroContext;
		protected $objParentObject;
		protected $strTitleVerb;
		protected $blnEditMode;

		// Controls that allow the editing of NarroContext's individual data fields
		protected $lblContextId;
		protected $lstText;
		protected $lstProject;
		protected $txtContext;
		protected $txtContextMd5;
		protected $lstFile;
		protected $calCreated;
		protected $calModified;
		protected $chkActive;

		// Controls that allow the viewing of NarroContext's individual data fields
		protected $lblTextId;
		protected $lblProjectId;
		protected $lblContext;
		protected $lblContextMd5;
		protected $lblFileId;
		protected $lblCreated;
		protected $lblModified;
		protected $lblActive;

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References


		/**
		 * Main constructor.  Constructor OR static create methods are designed to be called in either
		 * a parent QPanel or the main QForm when wanting to create a
		 * NarroContextMetaControl to edit a single NarroContext object within the
		 * QPanel or QForm.
		 *
		 * This constructor takes in a single NarroContext object, while any of the static
		 * create methods below can be used to construct based off of individual PK ID(s).
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this NarroContextMetaControl
		 * @param NarroContext $objNarroContext new or existing NarroContext object
		 */
		 public function __construct($objParentObject, NarroContext $objNarroContext) {
			// Setup Parent Object (e.g. QForm or QPanel which will be using this NarroContextMetaControl)
			$this->objParentObject = $objParentObject;

			// Setup linked NarroContext object
			$this->objNarroContext = $objNarroContext;

			// Figure out if we're Editing or Creating New
			if ($this->objNarroContext->__Restored) {
				$this->strTitleVerb = QApplication::Translate('Edit');
				$this->blnEditMode = true;
			} else {
				$this->strTitleVerb = QApplication::Translate('Create');
				$this->blnEditMode = false;
			}
		 }

		/**
		 * Static Helper Method to Create using PK arguments
		 * You must pass in the PK arguments on an object to load, or leave it blank to create a new one.
		 * If you want to load via QueryString or PathInfo, use the CreateFromQueryString or CreateFromPathInfo
		 * static helper methods.  Finally, specify a CreateType to define whether or not we are only allowed to 
		 * edit, or if we are also allowed to create a new one, etc.
		 * 
		 * @param mixed $objParentObject QForm or QPanel which will be using this NarroContextMetaControl
		 * @param integer $intContextId primary key value
		 * @param QMetaControlCreateType $intCreateType rules governing NarroContext object creation - defaults to CreateOrEdit
 		 * @return NarroContextMetaControl
		 */
		public static function Create($objParentObject, $intContextId = null, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			// Attempt to Load from PK Arguments
			if (strlen($intContextId)) {
				$objNarroContext = NarroContext::Load($intContextId);

				// NarroContext was found -- return it!
				if ($objNarroContext)
					return new NarroContextMetaControl($objParentObject, $objNarroContext);

				// If CreateOnRecordNotFound not specified, throw an exception
				else if ($intCreateType != QMetaControlCreateType::CreateOnRecordNotFound)
					throw new QCallerException('Could not find a NarroContext object with PK arguments: ' . $intContextId);

			// If EditOnly is specified, throw an exception
			} else if ($intCreateType == QMetaControlCreateType::EditOnly)
				throw new QCallerException('No PK arguments specified');

			// If we are here, then we need to create a new record
			return new NarroContextMetaControl($objParentObject, new NarroContext());
		}

		/**
		 * Static Helper Method to Create using PathInfo arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this NarroContextMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing NarroContext object creation - defaults to CreateOrEdit
		 * @return NarroContextMetaControl
		 */
		public static function CreateFromPathInfo($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intContextId = QApplication::PathInfo(0);
			return NarroContextMetaControl::Create($objParentObject, $intContextId, $intCreateType);
		}

		/**
		 * Static Helper Method to Create using QueryString arguments
		 *
		 * @param mixed $objParentObject QForm or QPanel which will be using this NarroContextMetaControl
		 * @param QMetaControlCreateType $intCreateType rules governing NarroContext object creation - defaults to CreateOrEdit
		 * @return NarroContextMetaControl
		 */
		public static function CreateFromQueryString($objParentObject, $intCreateType = QMetaControlCreateType::CreateOrEdit) {
			$intContextId = QApplication::QueryString('intContextId');
			return NarroContextMetaControl::Create($objParentObject, $intContextId, $intCreateType);
		}



		///////////////////////////////////////////////
		// PUBLIC CREATE and REFRESH METHODS
		///////////////////////////////////////////////

		/**
		 * Create and setup QLabel lblContextId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblContextId_Create($strControlId = null) {
			$this->lblContextId = new QLabel($this->objParentObject, $strControlId);
			$this->lblContextId->Name = QApplication::Translate('Context Id');
			if ($this->blnEditMode)
				$this->lblContextId->Text = $this->objNarroContext->ContextId;
			else
				$this->lblContextId->Text = 'N/A';
			return $this->lblContextId;
		}

		/**
		 * Create and setup QListBox lstText
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstText_Create($strControlId = null) {
			$this->lstText = new QListBox($this->objParentObject, $strControlId);
			$this->lstText->Name = QApplication::Translate('Text');
			$this->lstText->Required = true;
			if (!$this->blnEditMode)
				$this->lstText->AddItem(QApplication::Translate('- Select One -'), null);
			$objTextArray = NarroText::LoadAll();
			if ($objTextArray) foreach ($objTextArray as $objText) {
				$objListItem = new QListItem($objText->__toString(), $objText->TextId);
				if (($this->objNarroContext->Text) && ($this->objNarroContext->Text->TextId == $objText->TextId))
					$objListItem->Selected = true;
				$this->lstText->AddItem($objListItem);
			}
			return $this->lstText;
		}

		/**
		 * Create and setup QLabel lblTextId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblTextId_Create($strControlId = null) {
			$this->lblTextId = new QLabel($this->objParentObject, $strControlId);
			$this->lblTextId->Name = QApplication::Translate('Text');
			$this->lblTextId->Text = ($this->objNarroContext->Text) ? $this->objNarroContext->Text->__toString() : null;
			$this->lblTextId->Required = true;
			return $this->lblTextId;
		}

		/**
		 * Create and setup QListBox lstProject
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstProject_Create($strControlId = null) {
			$this->lstProject = new QListBox($this->objParentObject, $strControlId);
			$this->lstProject->Name = QApplication::Translate('Project');
			$this->lstProject->Required = true;
			if (!$this->blnEditMode)
				$this->lstProject->AddItem(QApplication::Translate('- Select One -'), null);
			$objProjectArray = NarroProject::LoadAll();
			if ($objProjectArray) foreach ($objProjectArray as $objProject) {
				$objListItem = new QListItem($objProject->__toString(), $objProject->ProjectId);
				if (($this->objNarroContext->Project) && ($this->objNarroContext->Project->ProjectId == $objProject->ProjectId))
					$objListItem->Selected = true;
				$this->lstProject->AddItem($objListItem);
			}
			return $this->lstProject;
		}

		/**
		 * Create and setup QLabel lblProjectId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblProjectId_Create($strControlId = null) {
			$this->lblProjectId = new QLabel($this->objParentObject, $strControlId);
			$this->lblProjectId->Name = QApplication::Translate('Project');
			$this->lblProjectId->Text = ($this->objNarroContext->Project) ? $this->objNarroContext->Project->__toString() : null;
			$this->lblProjectId->Required = true;
			return $this->lblProjectId;
		}

		/**
		 * Create and setup QTextBox txtContext
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtContext_Create($strControlId = null) {
			$this->txtContext = new QTextBox($this->objParentObject, $strControlId);
			$this->txtContext->Name = QApplication::Translate('Context');
			$this->txtContext->Text = $this->objNarroContext->Context;
			$this->txtContext->Required = true;
			$this->txtContext->TextMode = QTextMode::MultiLine;
			return $this->txtContext;
		}

		/**
		 * Create and setup QLabel lblContext
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblContext_Create($strControlId = null) {
			$this->lblContext = new QLabel($this->objParentObject, $strControlId);
			$this->lblContext->Name = QApplication::Translate('Context');
			$this->lblContext->Text = $this->objNarroContext->Context;
			$this->lblContext->Required = true;
			return $this->lblContext;
		}

		/**
		 * Create and setup QTextBox txtContextMd5
		 * @param string $strControlId optional ControlId to use
		 * @return QTextBox
		 */
		public function txtContextMd5_Create($strControlId = null) {
			$this->txtContextMd5 = new QTextBox($this->objParentObject, $strControlId);
			$this->txtContextMd5->Name = QApplication::Translate('Context Md 5');
			$this->txtContextMd5->Text = $this->objNarroContext->ContextMd5;
			$this->txtContextMd5->Required = true;
			$this->txtContextMd5->MaxLength = NarroContext::ContextMd5MaxLength;
			return $this->txtContextMd5;
		}

		/**
		 * Create and setup QLabel lblContextMd5
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblContextMd5_Create($strControlId = null) {
			$this->lblContextMd5 = new QLabel($this->objParentObject, $strControlId);
			$this->lblContextMd5->Name = QApplication::Translate('Context Md 5');
			$this->lblContextMd5->Text = $this->objNarroContext->ContextMd5;
			$this->lblContextMd5->Required = true;
			return $this->lblContextMd5;
		}

		/**
		 * Create and setup QListBox lstFile
		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function lstFile_Create($strControlId = null) {
			$this->lstFile = new QListBox($this->objParentObject, $strControlId);
			$this->lstFile->Name = QApplication::Translate('File');
			$this->lstFile->Required = true;
			if (!$this->blnEditMode)
				$this->lstFile->AddItem(QApplication::Translate('- Select One -'), null);
			$objFileArray = NarroFile::LoadAll();
			if ($objFileArray) foreach ($objFileArray as $objFile) {
				$objListItem = new QListItem($objFile->__toString(), $objFile->FileId);
				if (($this->objNarroContext->File) && ($this->objNarroContext->File->FileId == $objFile->FileId))
					$objListItem->Selected = true;
				$this->lstFile->AddItem($objListItem);
			}
			return $this->lstFile;
		}

		/**
		 * Create and setup QLabel lblFileId
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblFileId_Create($strControlId = null) {
			$this->lblFileId = new QLabel($this->objParentObject, $strControlId);
			$this->lblFileId->Name = QApplication::Translate('File');
			$this->lblFileId->Text = ($this->objNarroContext->File) ? $this->objNarroContext->File->__toString() : null;
			$this->lblFileId->Required = true;
			return $this->lblFileId;
		}

		/**
		 * Create and setup QDateTimePicker calCreated
		 * @param string $strControlId optional ControlId to use
		 * @return QDateTimePicker
		 */
		public function calCreated_Create($strControlId = null) {
			$this->calCreated = new QDateTimePicker($this->objParentObject, $strControlId);
			$this->calCreated->Name = QApplication::Translate('Created');
			$this->calCreated->DateTime = $this->objNarroContext->Created;
			$this->calCreated->DateTimePickerType = QDateTimePickerType::DateTime;
			$this->calCreated->Required = true;
			return $this->calCreated;
		}

		/**
		 * Create and setup QLabel lblCreated
		 * @param string $strControlId optional ControlId to use
		 * @param string $strDateTimeFormat optional DateTimeFormat to use
		 * @return QLabel
		 */
		public function lblCreated_Create($strControlId = null, $strDateTimeFormat = null) {
			$this->lblCreated = new QLabel($this->objParentObject, $strControlId);
			$this->lblCreated->Name = QApplication::Translate('Created');
			$this->strCreatedDateTimeFormat = $strDateTimeFormat;
			$this->lblCreated->Text = sprintf($this->objNarroContext->Created) ? $this->objNarroContext->Created->qFormat($this->strCreatedDateTimeFormat) : null;
			$this->lblCreated->Required = true;
			return $this->lblCreated;
		}

		protected $strCreatedDateTimeFormat;


		/**
		 * Create and setup QDateTimePicker calModified
		 * @param string $strControlId optional ControlId to use
		 * @return QDateTimePicker
		 */
		public function calModified_Create($strControlId = null) {
			$this->calModified = new QDateTimePicker($this->objParentObject, $strControlId);
			$this->calModified->Name = QApplication::Translate('Modified');
			$this->calModified->DateTime = $this->objNarroContext->Modified;
			$this->calModified->DateTimePickerType = QDateTimePickerType::DateTime;
			return $this->calModified;
		}

		/**
		 * Create and setup QLabel lblModified
		 * @param string $strControlId optional ControlId to use
		 * @param string $strDateTimeFormat optional DateTimeFormat to use
		 * @return QLabel
		 */
		public function lblModified_Create($strControlId = null, $strDateTimeFormat = null) {
			$this->lblModified = new QLabel($this->objParentObject, $strControlId);
			$this->lblModified->Name = QApplication::Translate('Modified');
			$this->strModifiedDateTimeFormat = $strDateTimeFormat;
			$this->lblModified->Text = sprintf($this->objNarroContext->Modified) ? $this->objNarroContext->Modified->qFormat($this->strModifiedDateTimeFormat) : null;
			return $this->lblModified;
		}

		protected $strModifiedDateTimeFormat;


		/**
		 * Create and setup QCheckBox chkActive
		 * @param string $strControlId optional ControlId to use
		 * @return QCheckBox
		 */
		public function chkActive_Create($strControlId = null) {
			$this->chkActive = new QCheckBox($this->objParentObject, $strControlId);
			$this->chkActive->Name = QApplication::Translate('Active');
			$this->chkActive->Checked = $this->objNarroContext->Active;
			return $this->chkActive;
		}

		/**
		 * Create and setup QLabel lblActive
		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function lblActive_Create($strControlId = null) {
			$this->lblActive = new QLabel($this->objParentObject, $strControlId);
			$this->lblActive->Name = QApplication::Translate('Active');
			$this->lblActive->Text = ($this->objNarroContext->Active) ? QApplication::Translate('Yes') : QApplication::Translate('No');
			return $this->lblActive;
		}



		/**
		 * Refresh this MetaControl with Data from the local NarroContext object.
		 * @param boolean $blnReload reload NarroContext from the database
		 * @return void
		 */
		public function Refresh($blnReload = false) {
			if ($blnReload)
				$this->objNarroContext->Reload();

			if ($this->lblContextId) if ($this->blnEditMode) $this->lblContextId->Text = $this->objNarroContext->ContextId;

			if ($this->lstText) {
					$this->lstText->RemoveAllItems();
				if (!$this->blnEditMode)
					$this->lstText->AddItem(QApplication::Translate('- Select One -'), null);
				$objTextArray = NarroText::LoadAll();
				if ($objTextArray) foreach ($objTextArray as $objText) {
					$objListItem = new QListItem($objText->__toString(), $objText->TextId);
					if (($this->objNarroContext->Text) && ($this->objNarroContext->Text->TextId == $objText->TextId))
						$objListItem->Selected = true;
					$this->lstText->AddItem($objListItem);
				}
			}
			if ($this->lblTextId) $this->lblTextId->Text = ($this->objNarroContext->Text) ? $this->objNarroContext->Text->__toString() : null;

			if ($this->lstProject) {
					$this->lstProject->RemoveAllItems();
				if (!$this->blnEditMode)
					$this->lstProject->AddItem(QApplication::Translate('- Select One -'), null);
				$objProjectArray = NarroProject::LoadAll();
				if ($objProjectArray) foreach ($objProjectArray as $objProject) {
					$objListItem = new QListItem($objProject->__toString(), $objProject->ProjectId);
					if (($this->objNarroContext->Project) && ($this->objNarroContext->Project->ProjectId == $objProject->ProjectId))
						$objListItem->Selected = true;
					$this->lstProject->AddItem($objListItem);
				}
			}
			if ($this->lblProjectId) $this->lblProjectId->Text = ($this->objNarroContext->Project) ? $this->objNarroContext->Project->__toString() : null;

			if ($this->txtContext) $this->txtContext->Text = $this->objNarroContext->Context;
			if ($this->lblContext) $this->lblContext->Text = $this->objNarroContext->Context;

			if ($this->txtContextMd5) $this->txtContextMd5->Text = $this->objNarroContext->ContextMd5;
			if ($this->lblContextMd5) $this->lblContextMd5->Text = $this->objNarroContext->ContextMd5;

			if ($this->lstFile) {
					$this->lstFile->RemoveAllItems();
				if (!$this->blnEditMode)
					$this->lstFile->AddItem(QApplication::Translate('- Select One -'), null);
				$objFileArray = NarroFile::LoadAll();
				if ($objFileArray) foreach ($objFileArray as $objFile) {
					$objListItem = new QListItem($objFile->__toString(), $objFile->FileId);
					if (($this->objNarroContext->File) && ($this->objNarroContext->File->FileId == $objFile->FileId))
						$objListItem->Selected = true;
					$this->lstFile->AddItem($objListItem);
				}
			}
			if ($this->lblFileId) $this->lblFileId->Text = ($this->objNarroContext->File) ? $this->objNarroContext->File->__toString() : null;

			if ($this->calCreated) $this->calCreated->DateTime = $this->objNarroContext->Created;
			if ($this->lblCreated) $this->lblCreated->Text = sprintf($this->objNarroContext->Created) ? $this->objNarroContext->Created->qFormat($this->strCreatedDateTimeFormat) : null;

			if ($this->calModified) $this->calModified->DateTime = $this->objNarroContext->Modified;
			if ($this->lblModified) $this->lblModified->Text = sprintf($this->objNarroContext->Modified) ? $this->objNarroContext->Modified->qFormat($this->strModifiedDateTimeFormat) : null;

			if ($this->chkActive) $this->chkActive->Checked = $this->objNarroContext->Active;
			if ($this->lblActive) $this->lblActive->Text = ($this->objNarroContext->Active) ? QApplication::Translate('Yes') : QApplication::Translate('No');

		}



		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////





		///////////////////////////////////////////////
		// PUBLIC NARROCONTEXT OBJECT MANIPULATORS
		///////////////////////////////////////////////

		/**
		 * This will save this object's NarroContext instance,
		 * updating only the fields which have had a control created for it.
		 */
		public function SaveNarroContext() {
			try {
				// Update any fields for controls that have been created
				if ($this->lstText) $this->objNarroContext->TextId = $this->lstText->SelectedValue;
				if ($this->lstProject) $this->objNarroContext->ProjectId = $this->lstProject->SelectedValue;
				if ($this->txtContext) $this->objNarroContext->Context = $this->txtContext->Text;
				if ($this->txtContextMd5) $this->objNarroContext->ContextMd5 = $this->txtContextMd5->Text;
				if ($this->lstFile) $this->objNarroContext->FileId = $this->lstFile->SelectedValue;
				if ($this->calCreated) $this->objNarroContext->Created = $this->calCreated->DateTime;
				if ($this->calModified) $this->objNarroContext->Modified = $this->calModified->DateTime;
				if ($this->chkActive) $this->objNarroContext->Active = $this->chkActive->Checked;

				// Update any UniqueReverseReferences (if any) for controls that have been created for it

				// Save the NarroContext object
				$this->objNarroContext->Save();

				// Finally, update any ManyToManyReferences (if any)
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * This will DELETE this object's NarroContext instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function DeleteNarroContext() {
			$this->objNarroContext->Delete();
		}		



		///////////////////////////////////////////////
		// PUBLIC GETTERS and SETTERS
		///////////////////////////////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				// General MetaControlVariables
				case 'NarroContext': return $this->objNarroContext;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to NarroContext fields -- will be created dynamically if not yet created
				case 'ContextIdControl':
					if (!$this->lblContextId) return $this->lblContextId_Create();
					return $this->lblContextId;
				case 'ContextIdLabel':
					if (!$this->lblContextId) return $this->lblContextId_Create();
					return $this->lblContextId;
				case 'TextIdControl':
					if (!$this->lstText) return $this->lstText_Create();
					return $this->lstText;
				case 'TextIdLabel':
					if (!$this->lblTextId) return $this->lblTextId_Create();
					return $this->lblTextId;
				case 'ProjectIdControl':
					if (!$this->lstProject) return $this->lstProject_Create();
					return $this->lstProject;
				case 'ProjectIdLabel':
					if (!$this->lblProjectId) return $this->lblProjectId_Create();
					return $this->lblProjectId;
				case 'ContextControl':
					if (!$this->txtContext) return $this->txtContext_Create();
					return $this->txtContext;
				case 'ContextLabel':
					if (!$this->lblContext) return $this->lblContext_Create();
					return $this->lblContext;
				case 'ContextMd5Control':
					if (!$this->txtContextMd5) return $this->txtContextMd5_Create();
					return $this->txtContextMd5;
				case 'ContextMd5Label':
					if (!$this->lblContextMd5) return $this->lblContextMd5_Create();
					return $this->lblContextMd5;
				case 'FileIdControl':
					if (!$this->lstFile) return $this->lstFile_Create();
					return $this->lstFile;
				case 'FileIdLabel':
					if (!$this->lblFileId) return $this->lblFileId_Create();
					return $this->lblFileId;
				case 'CreatedControl':
					if (!$this->calCreated) return $this->calCreated_Create();
					return $this->calCreated;
				case 'CreatedLabel':
					if (!$this->lblCreated) return $this->lblCreated_Create();
					return $this->lblCreated;
				case 'ModifiedControl':
					if (!$this->calModified) return $this->calModified_Create();
					return $this->calModified;
				case 'ModifiedLabel':
					if (!$this->lblModified) return $this->lblModified_Create();
					return $this->lblModified;
				case 'ActiveControl':
					if (!$this->chkActive) return $this->chkActive_Create();
					return $this->chkActive;
				case 'ActiveLabel':
					if (!$this->lblActive) return $this->lblActive_Create();
					return $this->lblActive;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					// Controls that point to NarroContext fields
					case 'ContextIdControl':
						return ($this->lblContextId = QType::Cast($mixValue, 'QControl'));
					case 'TextIdControl':
						return ($this->lstText = QType::Cast($mixValue, 'QControl'));
					case 'ProjectIdControl':
						return ($this->lstProject = QType::Cast($mixValue, 'QControl'));
					case 'ContextControl':
						return ($this->txtContext = QType::Cast($mixValue, 'QControl'));
					case 'ContextMd5Control':
						return ($this->txtContextMd5 = QType::Cast($mixValue, 'QControl'));
					case 'FileIdControl':
						return ($this->lstFile = QType::Cast($mixValue, 'QControl'));
					case 'CreatedControl':
						return ($this->calCreated = QType::Cast($mixValue, 'QControl'));
					case 'ModifiedControl':
						return ($this->calModified = QType::Cast($mixValue, 'QControl'));
					case 'ActiveControl':
						return ($this->chkActive = QType::Cast($mixValue, 'QControl'));
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>