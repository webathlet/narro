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

    $strPageTitle = t('Project language list');


    require(dirname(__FILE__) . '/configuration/header.inc.php')
?>

    <?php $this->RenderBegin() ?>
        <?php $this->pnlHeader->Render() ?>
        <?php $this->pnlBreadcrumb->Render() ?>
        <h3><?php echo sprintf(t('Language list for %s'), $this->objProject->ProjectName); ?></h3>
        <p><?php echo sprintf(t('This is a list of languages that %s is translated in.'), $this->objProject->ProjectName); ?></p>
        <br />

        <?php $this->dtgNarroLanguage->Render() ?>

    <?php $this->RenderEnd() ?>

<?php require(dirname(__FILE__) . '/configuration/footer.inc.php'); ?>
