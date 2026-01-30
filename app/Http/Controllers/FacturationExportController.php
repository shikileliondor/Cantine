<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FacturationExportController extends Controller
{
    public function facturePdf(Facture $facture): Response
    {
        $payload = $this->buildFacturePayload($facture);
        $pdf = Pdf::loadView('facturation.export-pdf', $payload)->setPaper('a4');
        $filename = 'facture-' . $payload['numero'] . '.pdf';

        return $pdf->download($filename);
    }

    public function factureCsv(Facture $facture): StreamedResponse
    {
        $payload = $this->buildFacturePayload($facture);
        $filename = 'facture-' . $payload['numero'] . '.csv';

        return response()->streamDownload(function () use ($payload) {
            $handle = fopen('php://output', 'w');
            $delimiter = ';';

            fputcsv($handle, ['Facture', $payload['numero']], $delimiter);
            fputcsv($handle, ['Élève', $payload['eleve']], $delimiter);
            fputcsv($handle, ['Période', $payload['periode']], $delimiter);
            fputcsv($handle, ['Statut', $payload['statut']], $delimiter);
            fputcsv($handle, ['Montant brut', $payload['montant_brut']], $delimiter);
            fputcsv($handle, ['Total remises', $payload['total_remises']], $delimiter);
            fputcsv($handle, ['Net à payer', $payload['net_a_payer']], $delimiter);
            fputcsv($handle, ['Total versé', $payload['total_verse']], $delimiter);
            fputcsv($handle, ['Reste à payer', $payload['reste_a_payer']], $delimiter);
            fputcsv($handle, [''], $delimiter);

            fputcsv($handle, ['Versements'], $delimiter);
            fputcsv($handle, ['Date', 'Mode', 'Référence', 'Montant', 'Commentaire'], $delimiter);

            if ($payload['versements']->isEmpty()) {
                fputcsv($handle, ['Aucun versement enregistré'], $delimiter);
            } else {
                foreach ($payload['versements'] as $versement) {
                    fputcsv($handle, [
                        $versement['date'],
                        $versement['mode'],
                        $versement['reference'],
                        $versement['montant'],
                        $versement['commentaire'],
                    ], $delimiter);
                }
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array{
     *     numero: string,
     *     eleve: string,
     *     periode: string,
     *     statut: string,
     *     montant_brut: string,
     *     total_remises: string,
     *     net_a_payer: string,
     *     total_verse: string,
     *     reste_a_payer: string,
     *     versements: \Illuminate\Support\Collection<int, array{montant: string, date: string, mode: string, reference: string, commentaire: string}>
     * }
     */
    private function buildFacturePayload(Facture $facture): array
    {
        $facture->load(['eleve', 'paiements']);

        $eleve = $facture->eleve;
        $nomEleve = trim(($eleve?->prenom ?? '') . ' ' . ($eleve?->nom ?? ''));
        $nomEleve = $nomEleve !== '' ? $nomEleve : ($eleve?->nom ?? 'Élève');

        $periode = $facture->mois?->locale('fr')->translatedFormat('F Y') ?? '';
        $montantBrut = (int) round((float) $facture->montant_mensuel);
        $remise = (int) round((float) $facture->montant_remise);
        $net = max($montantBrut - $remise, 0);
        $totalVerse = (int) round((float) $facture->paiements->sum('montant'));
        $reste = max($net - $totalVerse, 0);

        $versements = $facture->paiements
            ->sortByDesc('date_paiement')
            ->values()
            ->map(function ($paiement) {
                return [
                    'montant' => number_format((float) $paiement->montant, 0, ',', ' ') . ' FCFA',
                    'date' => $paiement->date_paiement?->toDateString() ?? '—',
                    'mode' => $paiement->mode_paiement ? ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) : '—',
                    'reference' => $paiement->reference ?? '—',
                    'commentaire' => $paiement->reference ? '' : '—',
                ];
            });

        return [
            'numero' => (string) $facture->id,
            'eleve' => $nomEleve,
            'periode' => $periode !== '' ? $periode : ($facture->mois?->format('Y-m') ?? ''),
            'statut' => $facture->statut ?? '—',
            'montant_brut' => number_format($montantBrut, 0, ',', ' ') . ' FCFA',
            'total_remises' => number_format($remise, 0, ',', ' ') . ' FCFA',
            'net_a_payer' => number_format($net, 0, ',', ' ') . ' FCFA',
            'total_verse' => number_format($totalVerse, 0, ',', ' ') . ' FCFA',
            'reste_a_payer' => number_format($reste, 0, ',', ' ') . ' FCFA',
            'versements' => $versements,
        ];
    }
}
