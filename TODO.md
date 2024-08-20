# TODO:

## SETUP PROJECT:

    - installer nelmio pour avoir access aux routes **CHECK**
    - gerer la serialization
    - setup authentication JWT + le refresh token **CHECK**
    - creer un system de responderHTTP (200, 404, 500...)
    - connecter l'API openweatherapp

## SYMFONY:

    - creer les roles pour les users ["ROLE_USER","ROLE_ADMIN"] **CHECK**
    - rajouter le champ code postal pour les information de recherche **CHECK**
    - rajouter l'entity : conseil **CHECK**
    - hacher le password **CHECK**

## ROUTES:

### Routes accessibles sans authentification :
    - POST /user : Cette route permet de créer un nouveau compte utilisateur.**CHECK**
  
    - POST /auth : Cette route permet de s’authentifier avec un token JWT **CHECK**
        (mais on est obligé de redigirer de auth à login_check, pas pratique)

### Routes accessibles aux utilisateurs authentifiés et aux admins :

    - GET /conseil/{mois} : permet de récupérer un tableau avec tous les conseils du mois spécifié. **CHECK**
  
    - GET /conseil/ : permet de récupérer un tableau avec tous les conseils du mois en cours. **CHECK**
  
    - GET /meteo/{ville} : permet de retourner la météo d’une ville donnée. La météo sera récupérée sur une API publique
      et stockée en cache pour éviter de surcharger l’API publique.
  
    - GET /meteo : Dans le cas où la ville n’est pas renseignée, c’est la ville du compte utilisateur qui est utilisée.
      S’il y a plusieurs réponses pour un même nom de ville, pour l’instant, on prend systématiquement la première 
      proposition. Pense aussi à retourner une erreur si la ville n’est pas trouvée par l’API météo.


### Routes accessibles seulement aux administrateurs :
    - POST /conseil : permet d’ajouter un conseil. Attention à bien préciser dans la requête la liste des mois ou le
        conseil s’applique. Le conseil est un simple champ texte pour l’instant. **CHECK**
      
    - PUT /conseil/{id} : permet de mettre à jour le conseil qui correspond à l’id. Cette route ne met à jour que les
          informations réellement envoyées (s’il ne faut changer que la liste des mois où le conseil s’applique, inutile
          de renvoyer l’intégralité du conseil). **CHECK**
      
    - DELETE /conseil/{id} : permet de supprimer un conseil. **CHECK**
      
    - PUT /user/{id} : permet de mettre à jour un compte. **CHECK**
      
    - DELETE /user/{id} : permet de supprimer un compte. **CHECK**

## DB:

    - rajouter un champ string nom de mois sur la table mois **CHECK**
