document.addEventListener('DOMContentLoaded', function () {
    const savePlaceButton = document.getElementById('savePlaceButton');
    // const addPlaceForm = document.getElementById('addPlaceForm');
    const addPlaceModal = document.getElementById('addPlaceModal');

    // Récupérez le chemin de la route à partir de l'attribut data-route
    const savePlaceRoute = savePlaceButton.getAttribute('data-route');

    savePlaceButton.addEventListener('click', function () {
        // Récupérez les données du formulaire
        const placeName = document.getElementById('placeName').value;
        const placeAddress = document.getElementById('placeAddress').value;
        const placeLatitude = document.getElementById('placeLatitude').value;
        const placeLongitude = document.getElementById('placeLongitude').value;

        // Créez un objet avec les données
        const placeData = {
            name: placeName,
            address: placeAddress,
            latitude: parseFloat(placeLatitude),
            longitude: parseFloat(placeLongitude)
            // Ajoutez d'autres propriétés au besoin
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
            const modal = bootstrap.Modal.getInstance(addPlaceModal);
            modal.hide();
            // Ajouter le nouveau lieu dans le select des lieux
            const placeList = document.getElementById('event_place');
            const newPlaceOption = document.createElement('option');
            newPlaceOption.value = data['place'].id;
            newPlaceOption.text = data['place'].name;
            placeList.appendChild(newPlaceOption);

        })
        .catch(error => {
            console.error('Erreur lors de l\'enregistrement du lieu:', error);
            // Gérez les erreurs ici
        });
    });
});