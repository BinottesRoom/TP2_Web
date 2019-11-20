<?php

    // auteur : Nicolas Chourot

    include_once 'DAL/models.php';
    include_once 'selectionUtilities.php';

    // retourne un tableau d'items (id, Nom de pays) issue de la table Selections
    function selectionsToItems(){
        $items=[];
        foreach(Selections()->get() as $selection){
            $country = Countries()->get($selection['CountrieId']);
            $items[$country['Id']] = $country['Name'];  
        }
        return $items;
    }
   
     // retourne un tableau d'items (id, Nom de pays) issue de la table Countries
    function countriesToItems(){
        $items = [];
         foreach(Countries()->get() as $country){
            $items[$country['Id']] = $country['Name'];  
        }
        return $items;
    }

    // Met à jour la table Selections avec la liste des options sélectionnés du <select...>
    // provenant du formulaire
    function saveFormSelection($selectedItemsId) {
        $selection['Id'] = 0;
        foreach($selectedItemsId as $idCountry) {
            $selection['CountrieId'] = $idCountry;
            Selections()->insert($selection);
        }
    }

    if (isset($_POST['submit'])) {
        DB()->beginTransaction();
        Selections()->deleteAll();
        if (isset($_POST['SelectedItems']))
            saveFormSelection($_POST['SelectedItems']);
        DB()->endTransaction();
    } 
    
    // produire les items qui seront présentés dans les <select...> du formulaire
    $selectedItems = selectionsToItems();
    $countriesItems = countriesToItems($selectedItems);

    // pruduction du html constituant le formulaire avec les deux <select...>
    $content="";  
    $content .= "<form method='post'>";
        $content .= "<table>";
            $content .= "<tr>";
                $content .= "<td>";
                    $content .= makeSelectedList($selectedItems);
                $content .= "</td>";
                $content .= "<td>";
                    // il faut utiliser le fichier css/flashButtons.css pour 
                    // que les <div> de classes MoveLeft et MoveRight se comportent
                    // comme des boutons flash
                    $content .= "<div id='MoveLeft'  ></div>"; 
                    $content .= "<div id='MoveRight' ></div>";
                $content .= "</td>";
                $content .= "<td>";
                    $content .= makeUnselectedList($countriesItems, $selectedItems);
                $content .= "</td>";
            $content .= "</tr>";
        $content .= "</table>";
        $content .= "<input type='submit' name='submit' value='Enregistrer'>";
    $content .= "</form>";
?>

<html>
<header>
    <!-- pour les classes d'icone au comportement de boutons à 3 états flash-->
	<link rel="stylesheet" href="css/flashButtons.css">
    <style>
        form{
            margin:30px;
        }
        select {
            width:250px;
            margin:10px;
            padding:3px;
        }
        option{
            margin:3px;
        }
    </style>
</header>
<body >
    <h1>Sélection de pays</h1>
    <div>
        <?php echo $content; ?>
    </div>
    <!-- On place normalement les script à la fin pour optimiser le temps de chargement du html :
        https://www.webfx.com/blog/web-design/20-html-best-practices-you-should-follow/
    -->
    <script src="js/jquery-3.3.1.js"></script>
    <!-- Script de gestion de sélection via les deux <select...>
          et les deux boutons flash MoveLeft et MoveRight
    -->
    <script src="js/selections.js"></script>
</body>
</html>