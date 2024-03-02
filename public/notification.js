// notification.js

// Fonction pour afficher la notification avec le nombre de notifications
function showNotification(notificationCount) {
    // Vérifier si les notifications sont prises en charge par le navigateur
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
    } else if (Notification.permission === "granted") {
        // Si la permission de notification est déjà accordée, afficher la notification
        show();
    } else if (Notification.permission !== "denied") {
        // Demander la permission d'afficher les notifications
        Notification.requestPermission().then(function (permission) {
            if (permission === "granted") {
                show();
            }
        });
    }

    function show() {
        // Créer une notification avec le message approprié incluant le nombre de notifications
        const notification = new Notification('Nouveau post ajouté', {
            body: `Vous avez ${notificationCount} nouveaux posts.`,
            icon: '/path/to/your/icon.png' // Remplacez par le chemin de votre propre icône
        });

        // Vous pouvez également ajouter un son de notification si nécessaire
        const audio = new Audio('/path/to/your/notification-sound.mp3'); // Remplacez par le chemin de votre propre son
        audio.play();
    }
}
