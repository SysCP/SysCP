<?php

/**
 * Class PDF Invoice (billing_class_pdfinvoice.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class builds the invoice made out of the data in the given xml
 * @package   Billing
 */

class pdfInvoice
{
	/**
	 * The main SimpleXMLElement Object which holds the data of our invoice.
	 * @var SimpleXMLElement
	 */

	var $invoiceXml = false;

	/**
	 * If this is a cancellation invoice
	 * @var bool
	 */

	var $cancellation = false;

	/**
	 * Class constructor of pdfInvoice. Creates lokal pdf objects and sets
	 * fonts, margins, pagesbreaks etc.
	 *
	 * @param db     Reference to database handler
	 * @param int    For admin mode set 1, otherwise 0
	 * @param string The name of the service
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function __construct()
	{
		$this->pdf = new PDF('P', 'mm', 'A4');
	}

	/**
	 * This method processes the given xml string and creates
	 * thogether with the given language array a pdf invoice.
	 *
	 * @param string The xml string which contains our invoice data
	 * @param array  A valid language array
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function processData($xml, $lng)
	{
		$this->invoiceXml = new SimpleXMLElement($xml);
		$contactdetails = '';

		if(utf8_decode((string)$this->invoiceXml->address->company[0]) != '')
		{
			$contactdetails.= utf8_decode((string)$this->invoiceXml->address->company[0]) . "\n";
		}

		if(utf8_decode((string)$this->invoiceXml->address->title[0]) != '')
		{
			$contactdetails.= utf8_decode((string)$this->invoiceXml->address->title[0]) . ' ';
		}

		if(utf8_decode((string)$this->invoiceXml->address->firstname[0]) != ''
		   && utf8_decode((string)$this->invoiceXml->address->name[0]) != '')
		{
			$contactdetails.= utf8_decode((string)$this->invoiceXml->address->firstname[0]) . ' ' . utf8_decode((string)$this->invoiceXml->address->name[0]) . "\n";
		}

		$contactdetails.= utf8_decode((string)$this->invoiceXml->address->street[0]) . "\n" . utf8_decode((string)$this->invoiceXml->address->zipcode[0]) . ' ' . utf8_decode((string)$this->invoiceXml->address->city[0]) . "\n" . utf8_decode((string)$this->invoiceXml->address->country[0]);
		$this->pdf->addPage(null, null, true);
		$this->pdf->setFooterEnabled(true, $lng['invoice']['page_footer']);

		// Write Sender

		$this->pdf->SetTextColor(160, 160, 160);
		$this->pdf->SetFont('', 'B', 6);
		$this->pdf->SetXY(17.5, 50.5);
		$this->pdf->Cell(0, 0, html_entity_decode($lng['invoice']['sender']));
		$this->pdf->SetTextColor(0, 0, 0);
		$this->pdf->Ln();

		// Write Address

		$this->pdf->SetFont('', '', 10);
		$this->pdf->SetXY(18, 60);
		$this->pdf->MultiCell(70, 4, $contactdetails, 0, 'L');
		$this->pdf->Ln();

		// Write Subject

		$this->pdf->SetFont('', 'B', 13);
		$this->pdf->SetXY(18, 100);
		$this->pdf->Cell(35, 4, html_entity_decode(($this->cancellation === true ? $lng['invoice']['cancellation'] : $lng['invoice']['invoice'])), 0, 2, 'L');
		$this->pdf->Ln();

		// Write Date

		$this->pdf->SetFont('', '', 8);
		$this->pdf->SetXY(18, 96);
		$this->pdf->Cell(70, 4, sprintf(html_entity_decode($lng['invoice']['dateheader']), ($this->cancellation === true ? makeNicePresentableDate(date('Y-m-d'), $lng['panel']['dateformat_function']) : utf8_decode((string)$this->invoiceXml->invoice_date[0]))), 0, 2, 'L');
		$this->pdf->Ln();

		// Write  invoice number

		$this->pdf->SetFont('', '', 9);
		$this->pdf->SetXY(18, 110);
		$this->pdf->Cell(35, 4, html_entity_decode($lng['invoice']['number']), 0, 0, 'L');
		$this->pdf->SetFont('', 'B', 9);
		$this->pdf->Cell(150, 4, utf8_decode((string)$this->invoiceXml->invoice_number[0]), 0, 0, 'L');
		$this->pdf->Ln();

		// Write contract number

		$this->pdf->SetFont('', '', 9);
		$this->pdf->Cell(35, 4, html_entity_decode($lng['invoice']['contract_number']), 0, 0, 'L');
		$this->pdf->SetFont('', 'B', 9);
		$this->pdf->Cell(150, 4, utf8_decode((string)$this->invoiceXml->billing->contract_number[0]), 0, 0, 'L');
		$this->pdf->Ln();

		// Write contract details

		$this->pdf->SetFont('', '', 9);
		$this->pdf->Cell(35, 4, html_entity_decode($lng['invoice']['contract_details']), 0, 0, 'L');
		$this->pdf->SetFont('', 'B', 9);
		$this->pdf->MultiCell(140, 4, utf8_decode((string)$this->invoiceXml->billing->contract_details[0]), 0, 'L');

		//$this->pdf->Ln();
		// Write invoice period

		$this->pdf->SetFont('', '', 9);
		$this->pdf->Cell(35, 4, html_entity_decode($lng['invoice']['period']), 0, 0, 'L');
		$this->pdf->SetFont('', 'B', 9);
		$this->pdf->Cell(150, 4, utf8_decode((string)$this->invoiceXml->invoice_period[0]), 0, 0, 'L');
		$this->pdf->Ln();
		$this->pdf->Ln();
		$invoice_items_table_header = html_entity_decode_array($lng['invoice']['header']);
		$invoice_items_table_column_width = array(
			8,
			84,
			32,
			15,
			12,
			8,
			15
		);
		$invoice_items_table_column_align = array(
			'L',
			'L',
			'R',
			'R',
			'R',
			'R',
			'R'
		);
		$this->pdf->SetFillColor(255, 255, 255);
		$this->pdf->SetTextColor(0);
		$this->pdf->SetDrawColor(0, 0, 0);
		$this->pdf->SetLineWidth(.2);
		$this->pdf->SetFont('', '', 7);
		$lineheight = 4;
		foreach($invoice_items_table_header as $i => $caption)
		{
			$this->pdf->Cell($invoice_items_table_column_width[$i], 7, utf8_decode($caption), 'B', 0, $invoice_items_table_column_align[$i], 1);
		}

		$this->pdf->Ln();
		$this->pdf->Ln(1);
		$i = 1;
		foreach($this->invoiceXml->service_category as $service_details)
		{
			$this->pdf->SetFont('', '', 9);
			$this->pdf->Cell($invoice_items_table_column_width[0]+$invoice_items_table_column_width[1], 5, utf8_decode((string)$service_details->caption), 0, 0, 'L', 0);
			$this->pdf->SetFont('', '', 7);
			$this->pdf->Cell($invoice_items_table_column_width[2], 5, utf8_decode((string)$service_details->interval), 0, 0, $invoice_items_table_column_align[2], 0);
			$this->pdf->Ln();
			foreach($service_details->invoice_row as $invoice_row)
			{
				$this->pdf->Cell($invoice_items_table_column_width[0], $lineheight, sprintf("%03d", $i), 0, 0, $invoice_items_table_column_align[0]);
				$this->pdf->Cell($invoice_items_table_column_width[1], $lineheight, utf8_decode(((string)$invoice_row->quantity[0] != '1' ? (string)$invoice_row->quantity[0] . ' x ' : '') . (string)$invoice_row->caption[0]), 0, 0, $invoice_items_table_column_align[1]);
				$this->pdf->Cell($invoice_items_table_column_width[2], $lineheight, utf8_decode((string)$invoice_row->interval[0]), 0, 0, $invoice_items_table_column_align[2]);
				$this->pdf->Cell($invoice_items_table_column_width[3], $lineheight, utf8_decode((string)$invoice_row->total_fee[0]), 0, 0, $invoice_items_table_column_align[3]);
				$this->pdf->Cell($invoice_items_table_column_width[4], $lineheight, utf8_decode((string)$invoice_row->tax[0]), 0, 0, $invoice_items_table_column_align[4]);
				$this->pdf->Cell($invoice_items_table_column_width[5], $lineheight, sprintf("%01.2f", 100*(float)utf8_decode((string)$invoice_row->taxrate[0])), 0, 0, $invoice_items_table_column_align[5]);
				$this->pdf->Cell($invoice_items_table_column_width[6], $lineheight, utf8_decode((string)$invoice_row->total_fee_taxed[0]), 0, 0, $invoice_items_table_column_align[6]);
				$this->pdf->Ln();
				$i++;
			}
		}

		$this->pdf->Ln(1);
		$this->pdf->Cell(array_sum($invoice_items_table_column_width), 0, '', 'T');
		$this->pdf->Ln();
		$this->pdf->Ln(1);
		$this->pdf->Cell($invoice_items_table_column_width[0]+$invoice_items_table_column_width[1]+$invoice_items_table_column_width[2], $lineheight);
		$this->pdf->Cell($invoice_items_table_column_width[3]+$invoice_items_table_column_width[4]+$invoice_items_table_column_width[5], $lineheight, html_entity_decode($lng['invoice']['subtotal']), 0, 0, 'R');
		$this->pdf->Cell($invoice_items_table_column_width[6], $lineheight, utf8_decode((string)$this->invoiceXml->total_fee[0]), 0, 0, 'R');
		$this->pdf->Ln();
		foreach($this->invoiceXml->tax as $tax)
		{
			$this->pdf->Cell($invoice_items_table_column_width[0]+$invoice_items_table_column_width[1]+$invoice_items_table_column_width[2]+$invoice_items_table_column_width[3]+$invoice_items_table_column_width[4]+$invoice_items_table_column_width[5], $lineheight, sprintf(html_entity_decode($lng['invoice']['tax']), (string)((double)utf8_decode((string)$tax['taxrate'])*100)), 0, 0, 'R');
			$this->pdf->Cell($invoice_items_table_column_width[6], $lineheight, utf8_decode((string)$tax), 0, 0, $invoice_items_table_column_align[6]);
			$this->pdf->Ln();
		}

		if((double)((string)$this->invoiceXml->credit_note[0]) != 0)
		{
			$this->pdf->Cell($invoice_items_table_column_width[0]+$invoice_items_table_column_width[1]+$invoice_items_table_column_width[2], $lineheight);
			$this->pdf->Cell($invoice_items_table_column_width[3]+$invoice_items_table_column_width[4]+$invoice_items_table_column_width[5], $lineheight, html_entity_decode($lng['invoice']['credit_note']), 0, 0, 'R');
			$this->pdf->Cell($invoice_items_table_column_width[6], $lineheight, '- ' . utf8_decode((string)$this->invoiceXml->credit_note[0]), 0, 0, 'R');
			$this->pdf->Ln();
		}

		$this->pdf->SetLineWidth(.5);
		$this->pdf->SetFont('', 'B', 9);
		$this->pdf->Cell($invoice_items_table_column_width[0]+$invoice_items_table_column_width[1]+$invoice_items_table_column_width[2], $lineheight);
		$this->pdf->Cell($invoice_items_table_column_width[3]+$invoice_items_table_column_width[4]+$invoice_items_table_column_width[5], $lineheight, html_entity_decode($lng['invoice']['total']), 'B', 0, 'R');
		$this->pdf->Cell($invoice_items_table_column_width[6], $lineheight, utf8_decode((string)$this->invoiceXml->total_fee_taxed[0]), 'B', 0, 'R');
		$this->pdf->Ln();
		$this->pdf->Ln(8);
		$this->pdf->SetFont('', '', '10');

		if((string)$this->invoiceXml->billing->taxid[0] != '')
		{
			$this->pdf->MultiCell(0, 5, html_entity_decode(sprintf($lng['invoice']['tax_text']['line'], (utf8_decode((string)$this->invoiceXml->address->company[0]) == '' ? $lng['invoice']['tax_text']['client'] : utf8_decode((string)$this->invoiceXml->address->company[0])), (string)$this->invoiceXml->billing->taxid[0])), 0, 'L');
			$this->pdf->Ln(8);
		}

		$this->pdf->MultiCell(0, 5, html_entity_decode(sprintf($lng['invoice']['payment_methods'][(int)(string)$this->invoiceXml->billing->payment_method[0]], utf8_decode((string)$this->invoiceXml->billing->term_of_payment[0]), utf8_decode((string)$this->invoiceXml->billing->bankaccount_bank[0]), utf8_decode((string)$this->invoiceXml->billing->bankaccount_number[0]), utf8_decode((string)$this->invoiceXml->billing->bankaccount_blz[0]))), 0, 'L');
		$this->pdf->Ln();
	}

	/**
	 * This method calls FPDF::Output to present invoice in browser
	 *
	 * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
	 */

	function outputBrowser()
	{
		$this->pdf->Output('invoice.pdf', 'I');
	}
}

?>
