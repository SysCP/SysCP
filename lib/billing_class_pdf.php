<?php

/**
 * Class PDF (billing_class_pdf.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * Set fpdf fontpath
 */

define('FPDF_FONTPATH', './lib/pdf/font/');

/**
 * Include the main fpdi class, which includes fpdf
 */

require ('./lib/pdf/fpdf.php');

/**
 * This class is an extension to FPDF/FPDI, providing a simple possibility to include the template on each page
 * @package   Billing
 */

class PDF extends FPDF
{
	/**
	 * print a page count footer?
	 * @var bool
	 */

	var $_pagecount_footer_enabled = false;

	/**
	 * The text which should be displayed for page count. First %s is current page, %s all counted pages.
	 * @var string
	 */

	var $_pagecount_footer_text = 'Page %s / %s';

	/**
	 * True if we should count pages at the moment, false otherwise.
	 * @var bool
	 */

	var $_count_pages = false;

	/**
	 * Current page count, can be different from the number FPDF counted if self::_count_pages is true
	 * @var int
	 */

	var $_my_page_count = 0;

	/**
	 * Wrapper for FPDF::__construct. It adds DIN fonts and sets Margins/AutoPageBreak/Font.
	 *
	 * @param string See FPDF::FPDF for this parameter, defaults to 'P'
	 * @param string See FPDF::FPDF for this parameter, defaults to 'mm'
	 * @param string See FPDF::FPDF for this parameter, defaults to 'A4'
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
	{
		parent::__construct($orientation, $unit, $format);
		$this->SetMargins(18, 25);
		$this->SetAutoPageBreak(true, 25);
		$this->SetFont('helvetica', '', '10');
	}

	/**
	 * Wrapper for FPDF::addPage. It uses a template for every page and counts pages if desired.
	 *
	 * @param string See FPDF::addPage for this parameter, defaults to 'P' when null
	 * @param string Template to use, defaults to './templates/invoice_template.pdf' when null
	 * @param bool   If we should count pages. Always uses last value when null.
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function addPage($orientation = null, $template = null, $count = null)
	{
		if($orientation == null)
		{
			$orientation = 'P';
		}

		parent::addPage($orientation);

		if($count !== null)
		{
			$this->_count_pages = $count;
		}

		if($this->_count_pages === true)
		{
			$this->_my_page_count++;
		}

		/*		if($template == null)
		{
			$template = './templates/invoice_template.pdf';
		}

		$pagecount = $this->setSourceFile($template);
		$tplidx = $this->ImportPage(1);
		$this->useTemplate($tplidx, 0, 0, false);*/
	}

	/**
	 * Wrapper for FPDF::Footer. It prints a page footer (page number/count)
	 * if $self::_pagecount_footer_enabled is true.
	 *
	 * @author Former03 GmbH :: Baltasar Cevc <baltasar.cevc@former03.de>
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function Footer()
	{
		if($this->_pagecount_footer_enabled)
		{
			$this->SetFont('', '', 9);
			$this->SetXY(0, -25);
			$this->Cell(220, 1, sprintf($this->_pagecount_footer_text, $this->PageNo(), '{nb}'), 0, 0, 'C');
		}
	}

	/**
	 * Wrapper for FPDF::Close. It's main purpose is to
	 * replace {nb} by our own count and not the number FPDF counted.
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function Close()
	{
		// taken from original fpdf-class

		$nb = $this->page;

		if(!empty($this->AliasNbPages))
		{
			//Replace number of pages

			for ($n = 1;$n <= $nb;$n++)$this->pages[$n] = str_replace($this->AliasNbPages, $this->_my_page_count, $this->pages[$n]);
		}

		parent::Close();
	}

	/**
	 * Returns the value stored in self::_pagecount_footer_enabled.
	 *
	 * @return bool The value stored in self::_pagecount_footer_enabled
	 *
	 * @author Former03 GmbH :: Baltasar Cevc <baltasar.cevc@former03.de>
	 */

	function getFooterEnabled()
	{
		return $this->_pagecount_footer_enabled;
	}

	/**
	 * Sets self::_pagecount_footer_enabled and self::_pagecount_footer_text
	 * according to the first/second parameters.
	 *
	 * @param bool   Should self::Footer include a pagecount footer?
	 * @param string The text to use in the pagecount footer, self::_pagecount_footer_text
	 *
	 * @author Former03 GmbH :: Baltasar Cevc <baltasar.cevc@former03.de>
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function setFooterEnabled($enable_footer, $footer_text = '')
	{
		if($this->_pagecount_footer_enabled = $enable_footer)
		{
			$this->AliasNbPages();
		}

		if($footer_text != '')
		{
			$this->_pagecount_footer_text = $footer_text;
		}
	}
}

?>
