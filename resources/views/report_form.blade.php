<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hinweis einreichen - Hinweisgeberportal</title>
    <link rel="stylesheet" href="/css/portal.css">
    <style>
        body { background: var(--bg); }

        .site-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-wrapper {
            max-width: 680px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .step-item.active { color: var(--primary); font-weight: 600; }
        .step-item.completed { color: var(--success); }

        .step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .step-item.active .step-num {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .step-item.completed .step-num {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }

        .step-divider {
            flex: 1;
            height: 1px;
            background: var(--border);
            margin: 0 8px;
        }

        .step-panel { display: none; }
        .step-panel.active { display: block; }

        .choice-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }

        .choice-card:hover { border-color: var(--primary); background: var(--primary-light); }
        .choice-card.selected { border-color: var(--primary); background: var(--primary-light); }
        .choice-card input[type="radio"] { display: none; }

        .credentials-table {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin: 1rem 0;
        }

        .credentials-table tr td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        .credentials-table tr:last-child td { border-bottom: none; }
        .credentials-table td:first-child { font-weight: 500; color: var(--text-muted); width: 130px; }
        .credentials-table td:last-child { font-family: monospace; font-size: 14px; }

        .copy-btn {
            background: none;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            cursor: pointer;
            color: var(--text-muted);
            margin-left: 8px;
        }

        .copy-btn:hover { border-color: var(--primary); color: var(--primary); }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .review-block {
            background: #f9fafb;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem;
            margin: 1rem 0;
        }

        .review-row {
            display: flex;
            gap: 12px;
            padding: 6px 0;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
        }

        .review-row:last-child { border-bottom: none; }
        .review-key { font-weight: 500; color: var(--text-muted); width: 140px; flex-shrink: 0; }
        .review-val { color: var(--text); }
    </style>
</head>
<body>
    <header class="site-header">
        <a href="/" style="font-size: 15px; font-weight: 700; color: #005FB8; text-decoration: none;">Hinweisgeberportal</a>
        <nav style="display: flex; gap: 8px; align-items: center;">
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm">Admin-Bereich</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-sm">Meine Hinweise</a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="btn btn-secondary btn-sm">Anmelden</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registrieren</a>
            @endauth
        </nav>
    </header>

    <div class="form-wrapper">
        <div class="card">
            <div class="card-header" style="background: #005FB8; color: white; border-radius: 6px 6px 0 0; border: none;">
                <div style="font-size: 16px; font-weight: 700;">Hinweis einreichen</div>
                <div style="font-size: 12px; font-weight: 400; opacity: 0.85; margin-top: 2px;">Ihre Meldung wird vertraulich behandelt</div>
            </div>

            <div class="card-body">
                <!-- Step Indicator -->
                <div class="step-indicator" id="stepIndicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-num">1</div>
                        <span>Information</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-num">2</div>
                        <span>Vorfall</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item" data-step="3">
                        <div class="step-num">3</div>
                        <span>Details</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step-item" data-step="4">
                        <div class="step-num">4</div>
                        <span>Prüfen</span>
                    </div>
                </div>

                <form id="reportForm">
                    <!-- Step 1 -->
                    <div class="step-panel active" data-step="1">
                        <div class="alert alert-info" style="margin-bottom: 1.25rem;">
                            <strong>Willkommen beim Hinweisgeberportal</strong><br>
                            Dieses Portal ermöglicht es Ihnen, Hinweise auf Missstände oder Rechtsverstöße sicher und vertraulich zu melden. Alle Angaben werden streng vertraulich behandelt.
                        </div>

                        @auth
                            @if(!auth()->user()->is_admin)
                                <div class="form-group">
                                    <label class="form-label">Einreichungsart</label>
                                    <div style="display: flex; gap: 10px;">
                                        <label class="choice-card selected" style="flex: 1;" onclick="selectChoice(this, 'authenticated')">
                                            <input type="radio" name="submission_type" value="authenticated" checked>
                                            <div style="font-weight: 600; font-size: 13px; margin-bottom: 2px;">Mit meinem Konto</div>
                                            <div style="font-size: 12px; color: var(--text-muted);">Angemeldet als {{ auth()->user()->name }}</div>
                                        </label>
                                        <label class="choice-card" style="flex: 1;" onclick="selectChoice(this, 'anonymous')">
                                            <input type="radio" name="submission_type" value="anonymous">
                                            <div style="font-weight: 600; font-size: 13px; margin-bottom: 2px;">Anonym</div>
                                            <div style="font-size: 12px; color: var(--text-muted);">Automatische Zugangsdaten</div>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        @endauth

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Weiter</button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-panel" data-step="2">
                        <div class="form-group">
                            <label class="form-label">Betroffene Gesellschaft <span class="optional">(optional)</span></label>
                            <input class="form-control" type="text" name="company" placeholder="z.B. Beispiel GmbH">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Art des Verstosses <span class="optional">(optional)</span></label>
                            <select class="form-control" name="violation_type">
                                <option value="">Bitte wahlen...</option>
                                <option value="Korruption">Korruption</option>
                                <option value="Betrug">Betrug</option>
                                <option value="Datenschutzverstoß">Datenschutzverstoß</option>
                                <option value="Diskriminierung">Diskriminierung</option>
                                <option value="Umweltverstoß">Umweltverstoß</option>
                                <option value="Sicherheitsrisiko">Sicherheitsrisiko</option>
                                <option value="Sonstiges">Sonstiges</option>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div class="form-group">
                                <label class="form-label">Datum des Vorfalls <span class="optional">(optional)</span></label>
                                <input class="form-control" type="date" name="incident_date">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ort des Vorfalls <span class="optional">(optional)</span></label>
                                <input class="form-control" type="text" name="incident_location" placeholder="z.B. Berlin, Abteilung XY">
                            </div>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">Zuruck</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Weiter</button>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-panel" data-step="3">
                        <div class="form-group">
                            <label class="form-label">Beteiligte Personen <span class="optional">(optional)</span></label>
                            <textarea class="form-control" name="involved_persons" placeholder="Beschreiben Sie beteiligte oder betroffene Personen"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Titel des Hinweises <span style="color: var(--danger);">*</span></label>
                            <input class="form-control" type="text" name="title" required placeholder="Kurze Zusammenfassung des Vorfalls">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Beschreibung <span style="color: var(--danger);">*</span></label>
                            <textarea class="form-control" name="description" required placeholder="Beschreiben Sie den Vorfall so detailliert wie moglich..." style="min-height: 160px;"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Anhange <span class="optional">(optional)</span></label>
                            <input class="form-control" type="file" id="fileInput" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt" style="padding: 6px;">
                            <div class="text-sm text-muted mt-1">Max. 5 Dateien, je max. 10 MB (PDF, DOC, JPG, PNG, TXT)</div>
                            <div id="fileList"></div>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">Zuruck</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Weiter</button>
                        </div>
                    </div>

                    <!-- Step 4: Review -->
                    <div class="step-panel" data-step="4">
                        <div class="alert alert-info">Bitte uberprufen Sie Ihre Angaben vor dem Absenden.</div>

                        <div class="review-block" id="reviewContent"></div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">Zuruck</button>
                            <button type="submit" class="btn btn-primary">Hinweis absenden</button>
                        </div>
                    </div>

                    <!-- Step 5: Success -->
                    <div class="step-panel" data-step="5">
                        <div class="alert alert-success" style="margin-bottom: 1.25rem;">
                            <strong>Hinweis erfolgreich eingereicht.</strong><br>
                            Ihre Meldung wurde sicher ubermittelt.
                        </div>

                        <div class="alert alert-warning">
                            <strong>Wichtig: Speichern Sie diese Zugangsdaten.</strong><br>
                            Sie werden nur einmal angezeigt und ermoglichen Ihnen den Zugriff auf Ihren Hinweis.
                        </div>

                        <table class="credentials-table">
                            <tr>
                                <td>Benutzername</td>
                                <td>
                                    <span id="cred-username"></span>
                                    <button type="button" class="copy-btn" onclick="copyText('cred-username')">Kopieren</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Passwort</td>
                                <td>
                                    <span id="cred-password"></span>
                                    <button type="button" class="copy-btn" onclick="copyText('cred-password')">Kopieren</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Zugangslink</td>
                                <td>
                                    <a href="#" id="cred-url" class="link" target="_blank">Link offnen</a>
                                    <button type="button" class="copy-btn" onclick="copyText('cred-url')">Kopieren</button>
                                </td>
                            </tr>
                        </table>

                        <button type="button" class="btn btn-primary" onclick="window.location.href='/'">Fertig</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        function selectChoice(el, type) {
            document.querySelectorAll('.choice-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
        }

        function updateIndicator(step) {
            document.querySelectorAll('.step-item').forEach(item => {
                const s = parseInt(item.dataset.step);
                item.classList.remove('active', 'completed');
                if (s === step) item.classList.add('active');
                else if (s < step) item.classList.add('completed');
            });
        }

        function showStep(step) {
            document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
            const panel = document.querySelector(`.step-panel[data-step="${step}"]`);
            if (panel) panel.classList.add('active');
            if (step <= totalSteps) updateIndicator(step);
        }

        function nextStep() {
            if (currentStep === 3) {
                const title = document.querySelector('[name="title"]').value.trim();
                const desc = document.querySelector('[name="description"]').value.trim();
                if (!title || !desc) { alert('Bitte fullen Sie Titel und Beschreibung aus.'); return; }
            }
            if (currentStep === totalSteps) updateReview();
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                window.scrollTo(0, 0);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                window.scrollTo(0, 0);
            }
        }

        function updateReview() {
            const fd = new FormData(document.getElementById('reportForm'));
            const fields = {
                title: 'Titel', company: 'Gesellschaft', violation_type: 'Art des Verstosses',
                incident_date: 'Datum', incident_location: 'Ort',
                involved_persons: 'Beteiligte', description: 'Beschreibung'
            };
            let html = '';
            for (const [key, label] of Object.entries(fields)) {
                const val = fd.get(key);
                if (val) html += `<div class="review-row"><div class="review-key">${label}</div><div class="review-val">${val}</div></div>`;
            }
            document.getElementById('reviewContent').innerHTML = html || '<div class="text-muted text-sm">Keine Angaben</div>';
        }

        document.getElementById('reportForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            const files = document.getElementById('fileInput').files;
            const submissionType = document.querySelector('input[name="submission_type"]:checked');
            const isAnonymous = !submissionType || submissionType.value === 'anonymous';

            const data = {
                title: fd.get('title'), company: fd.get('company'),
                violation_type: fd.get('violation_type'), incident_date: fd.get('incident_date'),
                incident_location: fd.get('incident_location'), involved_persons: fd.get('involved_persons'),
                description: fd.get('description'), is_anonymous: isAnonymous
            };

            try {
                const res = await fetch('/api/reports', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await res.json();

                if (result.success) {
                    if (files.length > 0) await uploadFiles(result.report_id || result.report.id, files);

                    if (isAnonymous) {
                        document.getElementById('cred-username').textContent = result.credentials.username;
                        document.getElementById('cred-password').textContent = result.credentials.password;
                        document.getElementById('cred-url').textContent = result.credentials.access_url;
                        document.getElementById('cred-url').href = result.credentials.access_url;
                        currentStep = 5;
                        showStep(5);
                        window.scrollTo(0, 0);
                    } else {
                        window.location.href = '/user/dashboard';
                    }
                }
            } catch(err) {
                alert('Fehler beim Absenden. Bitte versuchen Sie es erneut.');
            }
        });

        async function uploadFiles(reportId, files) {
            const fd = new FormData();
            for (let i = 0; i < files.length; i++) fd.append('files[]', files[i]);
            try {
                await fetch(`/api/reports/${reportId}/attachments`, { method: 'POST', body: fd });
            } catch(e) { console.error(e); }
        }

        document.getElementById('fileInput').addEventListener('change', function(e) {
            const list = document.getElementById('fileList');
            if (!e.target.files.length) { list.innerHTML = ''; return; }
            let html = '<div style="margin-top: 8px; font-size: 12px; color: var(--text-muted);">';
            for (const f of e.target.files) html += `<div>${f.name} (${(f.size/1024).toFixed(1)} KB)</div>`;
            list.innerHTML = html + '</div>';
        });

        function copyText(id) {
            const el = document.getElementById(id);
            navigator.clipboard.writeText(el.textContent || el.href).then(() => {
                alert('Kopiert!');
            });
        }
    </script>
</body>
</html>
