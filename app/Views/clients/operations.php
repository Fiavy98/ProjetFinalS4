<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations client</title>
    <link rel="stylesheet" href="/assets/client.css">
</head>
<body class="client-page">
    <div class="page-shell">
        <?php $feeRulesJson = json_encode($feeRules ?? [], JSON_UNESCAPED_UNICODE); ?>
        <header class="brandbar">
            <div class="brand">
                <div class="brand-mark">MM</div>
                <div>
                    <div>Bonjour <?= esc($client['nom']) ?></div>
                    <small class="muted">Compte <?= esc($client['num']) ?> · Solde <?= number_format((float) $client['solde'], 2, ',', ' ') ?></small>
                </div>
            </div>
            <nav class="nav-links">
                <a class="chip" href="/client/solde">Voir solde</a>
                <a class="chip" href="/client/historique">Historique</a>
                <a class="chip" href="/client/logout">Deconnexion</a>
            </nav>
        </header>

        <?php if (! empty($message)) : ?>
            <div class="alert alert-success"><?= esc($message) ?></div>
        <?php endif; ?>

        <?php if (! empty($error)) : ?>
            <div class="alert alert-error"><?= esc($error) ?></div>
        <?php endif; ?>

        <section class="section-grid">
            <div class="form-card">
                <h2>Faire un depot</h2>
                <p class="muted">Ajouter de l'argent à votre compte Mobile Money.</p>
                <form method="post" action="/client/depot" class="form-grid">
                    <div>
                        <label for="depot_valeur">Montant</label>
                        <input id="depot_valeur" name="valeur" type="number" step="0.01" min="0.01" placeholder="10000" required>
                    </div>
                    <button class="btn" type="submit">Deposer</button>
                </form>
            </div>

            <div class="form-card">
                <h2>Faire un retrait</h2>
                <p class="muted">Retirer un montant depuis votre solde disponible.</p>
                <form method="post" action="/client/retrait" class="form-grid">
                    <div>
                        <label for="retrait_valeur">Montant</label>
                        <input id="retrait_valeur" name="valeur" type="number" step="0.01" min="0.01" placeholder="5000" required>
                    </div>
                    <button class="btn" type="submit">Retirer</button>
                </form>
            </div>

            <div class="form-card">
                <h2>Faire un transfert</h2>
                <p class="muted">Envoyer de l'argent vers un autre compte enregistré.</p>
                <form method="post" action="/client/transfert" class="form-grid">
                    <div>
                        <label for="dest_num">Numero destinataire</label>
                        <input id="dest_num" name="dest_num" type="text" placeholder="0339876543" required>
                    </div>

                    <div>
                        <label for="transfert_valeur">Montant</label>
                        <input id="transfert_valeur" name="valeur" type="number" step="0.01" min="0.01" placeholder="25000" required>
                    </div>

                    <div>
                        <label for="with_fee">Frais</label>
                        <select id="with_fee" name="with_fee">
                            <option value="0">Sans frais</option>
                            <option value="1">Avec frais</option>
                        </select>
                    </div>

                    <div class="alert alert-success" data-single-fee-preview style="display:none; margin-top: 4px;">
                        <strong>Frais estimés :</strong> <span></span>
                    </div>

                    <button class="btn" type="submit">Transferrer</button>
                </form>
            </div>
            <div class="form-card">
                <h2>Faire un transfert multiple</h2>
                <p class="muted">Ajoutez plusieurs destinataires du même opérateur. Le montant total sera divisé équitablement.</p>
                
                <form method="post" action="/client/transfertMultiple" class="form-grid">
                    <!-- Conteneur des champs de numéros dynamiques -->
                    <div id="destinatarires-container">
                        <label>Numéros destinataires</label>
                        <div class="destination-input-group" style="display: table; width: 100%; margin-bottom: 8px;">
                            <div style="display: table-cell; width: 100%;">
                                <input name="dest_nums[]" type="text" placeholder="Ex: 0341234567" class="dest-num-field" required>
                            </div>
                            <!-- On ne peut pas supprimer le premier champ obligatoire -->
                            <div style="display: table-cell; padding-left: 8px; vertical-align: middle;">
                                <button type="button" class="btn-secondary" style="padding: 10px; opacity: 0.5; cursor: not-allowed;" disabled>×</button>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton pour ajouter un champ -->
                    <div style="margin-bottom: 16px;">
                        <button type="button" id="add-dest-btn" class="btn-secondary" style="padding: 6px 12px; font-size: 0.85em;">
                            + Ajouter un destinataire
                        </button>
                    </div>

                    <div>
                        <label for="transfert_valeur_multiple">Montant Total à diviser</label>
                        <input id="transfert_valeur_multiple" name="valeur" type="number" step="0.01" min="0.01" placeholder="30000" required>
                    </div>

                    <div>
                        <label for="with_fee_multiple">Frais</label>
                        <select id="with_fee_multiple" name="with_fee">
                            <option value="0">Sans frais</option>
                            <option value="1">Avec frais</option>
                        </select>
                    </div>

                    <div class="alert alert-success" data-multiple-fee-preview style="display:none; margin-top: 10px; font-size: 0.95em;">
                        <strong>Calcul estimé :</strong> <span></span>
                    </div>

                    <button class="btn" type="submit">Transférer à tous</button>
                </form>
            </div>

        </section>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const feeRules = <?= $feeRulesJson ?: '[]' ?>;
    const singleAmountInput = document.getElementById('transfert_valeur');
    const singleFeeSelect = document.getElementById('with_fee');
    const singleFeePreview = document.querySelector('[data-single-fee-preview]');

    const container = document.getElementById('destinatarires-container');
    const addBtn = document.getElementById('add-dest-btn');
    const totalValueInput = document.getElementById('transfert_valeur_multiple');
    const multipleFeeSelect = document.getElementById('with_fee_multiple');
    const multipleFeePreview = document.querySelector('[data-multiple-fee-preview]');

    function findTransferFee(amount) {
        const numericAmount = Number(amount);
        if (!Number.isFinite(numericAmount) || numericAmount <= 0) {
            return 0;
        }

        const rule = feeRules.find(function (entry) {
            return String(entry.type_label || '').toLowerCase() === 'transfert'
                && numericAmount >= Number(entry.min)
                && numericAmount <= Number(entry.max);
        });

        return rule ? Number(rule.valeur) : 0;
    }

    function updateSingleFeePreview() {
        if (!singleFeePreview) {
            return;
        }

        const amount = Number(singleAmountInput.value);
        const withFee = singleFeeSelect.value === '1';

        if (!Number.isFinite(amount) || amount <= 0) {
            singleFeePreview.style.display = 'none';
            return;
        }

        const fee = withFee ? findTransferFee(amount) : 0;
        const totalDebit = amount + fee;
        singleFeePreview.style.display = 'block';
        singleFeePreview.querySelector('span').textContent = withFee
            ? `frais ${fee.toFixed(2)} BGA, total débité ${totalDebit.toFixed(2)} BGA`
            : 'aucun frais, total débité identique au montant saisi';
    }

    function calculateDynamicSplit() {
        const fields = container.querySelectorAll('.dest-num-field');
        const totalAmount = parseFloat(totalValueInput.value);

        let count = 0;
        fields.forEach(function (field) {
            if (field.value.trim().length > 0) {
                count++;
            }
        });

        if (!multipleFeePreview || count <= 0 || Number.isNaN(totalAmount) || totalAmount <= 0) {
            if (multipleFeePreview) {
                multipleFeePreview.style.display = 'none';
            }
            return;
        }

        const amountPerPerson = totalAmount / count;
        const withFee = multipleFeeSelect.value === '1';
        const feePerTransfer = withFee ? findTransferFee(amountPerPerson) : 0;
        const totalFee = feePerTransfer * count;
        const totalDebit = totalAmount + totalFee;

        multipleFeePreview.style.display = 'block';
        multipleFeePreview.querySelector('span').textContent = `${count} destinataire(s), ${amountPerPerson.toFixed(2)} BGA chacun, frais total ${totalFee.toFixed(2)} BGA, débit total ${totalDebit.toFixed(2)} BGA.`;
    }

    addBtn.addEventListener('click', function () {
        const newGroup = document.createElement('div');
        newGroup.className = 'destination-input-group';
        newGroup.style.display = 'table';
        newGroup.style.width = '100%';
        newGroup.style.marginBottom = '8px';

        newGroup.innerHTML = `
            <div style="display: table-cell; width: 100%;">
                <input name="dest_nums[]" type="text" placeholder="Ex: 0341234567" class="dest-num-field" required>
            </div>
            <div style="display: table-cell; padding-left: 8px; vertical-align: middle;">
                <button type="button" class="btn-secondary remove-dest-btn" style="padding: 10px; background-color: #dc3545; color: white; border: none;">×</button>
            </div>
        `;

        container.appendChild(newGroup);

        newGroup.querySelector('.dest-num-field').addEventListener('input', calculateDynamicSplit);
        newGroup.querySelector('.remove-dest-btn').addEventListener('click', function () {
            newGroup.remove();
            calculateDynamicSplit();
        });

        calculateDynamicSplit();
    });

    container.querySelector('.dest-num-field').addEventListener('input', calculateDynamicSplit);
    totalValueInput.addEventListener('input', calculateDynamicSplit);
    singleAmountInput.addEventListener('input', updateSingleFeePreview);
    singleFeeSelect.addEventListener('change', updateSingleFeePreview);
    multipleFeeSelect.addEventListener('change', calculateDynamicSplit);

    updateSingleFeePreview();
    calculateDynamicSplit();
});
</script>
</body>
</html>