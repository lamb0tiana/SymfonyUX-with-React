const chokidar = require('chokidar');
const { exec } = require('child_process');

// Chemin vers le fichier .graphql à surveiller
const fichierGraphQL = 'graphql/*.graphql';

// Commande à exécuter pour exécuter le codegen
const commandeCodegen = 'yarn codegen';

// Configuration de chokidar pour surveiller les changements de fichier
const watcher = chokidar.watch(fichierGraphQL);

// Événement déclenché lorsqu'un changement de fichier est détecté
watcher.on('change', () => {
    console.log('Le fichier .graphql a été modifié. Exécution du codegen...');

    // Exécution de la commande de codegen
    exec(commandeCodegen, (erreur, stdout, stderr) => {
        if (erreur) {
            console.error(`Erreur lors de l'exécution du codegen : ${erreur}`);
            return;
        }

        // Affichage de la sortie standard de la commande
        console.log(stdout);

        // Affichage de la sortie d'erreur de la commande
        console.error(stderr);
    });
});

// Gestion des erreurs
watcher.on('error', erreur => {
    console.error(`Erreur lors de la surveillance du fichier .graphql : ${erreur}`);
});

// Message de confirmation que la surveillance a commencé
console.log(`Surveillance du fichier .graphql : ${fichierGraphQL}`);
