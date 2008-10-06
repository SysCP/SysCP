<?php

/**
 * Class PDF Reminder (billing_class_pdfreminder.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class builds the reminder made out of the data in the given xml
 * @package   Billing
 */

class pdfReminder
{
    /**
     * The main SimpleXMLElement Object which holds the data of our invoice.
     * @var SimpleXMLElement
     */

    var $invoiceXml = false;

    /**
     * Class constructor of pdfReminder. Creates lokal pdf objects and sets
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
     * thogether with the given language array a pdf reminder.
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
        $this->pdf->addPage();

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
        $this->pdf->Cell(35, 4, html_entity_decode($lng['invoice']['reminder']), 0, 2, 'L');
        $this->pdf->Ln();

        // Write Date

        $this->pdf->SetFont('', '', 8);
        $this->pdf->SetXY(18, 96);
        $this->pdf->Cell(70, 4, sprintf(html_entity_decode($lng['invoice']['dateheader']), makeNicePresentableDate(date('Y-m-d'), $lng['panel']['dateformat_function'])), 0, 2, 'L');
        $this->pdf->Ln();

        // Write  invoice number

        $this->pdf->SetFont('', '', 9);
        $this->pdf->SetXY(18, 110);
        $this->pdf->Cell(0, 4, utf8_decode('Sehr geehrte Damen und Herren,'), 0, 0, 'L');
        $this->pdf->Ln(8);
        $this->pdf->MultiCell(0, 4, utf8_decode('bisher haben wir keinen Geldeingang zu unserer Rechnung ' . (string)$this->invoiceXml->invoice_number[0] . ' vom ' . (string)$this->invoiceXml->invoice_date[0] . ' feststellen können. Die Rechnung bezog sich auf:'), 0, 'L');
        $this->pdf->SetFont('', 'B', 9);
        $this->pdf->Ln(4);
        $this->pdf->SetX(23);
        $this->pdf->MultiCell(0, 4, utf8_decode((string)$this->invoiceXml->billing->contract_details[0]), 0, 'L');
        $this->pdf->SetFont('', '', 9);
        $this->pdf->Ln(4);
        $this->pdf->SetX(18);
        $this->pdf->Cell(0, 4, utf8_decode('Bitte überweisen Sie uns den fälligen '), 0, 0, 'L');
        $this->pdf->Ln(6);
        $this->pdf->SetX(23);
        $this->pdf->Cell(35, 4, utf8_decode('Gesamtbetrag von'), 0, 0, 'L');
        $this->pdf->SetFont('', 'B', 9);
        $this->pdf->Cell(150, 4, utf8_decode((string)$this->invoiceXml->total_fee_taxed[0]) . ' ' . chr(128), 0, 0, 'L');
        $this->pdf->SetFont('', '', 9);
        $this->pdf->Ln(6);
        $this->pdf->SetX(23);
        $this->pdf->Cell(35, 4, utf8_decode('bis spätestens zum'), 0, 0, 'L');
        $this->pdf->SetFont('', 'B', 9);
        $this->pdf->Cell(150, 4, makeNicePresentableDate(manipulateDate(time(), '+', (string)$this->invoiceXml->billing->term_of_payment[0], 'd'), $lng['panel']['dateformat_function']), 0, 0, 'L');
        $this->pdf->SetFont('', '', 9);
        $this->pdf->Ln(6);
        $this->pdf->SetX(18);
        $this->pdf->MultiCell(0, 4, utf8_decode('auf unser unten genanntes Konto. Wir möchten Sie freundlich darauf hinweisen, dass Sie mit der Zahlung in Verzug sind. Sollten Sie die Forderung nicht bis zum genannten Datum begleichen, werden wir nebst der Forderung noch Verzugszinsen geltend machen.'), 0, 'L');
    }

    /**
     * This method calls FPDF::Output to present reminder in browser
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function outputBrowser()
    {
        $this->pdf->Output('reminder.pdf', 'I');
    }
}

?>
