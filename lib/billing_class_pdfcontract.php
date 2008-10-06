<?php

/**
 * Class PDF Contract (billing_class_pdfcontract.php)
 *
 * @author    Former03 GmbH :: Florian Lippert <flo@syscp.org>
 * @package   Billing
 * @version   $Id$
 */

/**
 * This class provides a full contract made out of the contract data (given in an array)
 * @package   Billing
 */

class pdfContract
{
    /**
     * Class constructor of pdfContract. Creates lokal pdf objects and sets
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
     * This method processes the given contract array and creates
     * thogether with the given language array a pdf contract.
     *
     * @param array  A valid user array (a row with all fields from PANEL_ADMINS/PANEL_CUSTOMERS)
     * @param array  A valid language array
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function processData($contract, $lng)
    {
        $this->pdf->addPage(null, null, true);
        $this->pdf->setFooterEnabled(true, $lng['invoice']['page_footer']);
        $contract_date = makeNicePresentableDate($contract['contract_date'], $lng['panel']['dateformat_function']);
        $contactdetails = '';

        if($contract['company'] != '')
        {
            $contactdetails.= $contract['company'] . "\n";
        }

        if($contract['title'] != '')
        {
            $contactdetails.= $contract['title'] . ' ';
        }

        if($contract['firstname'] != ''
           && $contract['name'] != '')
        {
            $contactdetails.= $contract['firstname'] . ' ' . $contract['name'] . "\n";
        }

        $contactdetails.= $contract['street'] . "\n" . $contract['zipcode'] . ' ' . $contract['city'] . "\n" . $contract['country'];

        // Write Subject

        $this->pdf->SetFont('', 'B', 14);
        $this->pdf->SetXY(18, 32);
        $this->pdf->Cell(35, 4, utf8_decode('HOSTINGVERTRAG'), 0, 2, 'L');
        $this->pdf->Ln();

        // Write Date

        $this->pdf->SetFont('', '', 8);
        $this->pdf->SetXY(18, 25);
        $this->pdf->Cell(70, 4, sprintf(html_entity_decode($lng['invoice']['dateheader']), $contract_date), 0, 2, 'L');
        $this->pdf->Ln();

        // Write  invoice number

        $this->pdf->SetFont('', '', 10);
        $this->pdf->SetXY(18, 43);
        $this->pdf->Cell(0, 4, utf8_decode('zwischen der'), 0, 0, 'L');
        $this->pdf->Ln(8);
        $this->pdf->SetFont('', 'B', 10);
        $this->pdf->MultiCell(0, 4, utf8_decode('Provider GmbH' . "\n" . 'Strasse 1' . "\n" . '12345 Stadt' . "\n" . 'Land'), 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->SetFont('', '', 10);
        $this->pdf->Cell(0, 4, utf8_decode('im Folgenden "Provider" genannt'), 0, 0, 'L');
        $this->pdf->SetFont('', '', 10);
        $this->pdf->SetXY(18, 80);
        $this->pdf->Cell(0, 4, utf8_decode('und'), 0, 0, 'L');
        $this->pdf->Ln(8);
        $this->pdf->SetFont('', 'B', 10);
        $this->pdf->MultiCell(0, 4, $contactdetails, 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->SetFont('', '', 10);
        $this->pdf->Cell(0, 4, utf8_decode('im Folgenden "Kunde" genannt'), 0, 0, 'L');
        $this->pdf->SetFont('', 'B', 14);
        $this->pdf->SetXY(18, 130);
        $this->pdf->Cell(35, 4, utf8_decode('HOSTINGVERTRAG'), 0, 2, 'L');
        $this->pdf->Ln();
        $this->pdf->SetFont('', 'B', 10);
        $this->pdf->Cell(8, 4, utf8_decode('§ 1'), 0, 0, 'L');
        $this->pdf->Cell(0, 4, utf8_decode('KONDITIONEN'), 0, 0, 'L');
        $this->pdf->SetFont('', '', 10);
        $this->pdf->Ln(6);
        $this->pdf->Cell(8, 4, utf8_decode('(1)'), 0, 0, 'L');
        $this->pdf->MultiCell(0, 4, utf8_decode('Grundkonditionen'), 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('Domains (.' . implode(', .', explode(' ', $contract['included_domains_tld'])) . ')'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode($contract['included_domains_qty']), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('Subdomains'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode($contract['subdomains'] == '-1' ? html_entity_decode($lng['customer']['unlimited']) : $contract['subdomains']), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('Webspace'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode((($contract['diskspace']/1024) == '-1' ? html_entity_decode($lng['customer']['unlimited']) : ($contract['diskspace']/1024)) . ' MB'), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('Frei-Traffic'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode((($contract['traffic']/(1024*1024)) == '-1' ? html_entity_decode($lng['customer']['unlimited']) : ($contract['traffic']/(1024*1024))) . ' GB / Monat'), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('FTP-Benutzer (Mehrfach Login möglich)'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode($contract['ftps'] == '-1' ? html_entity_decode($lng['customer']['unlimited']) : $contract['ftps']), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('Datenbanken (MySQL)'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode($contract['mysqls'] == '-1' ? html_entity_decode($lng['customer']['unlimited']) : $contract['mysqls']), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('E-Mail-Adressen'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode($contract['emails'] == '-1' ? html_entity_decode($lng['customer']['unlimited']) : $contract['emails']), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetX(26);
        $this->pdf->Cell(80, 4, utf8_decode('POP3-Emailkonten'), 0, 0, 'L');
        $this->pdf->Cell(20, 4, utf8_decode($contract['email_accounts'] == '-1' ? html_entity_decode($lng['customer']['unlimited']) : $contract['email_accounts']), 0, 0, 'R');
        $this->pdf->Ln(6);
        $this->pdf->SetFont('', 'B', 10);
        $this->pdf->Cell(8, 4, utf8_decode('§ 2'), 0, 0, 'L');
        $this->pdf->Cell(0, 4, utf8_decode('KOSTEN'), 0, 0, 'L');
        $this->pdf->SetFont('', '', 10);
        $this->pdf->Ln(6);
        $this->pdf->Cell(8, 4, utf8_decode('(1)'), 0, 0, 'L');
        $this->pdf->MultiCell(0, 4, utf8_decode('Die monatlichen Kosten belaufen sich auf ' . $contract['interval_fee'] . ' ' . chr(128) . '.'), 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->Cell(8, 4, utf8_decode('(2)'), 0, 0, 'L');
        $this->pdf->MultiCell(0, 4, utf8_decode('Die Setup-Gebühr beträgt ' . $contract['setup_fee'] . ' ' . chr(128) . '.'), 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->Cell(8, 4, utf8_decode('(3)'), 0, 0, 'L');
        $this->pdf->MultiCell(0, 4, utf8_decode('Bei Überschreitung des Frei-Traffics koste' . (($contract['additional_traffic_unit']/(1024*1024)) == '1' ? 't jedes' : 'n ' . ($contract['additional_traffic_unit']/(1024*1024))) . ' weitere angefangene Gigabyte ' . $contract['additional_traffic_fee'] . ' ' . chr(128) . '.'), 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->Cell(8, 4, utf8_decode('(4)'), 0, 0, 'L');
        $this->pdf->MultiCell(0, 4, utf8_decode('Bei Überschreitung des Webspace koste' . (($contract['additional_diskspace_unit']/1024) == '1' ? 't jedes' : 'n ' . ($contract['additional_diskspace_unit']/1024)) . ' weitere angefangene Megabyte ' . $contract['additional_diskspace_fee'] . ' ' . chr(128) . '.'), 0, 'L');
        $this->pdf->Ln(3);
        $this->pdf->Cell(8, 4, utf8_decode('(5)'), 0, 0, 'L');
        $this->pdf->MultiCell(0, 4, utf8_decode('Alle Preise rein netto in Euro zuzüglich der gültigen gesetzlichen Umsatzsteuer.'), 0, 'L');
        $this->pdf->Ln(10);
        $this->pdf->Cell(70, 4, sprintf(html_entity_decode($lng['invoice']['dateheader']), $contract_date), 0, 0, 'L');
        $this->pdf->SetLineWidth(.5);
        $this->pdf->Ln(20);
        $this->pdf->Cell(75, 4, utf8_decode('Unterschrift Kunde'), 'T', 0, 'L');
        $this->pdf->SetX(110);
        $this->pdf->Cell(75, 4, utf8_decode('Unterschrift Provider'), 'T', 0, 'L');
        $this->pdf->SetY(-0.1);
    }

    /**
     * This method calls FPDF::Output to present contract in browser
     *
     * @author Former03 GmbH :: Florian Lippert <flo@syscp.org>
     */

    function outputBrowser()
    {
        $this->pdf->Output('contract.pdf', 'I');
    }
}

?>
