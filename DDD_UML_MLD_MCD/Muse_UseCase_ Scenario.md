# Scenario:
## Commande Point de vue Client


|Description|
|-----------|
 Ce cas d’utilisation a pour but de décrire le parcours effectué par l’utilisateur, afin de saisir des produits dans son panier puis de valider une commande.




|Conditions|
|-----------|
Le client est sur la page d’accueil, accède aux catégories, sélectionne 1 ou plusieurs produits, les met dans son panier et paie.



|Résultat|
|-----------|
Une commande est validée.


|Flot Nominal| 
|-----------|
&rarr; Le client clique sur une catégorie de la page d'accueil ou l'onglet "Catégories".
&larr; le système lui presente la page des sous-catégories.
&rarr; le client clique sur une sous-catégorie.
&larr; le système renvoie la page de navigation des produits.
&rarr; le client clique sur un produit.
&larr; le système lui renvoie la page du produit.
&rarr; Le client clique sur "Ajouter au panier".  
&larr; Le système ajoute le produit au panier (cette opération peut être réalisée pusieurs fois d'affilée).
&rarr; Le client clique sur le panier.
&larr; Le système affiche la page du panier.
&rarr; Le client clique sur "Finaliser la commande".
&larr; Le systeme affiche un récapitulatif des informations de l'utilisateur et du panier, ainsi qu'un formulaire de sélection des adresses de livraison et de facturation et un formulaire d'ajout d'adresse.
&rarr; Le client remplit les différents inputs et clique sur "Valider la commande".
&larr; le système valide la commande, affiche un message de validation et envoie un mail avec le recapitulatif de la commande. 

|Flot Alternatif:|Utilisateur non connecté| 
|-----------|-------------
&rarr;  Quand Le client clique sur "Panier" ou "Ajouter au panier". 
&larr; L'application affiche un formulaire d'authentification ainsi qu'un message demandant de s'inscrire ou de se connecter.
&rarr; le client saisit les champs d'authentification.
&larr; le système valide l'authentification et affiche un message de validation.
&harr; Le flot nominal reprend.

|Flot Alternatif:|Utilisateur non inscrit| 
|-----------|-------------
&rarr;  Quand Le client clique sur "Panier" ou "Ajouter au panier". 
&larr; L'application affiche un formulaire d'authentification ainsi qu'un message demandant de s'inscrire ou de se connecter.
&rarr; le client clique sur "S'inscrire".
&larr; Le système affiche le formulaire d'inscription.
&rarr; Le client entre ses coordonnées.
&larr; Le système valide l'inscription, affiche un message de validation et le client est connecté.
&harr; Le flot nominal reprend.

|Flot Alternatif:|Adresses non renseignées| 
|-----------|-------------
&rarr;  Quand Le client clique sur "Panier" ou "Ajouter au panier". 
&larr; L'application affiche un message demandant de renseigner les adresses de livraison et de facturation.
&rarr; le client clique sur les adresses de livraison et de facturation ou renseigne une nouvelle adresse.
&larr; Le système enregistre les adresses et affiche un message de validation des adresses.
&harr; Le flot nominal reprend.

