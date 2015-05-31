Feature: Posts
    Permet de recupérer les posts du site vie de merde

    Scenario: Page d'accueil
        When je vais sur la page d'accueil
        Then Then response code should be 404
    Scenario: Récupérer les posts
        When I send a GET request to "/posts"
        Then the response should contain json:
        """
        {"id":73,"content":"Aujourd'hui, je prends le train. Le trajet \u00e9tant un peu long, je d\u00e9cide de faire une sieste et demande donc \u00e0 mon voisin de me r\u00e9veiller \u00e0 mon arr\u00eat. Je me r\u00e9veille 5 h plus tard et 3 gares apr\u00e8s la mienne, il s'\u00e9tait endormi lui aussi. VDM","date":"2015-05-18T00:25:44+0200","author":"bodybop"}
        """
        And the response json's "items" key should be of type "array"
    Scenario: Récupérer les posts d'un auteur    
        When I send a GET request to "/api/posts?author=Genius"
        Then the response should contain json:
        """
        {"id":81,"content":"Aujourd'hui et pour la premi\u00e8re fois depuis deux ans, j'ai repris contact avec les pr\u00e9servatifs. J'ai gliss\u00e9 dessus dans le hall de ma fac lors d'une journ\u00e9e de pr\u00e9vention. VDM","date":"2015-05-16T00:25:44+0200","author":"Genius"}
        """
        And the response json's "items" key should be of type "array"
    Scenario: Récupérer les posts entre deux dates    
        When I send a GET request to "/api/posts?from=2015-05-15&to=2015-05-30"
        Then the response should contain json:
        """
        {"id":89,"content":"Aujourd'hui, je fais la sieste avec mon mari. Je sens pendant mon sommeil qu'on me touche les fesses. Je me laisse aller \u00e0 quelques mouvements de fesses croyant que c'\u00e9tait mon mari, en fait c'\u00e9tait mon chien qui avait bien cal\u00e9 sa truffe. Mon mari \u00e9tait d\u00e9j\u00e0 lev\u00e9. VDM","date":"2015-05-15T00:12:18+0200","author":"lula"}
        """
        And the response json's "items" key should be of type "array"