<script>
    function highlightIfChanged(oldValue, newValue) {
        return oldValue !== newValue 
            ? `<span style="color: #57c757; font-weight: 900;">${newValue}</span>` 
            : newValue;
    }

    document.querySelectorAll('.saveButton').forEach(button => {
        button.addEventListener('click', function() {
            const validationId = this.getAttribute('data-id');
            const nouveau = JSON.parse(this.getAttribute('data-nouveau'));
            const action_type = nouveau.action_type;
            const updateType = nouveau.observation;
            let infoTable = ``;

            let action_name = ' création';
            // Affichage dynamique en fonction du type d'action
            if (action_type == 'create') {

                let modifications = nouveau.model;
                // Vérifier si `modifications` est une chaîne JSON et le parser si nécessaire
                if (typeof modifications === 'string') {
                    modifications = JSON.parse(modifications);
                }

                infoTable = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Champs</th>
                                <th>Informations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Nom</td><td>${modifications.nom}</td></tr>
                            <tr><td>Transporteur ID</td><td>${modifications.transporteur_id}</td></tr>
                            <tr><td>RFID Physique</td><td>${modifications.rfid_physique ?? "<span style='color: #FFA500; font-weight: 900;'>En attente</span>"}</td></tr>
                            <tr><td>Contact</td><td>${modifications.contact}</td></tr>
                            <tr><td>Numéro de badge</td><td>${modifications.numero_badge}</td></tr>
                        </tbody>
                    </table>
                `;
            }


            if (action_type == 'delete') {
                action_name = ' suppression';
                let modifications = nouveau.model;

                // Vérifier si `modifications` est une chaîne JSON et le parser si nécessaire
                if (typeof modifications === 'string') {
                    modifications = JSON.parse(modifications);
                }

                infoTable = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Champs</th>
                                <th>Informations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Nom</td><td>${modifications.nom}</td></tr>
                            <tr><td>Transporteur </td><td>${modifications.related_transporteur.nom}</td></tr>
                            <tr><td>RFID Physique</td><td>${modifications.rfid_physique}</td></tr>
                            <tr><td>RFID plateforme</td><td>${modifications.rfid}</td></tr>
                            <tr><td>Contact</td><td>${modifications.contact}</td></tr>
                            <tr><td>Numéro de badge</td><td>${modifications.numero_badge}</td></tr>
                        </tbody>
                    </table>
                `;
            }

            let rfidField = '';

            if (action_type  == 'create') { 
                if(nouveau.modifications.rfid_physique == null){
                    rfidField = `
                        <div class='form-row' style="margin-top: 10px;">
                            <div class='form-group col-md-5' >
                                <label for="rfidInput">RFID PLATEFORME:</label>
                                <input type="text" id="rfidInput" class="swal2-input" placeholder="RFID" required>
                            </div>
                            <div class='form-group col-md-7' style='padding-right: 32px;'>
                                <label for="rfidphysiqueInput"> RFID PHYSIQUE:</label>
                                <input type="number" id="rfidphysiqueInput" class="swal2-input" placeholder="RFID Physique" required>
                            </div>
                        </div>
                    `;
                }else{

                    rfidField = `
                        <div class='form-group' style="margin-top: 10px;">
                            <label for="rfidInput">RFID PLATEFORME:</label>
                            <input type="text" id="rfidInput" class="swal2-input" placeholder="RFID" required>
                        </div>
                    `;
                }


            }



            if (action_type  == 'update') { 

                action_name = nouveau.observation;

                let ancien = nouveau.model;
                // Vérifier si `ancien` est une chaîne JSON et le parser si nécessaire
                if (typeof ancien === 'string') {
                    ancien = JSON.parse(ancien);
                }
                
                let nouveau_infos = nouveau.modifications;
                // Vérifier si `nouveau` est une chaîne JSON et le parser si nécessaire
                if (typeof nouveau_infos === 'string') {
                    nouveau_infos = JSON.parse(nouveau_infos);
                }

                infoTable = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Champs</th>
                                <th>Ancienne Information</th>
                                <th>Nouvelle Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nom</td>
                                <td>${ancien.nom}</td>
                                <td>${highlightIfChanged(ancien.nom, nouveau_infos.nom)}</td>
                            </tr>
                            <tr>
                                <td>Contact</td>
                                <td>${ancien.contact}</td>
                                <td>${highlightIfChanged(ancien.contact, nouveau_infos.contact)}</td>
                            </tr>
                            <tr>
                                <td>RFID</td>
                                <td>${ancien.rfid}</td>
                                <td>${updateType == "changement RFID pour la même personne" || updateType == "Changement de transporteur" ? '<span style="color: #FFA500; font-weight: 900;">En attente</span>' : highlightIfChanged(ancien.rfid, nouveau_infos.rfid)}</td>

                            </tr>
                            <tr>
                                <td>Numéro Badge</td>
                                <td>${ancien.numero_badge}</td>
                                <td>${highlightIfChanged(ancien.numero_badge, nouveau_infos.numero_badge)}</td>
                            </tr>
                            <tr>
                                <td>RFID Physique</td>
                                <td>${ancien.rfid_physique}</td>
                                <td>${highlightIfChanged(ancien.rfid_physique, nouveau_infos.rfid_physique)}</td>
                            </tr>
                            <tr>
                                <td>Transporteur</td>
                                <td>${ancien.related_transporteur.nom}</td>
                                <td>${highlightIfChanged(ancien.related_transporteur.nom, nouveau_infos.transporteur)}</td>
                            </tr>
                        </tbody>
                    </table>
                `;

                if (updateType == "changement RFID pour la même personne" || updateType == "Changement de transporteur") { 
                    rfidField = `
                        <div style="margin-top: 10px;">
                            <label for="rfidInput">Veuillez saisir le nouveau RFID :</label>
                            <input type="text" id="rfidInput" class="swal2-input" placeholder="Nouveau RFID" required>
                        </div>
                    `;
                }
            }

            Swal.fire({
                title: 'Confirmer la validation de la' + action_name +" du chauffeur " + nouveau.modifications.nom ,
                html: infoTable + rfidField,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler',
                width: '700px',
                customClass: {
                    validationMessage: 'custom-validation' // Ajoute une classe CSS personnalisée
                },
                    preConfirm: () => {

                        if (updateType == "changement RFID pour la même personne" || updateType == "Changement de transporteur" ) {
                            const rfidValue = document.getElementById('rfidInput').value.trim();
                            if (rfidValue === '') {
                                Swal.showValidationMessage('Le champ RFID est requis.');
                                return false; // Bloque la validation si le RFID est vide
                            }
                            return { rfid: rfidValue }; // Retourne le RFID saisi
                        }

                        if (action_type == 'create' && nouveau.modifications.rfid_physique == null) {
                            console.log('nouveau.rfid_physique',nouveau.rfid_physique)
                            const rfidValue = document.getElementById('rfidInput').value.trim();
                            const rfidPhysiqueValue = document.getElementById('rfidphysiqueInput').value.trim();
                            
                            if (rfidValue === '' || rfidPhysiqueValue === '') {
                                Swal.showValidationMessage('Veuillez remplir les formulaires.');
                                return false; // Bloque la validation si l'un des champs est vide
                            }
                            return { rfid: rfidValue, rfidphysique: rfidPhysiqueValue }; // Retourne les valeurs
                        } else if (action_type == 'create') {
                            const rfidValue = document.getElementById('rfidInput').value.trim();
                            
                            if (rfidValue === '') {
                                Swal.showValidationMessage('Le champ RFID est requis.');
                                return false; // Bloque la validation si le RFID est vide
                            }
                            return { rfid: rfidValue }; // Retourne le RFID saisi
                        }

                        return true;
                    }
            }).then((result) => {
                if (result.isConfirmed) {

                    const rfidValue = result.value?.rfid || null; // Récupérer le RFID si présent
                    const rfidPhysiqueValue = result.value?.rfidphysique || null; // Récupérer le RFID si présent

                    fetch("{{ route('validationRequest.creation') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: validationId,
                            validation: true,
                            rfid: rfidValue,
                            rfidPhysique: rfidPhysiqueValue,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Validé!', 'La validation a été effectuée.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                        console.error(error);
                    });
                }
            });
        });
    });




    document.querySelectorAll('.refusButton').forEach(button => {
        button.addEventListener('click', function() {
            const validationId = this.getAttribute('data-id');
            const nouveau = JSON.parse(this.getAttribute('data-nouveau'));
            const action_type = nouveau.action_type;

            let commentaire = `
                <div class='form-group ' style="margin-top: 10px;">
                    <textarea id="commentaireid" class="swal2-textarea" placeholder="Veuillez entrer votre description" required></textarea>
                </div>
            `;

            let action_name = ' création';
            // Affichage dynamique en fonction du type d'action
            if (action_type == 'delete') {
                action_name = ' suppression ';
            }
            if (action_type == 'update') {
                action_name = nouveau.observation;
            }
            Swal.fire({
                title: 'Validation refus de la' + action_name + ' du chauffeur ' + nouveau.modifications.nom,
                icon: 'warning',
                html: commentaire,
                showCancelButton: true,
                confirmButtonText: 'Oui, refuser',
                cancelButtonText: 'Annuler',
                width: '600px',
                customClass: {
                    validationMessage: 'custom-validation' // Ajoute une classe CSS personnalisée
                },

                preConfirm: () => {
                    const commentaire = document.getElementById('commentaireid').value.trim();
                    if (commentaire === '') {
                        Swal.showValidationMessage('Veuillez entrer un commentaire.');
                        return false;
                    }
                    return { commentairevalue: commentaire };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const commentaires = result.value?.commentairevalue || null;
                    fetch("{{ route('validationRequest.creation') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: validationId,
                            validation: false,
                            commentaire: commentaires
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Validé!', 'Le refus a été enregistré.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Erreur!', 'Une erreur est survenue lors du refus.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erreur!', 'Une erreur est survenue.', 'error');
                        console.error(error);
                    });
                }
            });
        });
    });



</script>