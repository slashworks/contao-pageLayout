<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Stefan Melz 2012
 * @author     Stefan Melz <www.borowiakziehe.de>
 * @author     Joe Ray Gregory <www.borowiakziehe.de>
 * @package    sm_pageLayout
 * @license    LGPL
 * @filesource
 */

/**
 * Table tl_page
 */

/*
 * set field in palettes
 */
foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $type => $insert)
{
    if(!is_array($insert)) {
        $newPaletteString = str_replace('includeLayout','includeLayout,pagelayout_bz',$insert);
        $GLOBALS['TL_DCA']['tl_page']['palettes'][$type] = $newPaletteString;
    }
}


/*
 * add some Layout stuff
 */
if(!$GLOBALS['TL_DCA']['tl_page']['fields']['layout']['eval']['tl_class'])
{
    $GLOBALS['TL_DCA']['tl_page']['fields']['layout']['eval']['tl_class'] = 'w50';
}
if(!$GLOBALS['TL_DCA']['tl_page']['fields']['includeLayout']['eval']['tl_class'])
{
    $GLOBALS['TL_DCA']['tl_page']['fields']['includeLayout']['eval']['tl_class'] = 'w50';
}


/*
 * define Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['pagelayout_bz'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['pagelayout_bz']['layout'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'input_field_callback'    => array('tl_page_add','getPageLayout'),
    'eval'                    => array('mandatory'=>false,'tl_class'=>'w50','readonly'=>true),

);

/**
 * Class tl_page
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Stefan Melz 2012
 * @author     Stefan Melz <www.borowiakziehe.de>
 * @author     Joe Ray Gregory <www.borowiakziehe.de>
 * @package    sm_pageLayout
 */
class tl_page_add extends Backend
{

    public function getPageLayout($varValue)
    {
        $this->import('PageThemeSniffer');
        $output = $this->PageThemeSniffer->findThemeData($varValue);

        $_layout = $output->layout;
        $_theme = $output->theme;

        /*
         * build return string
         */
        $builder  = "<div class='w50 cbx'><div class='tl_checkbox_single_container'>";
        $builder .= "<label>".$GLOBALS['TL_LANG']['pagelayout_bz']['aktuellesLayout']." </label>";
        if($_theme->name)
        {
            $builder .= "<span class='themename'>".$_theme->name."-</span>";
        }
        if($_layout->name)
        {
            $builder .= "<a href='contao/main.php?do=themes&table=tl_layout&act=edit&id=".$_layout->id."'>".$_layout->name."</a>";
        }
        else
        {
            $builder .= $_layout->name;
        }
        $builder .= "</div></div>";

        return $builder;
    }

}