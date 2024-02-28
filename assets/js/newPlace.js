document.addEventListener('DOMContentLoaded', function () {
    const savePlaceButton = document.getElementById('savePlaceButton');
    const addPlaceModal = document.getElementById('addPlaceModal');


    // Récupérez le chemin de la route à partir de l'attribut data-route
    const savePlaceRoute = savePlaceButton.getAttribute('data-route');

    const addPlaceModal = document.getElementById('addPlaceModal');
    const bootstrapModal = new bootstrap.Modal(addPlaceModal);

    savePlaceButton.addEventListener('click', function () {
        // Récupérez les données du formulaire
        const placeNameInput = document.getElementById('placeName');
        const placeAddressInput = document.getElementById('placeAddress');
        const placeLatitudeInput = document.getElementById('placeLatitude');
        const placeLongitudeInput = document.getElementById('placeLongitude');

        const placeName = placeNameInput.value;
        const placeAddress = placeAddressInput.value;
        const placeLatitude = parseFloat(placeLatitudeInput.value);
        const placeLongitude = parseFloat(placeLongitudeInput.value);

        // Vérifiez la longueur des données
        if (placeName.length < 2 || placeAddress.length < 2) {
            alert("Nom du lieu et Adresse du lieu doivent avoir au moins 2 caractères.");
            return;
        }

        // Créez un objet avec les données
        const placeData = {
            name: placeName,
            address: placeAddress,
            latitude: parseFloat(placeLatitude),
            longitude: parseFloat(placeLongitude)
        };

        // Envoyez une requête Ajax pour enregistrer le nouveau lieu
        fetch(savePlaceRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(placeData),
        })
        .then(response => response.json())
        .then(data => {
            // Traitez la réponse du serveur ici
            console.log(data['place']);
            // Fermez le modal si l'opération réussit
            bootstrapModal.hide();
            // Ajouter le nouveau lieu dans le select des lieux
            const placeList = document.getElementById('event_place');
            const newPlaceOption = document.createElement('option');
            newPlaceOption.value = data['place'].id;
            newPlaceOption.text = data['place'].name;
            placeList.appendChild(newPlaceOption);

        })
        .catch(error => {
            console.error('Erreur lors de l\'enregistrement du lieu:', error);
        });
    });
});