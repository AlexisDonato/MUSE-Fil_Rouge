# Scenario:
## Commande Point de vue Client


|Description|
|-----------|
 Ce cas d’utilisation a pour but de décrire  le parcours effectué  par l’utilisateur,  afin de saisir des produits dans son panier puis  valider une commande.




|Conditions|
|-----------|
Le Client est sur la page d’accueil, accéde aux catégories, selectionne 1 ou plusieurs produits, les met dans son panier et paie.



|Resultat|
|-----------|
Une commande est validée.


|Flot Nominal| 
|-----------|
&rarr; Le client clique sur une categorie de la page d'accueil ou l'onglet "Catégories".
&larr; le systéme lui presente la page des sous-categories.
&rarr; le client clique sur une sous-categorie.
&larr; le systéme renvoie la page de navigation des produits.
&rarr; le client clique sur un produit.
&larr; le systéme lui renvoie la page du produit.
&rarr; Le client clique sur "Ajouter au panier".  
&larr; Le systéme affiche l'écran du panier.(Cette opération peut être réalisée pusieurs fois d'affilée).
&rarr; Le client clique sur "Finaliser la commande".
&larr; Le systeme affiche un formulaire de selection de moyen de paiment. et de l'adresse de livraison.
&rarr; Le client remplit les différents inputs et clique sur "Valider la commande.
&larr; le système valide la commande et envoies un mail avec le recapitulatif de la commande. 

|Flot Alternatif:|User non connecté| 
|-----------|-------------
&rarr;  Quand Le client clique sur "Panier" ou "Ajouter au panier". 
&larr; L'application affiche un formulaire d'authentification.
&rarr; le client saisit les champs d'authentification.
&larr; le systéme valide l'authentification.
&harr; Le flot nominal reprend.

|Flot Alternatif:|User non inscrit| 
|-----------|-------------
&rarr;  Quand Le client clique sur "Panier" ou "Ajouter au panier". 
&larr; L'application affiche un formulaire d'authentification
&rarr; le client clique sur "s'inscrire"
&larr; Le système affiche le formulaire d'inscritpion
&rarr; Le client entre ses coordonées
&larr; Le système valide l'inscription et le client est connecté.
&harr; Le flot nominal reprend.

