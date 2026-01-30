<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>Facture {{ $numero }}</title>
    <style>
      body {
        font-family: DejaVu Sans, sans-serif;
        color: #0f172a;
        font-size: 12px;
      }
      h1,
      h2,
      h3 {
        margin: 0;
      }
      .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #334155;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
      }
      .grid {
        display: table;
        width: 100%;
        margin-top: 16px;
      }
      .grid-item {
        display: table-cell;
        width: 33.33%;
        padding: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        vertical-align: top;
      }
      .grid-item strong {
        display: block;
        margin-top: 4px;
        font-size: 14px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
      }
      th,
      td {
        border: 1px solid #e2e8f0;
        padding: 8px;
        text-align: left;
      }
      th {
        background: #f8fafc;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
      }
      .muted {
        color: #64748b;
      }
    </style>
  </head>
  <body>
    <table style="width: 100%; margin-bottom: 16px;">
      <tr>
        <td style="border: none;">
          <h1>Facture #{{ $numero }}</h1>
          <p class="muted">Période : {{ $periode }}</p>
          <p class="muted">Élève : {{ $eleve }}</p>
        </td>
        <td style="border: none; text-align: right;">
          <span class="badge">{{ $statut }}</span>
        </td>
      </tr>
    </table>

    <div class="grid">
      <div class="grid-item">
        Montant brut
        <strong>{{ $montant_brut }}</strong>
      </div>
      <div class="grid-item">
        Total remises
        <strong>{{ $total_remises }}</strong>
      </div>
      <div class="grid-item">
        Net à payer
        <strong>{{ $net_a_payer }}</strong>
      </div>
    </div>

    <div class="grid">
      <div class="grid-item">
        Total versé
        <strong>{{ $total_verse }}</strong>
      </div>
      <div class="grid-item">
        Reste à payer
        <strong>{{ $reste_a_payer }}</strong>
      </div>
      <div class="grid-item">
        Statut
        <strong>{{ $statut }}</strong>
      </div>
    </div>

    <h2 style="margin-top: 24px;">Versements enregistrés</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Mode</th>
          <th>Référence</th>
          <th>Montant</th>
          <th>Commentaire</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($versements as $versement)
          <tr>
            <td>{{ $versement['date'] }}</td>
            <td>{{ $versement['mode'] }}</td>
            <td>{{ $versement['reference'] }}</td>
            <td>{{ $versement['montant'] }}</td>
            <td>{{ $versement['commentaire'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="muted">Aucun versement enregistré.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </body>
</html>
