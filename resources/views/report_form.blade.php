<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hinweis einreichen - Hinweisgeberportal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: system-ui, -apple-system, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto;
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .header p { opacity: 0.9; }
        .progress-bar {
            display: flex;
            background: #f5f5f5;
            padding: 20px;
            justify-content: space-between;
        }
        .progress-step {
            flex: 1;
            text-align: center;
            padding: 10px;
            position: relative;
        }
        .progress-step.active {
            color: #667eea;
            font-weight: 600;
        }
        .progress-step.completed {
            color: #4caf50;
        }
        .form-content {
            padding: 40px;
        }
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        label .optional {
            font-weight: normal;
            color: #999;
            font-size: 13px;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-info {
            background: #e3f2fd;
            color: #1565c0;
            border-left: 4px solid #2196f3;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }
        .credentials-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box h3 {
            color: #856404;
            margin-bottom: 15px;
        }
        .credential-item {
            background: white;
            padding: 12px;
            border-radius: 4px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .copy-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .warning-box {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Hinweis einreichen</h1>
            <p>Ihre Meldung wird vertraulich und anonym behandelt</p>
        </div>

        <div class="progress-bar">
            <div class="progress-step active" data-step="1">① Aufklärung</div>
            <div class="progress-step" data-step="2">② Bericht erstellen</div>
            <div class="progress-step" data-step="3">③ Ihre Daten</div>
            <div class="progress-step" data-step="4">④ Überprüfen</div>
        </div>

        <div class="form-content">
            <form id="reportForm">
                <!-- Step 1: Info -->
                <div class="step active" data-step="1">
                    <div class="alert alert-info">
                        <strong>Willkommen beim Hinweisgeberportal</strong><br><br>
                        Dieses Portal ermöglicht es Ihnen, Hinweise auf Missstände oder Rechtsverstöße sicher und vertraulich zu melden.<br><br>
                        <strong>Ihre Anonymität ist geschützt:</strong><br>
                        • Sie erhalten automatisch generierte Zugangsdaten<br>
                        • Keine persönlichen Daten werden gespeichert<br>
                        • Sichere Kommunikation mit der internen Stelle
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn-primary" onclick="nextStep()">Weiter →</button>
                    </div>
                </div>

                <!-- Step 2: Report Details -->
                <div class="step" data-step="2">
                    <h2 style="margin-bottom: 20px;">Bericht erstellen</h2>
                    
                    <div class="form-group">
                        <label>Welche Gesellschaft ist involviert? <span class="optional">(optional)</span></label>
                        <input type="text" name="company" placeholder="z.B. Beispiel GmbH">
                    </div>

                    <div class="form-group">
                        <label>Was ist der Auflegung? <span class="optional">(optional)</span></label>
                        <select name="violation_type">
                            <option value="">Bitte wählen...</option>
                            <option value="Korruption">Korruption</option>
                            <option value="Betrug">Betrug</option>
                            <option value="Datenschutzverstoß">Datenschutzverstoß</option>
                            <option value="Diskriminierung">Diskriminierung</option>
                            <option value="Umweltverstoß">Umweltverstoß</option>
                            <option value="Sicherheitsrisiko">Sicherheitsrisiko</option>
                            <option value="Sonstiges">Sonstiges</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Wann ist es passiert? <span class="optional">(optional)</span></label>
                        <input type="date" name="incident_date">
                    </div>

                    <div class="form-group">
                        <label>Wo ist es passiert? <span class="optional">(optional)</span></label>
                        <input type="text" name="incident_location" placeholder="z.B. Hauptsitz Berlin, Abteilung XY">
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="prevStep()">← Zurück</button>
                        <button type="button" class="btn-primary" onclick="nextStep()">Weiter →</button>
                    </div>
                </div>

                <!-- Step 3: Details -->
                <div class="step" data-step="3">
                    <h2 style="margin-bottom: 20px;">Einzelheiten zu Ihrem Bericht</h2>
                    
                    <div class="form-group">
                        <label>Wer ist beteiligt oder betroffen? <span class="optional">(optional)</span></label>
                        <textarea name="involved_persons" placeholder="Beschreiben Sie beteiligte oder betroffene Personen (ohne Ihre eigene Identität preiszugeben)"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Titel des Hinweises <span style="color: red;">*</span></label>
                        <input type="text" name="title" required placeholder="Kurze Zusammenfassung">
                    </div>

                    <div class="form-group">
                        <label>Einzelheiten zu Ihrem Bericht <span style="color: red;">*</span></label>
                        <textarea name="description" required placeholder="Beschreiben Sie den Vorfall so detailliert wie möglich..." style="min-height: 200px;"></textarea>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="prevStep()">← Zurück</button>
                        <button type="button" class="btn-primary" onclick="nextStep()">Weiter →</button>
                    </div>
                </div>

                <!-- Step 4: Review & Submit -->
                <div class="step" data-step="4">
                    <h2 style="margin-bottom: 20px;">Überprüfen und absenden</h2>
                    
                    <div class="alert alert-info">
                        <strong>Bitte überprüfen Sie Ihre Angaben</strong><br>
                        Nach dem Absenden erhalten Sie automatisch generierte Zugangsdaten.
                    </div>

                    <div id="reviewContent" style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <!-- Will be filled by JavaScript -->
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="prevStep()">← Zurück</button>
                        <button type="submit" class="btn-primary">Hinweis absenden</button>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="step" data-step="5">
                    <div class="alert alert-success">
                        <strong>✅ Hinweis erfolgreich eingereicht!</strong><br>
                        Ihre Meldung wurde sicher übermittelt.
                    </div>

                    <div class="credentials-box">
                        <h3>⚠️ WICHTIG: Speichern Sie diese Zugangsdaten!</h3>
                        <p style="margin-bottom: 15px;">Diese Daten werden nur einmal angezeigt und ermöglichen Ihnen den Zugriff auf Ihren Hinweis.</p>
                        
                        <div class="credential-item">
                            <span><strong>Benutzername:</strong> <span id="cred-username"></span></span>
                            <button type="button" class="copy-btn" onclick="copyText('cred-username')">Kopieren</button>
                        </div>
                        <div class="credential-item">
                            <span><strong>Passwort:</strong> <span id="cred-password"></span></span>
                            <button type="button" class="copy-btn" onclick="copyText('cred-password')">Kopieren</button>
                        </div>
                        <div class="credential-item">
                            <span><strong>Zugangslink:</strong> <a href="#" id="cred-url" target="_blank">Link öffnen</a></span>
                            <button type="button" class="copy-btn" onclick="copyText('cred-url')">Kopieren</button>
                        </div>

                        <div class="warning-box">
                            <strong>⚠️ Diese Zugangsdaten werden nicht erneut angezeigt!</strong><br>
                            Bitte speichern Sie sie an einem sicheren Ort.
                        </div>
                    </div>

                    <button type="button" class="btn-primary" onclick="window.location.href='/'">Fertig</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        function showStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.progress-step').forEach(s => s.classList.remove('active', 'completed'));
            
            document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
            document.querySelector(`.progress-step[data-step="${step}"]`).classList.add('active');
            
            for(let i = 1; i < step; i++) {
                document.querySelector(`.progress-step[data-step="${i}"]`).classList.add('completed');
            }
        }

        function nextStep() {
            if(currentStep === 4) {
                updateReview();
            }
            if(currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                window.scrollTo(0, 0);
            }
        }

        function prevStep() {
            if(currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                window.scrollTo(0, 0);
            }
        }

        function updateReview() {
            const formData = new FormData(document.getElementById('reportForm'));
            let html = '<h3 style="margin-bottom: 15px;">Ihre Angaben:</h3>';
            
            const fields = {
                'title': 'Titel',
                'company': 'Gesellschaft',
                'violation_type': 'Art des Verstoßes',
                'incident_date': 'Datum',
                'incident_location': 'Ort',
                'involved_persons': 'Beteiligte Personen',
                'description': 'Beschreibung'
            };

            for(const [key, label] of Object.entries(fields)) {
                const value = formData.get(key);
                if(value) {
                    html += `<p><strong>${label}:</strong> ${value}</p>`;
                }
            }

            document.getElementById('reviewContent').innerHTML = html;
        }

        document.getElementById('reportForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                title: formData.get('title'),
                company: formData.get('company'),
                violation_type: formData.get('violation_type'),
                incident_date: formData.get('incident_date'),
                incident_location: formData.get('incident_location'),
                involved_persons: formData.get('involved_persons'),
                description: formData.get('description'),
                is_anonymous: true
            };

            try {
                const response = await fetch('/api/reports', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if(result.success) {
                    document.getElementById('cred-username').textContent = result.credentials.username;
                    document.getElementById('cred-password').textContent = result.credentials.password;
                    document.getElementById('cred-url').textContent = result.credentials.access_url;
                    document.getElementById('cred-url').href = result.credentials.access_url;
                    
                    currentStep = 5;
                    showStep(5);
                    window.scrollTo(0, 0);
                }
            } catch(error) {
                alert('Fehler beim Absenden. Bitte versuchen Sie es erneut.');
            }
        });

        function copyText(elementId) {
            const text = document.getElementById(elementId).textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('In Zwischenablage kopiert!');
            });
        }
    </script>
</body>
</html>
