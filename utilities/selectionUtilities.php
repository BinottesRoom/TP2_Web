<?php
include_once "DAL/DBA.php";
// retourne vrai si l'item fait partie de la sélection
function inSelection($selections, $id){
    return isset($selections[$id]);
}

// produire le html d'un controle <select...> avec une liste
// qui présente les items
function makeSelectedList($items){
    $i = 0;
    $html = "<select id='SelectedItems' name='SelectedItems[]' size='12' multiple class='form-control'>";
    foreach($items as $id => $item) {
        $html .= "<option value='$id'>$item</option>";
    }
    $html .= "</select>";
    return $html; 
}

function SelectedDetails($items){
    $i = 0;
    $id = 0;
    $valTemp;
    $html = "<div multiple class='actorsList'>";
    foreach($items as $objet) {
        if ($i == 0) //id
        {
            $html .= "<div style='color:blue;font-size: 25px;'>$objet</div>";
            $valTemp = Actors()->selectWhere("Name='$objet'");
            $id = $valTemp[0][0];
            $i++;
        }
        else //image
        {
            $html .= "<a href='detailsActorForm.php?id=$id'>";
            $html .= html_image("images/$objet", 'photoMedium')."</a>";
            $i--;
        }
    }
    $html .= "</div>";
    return $html; 
}

// produire le html d'un controle <select...> avec une liste
// qui présente les items qui ne font pas partie de 
// d'items sélectionnés se trouvant dans $selectedItems
function makeUnselectedList($items, $selectedItems){
    $html = "<select id='UnselectedItems' size='12' multiple class='form-control'>";
    foreach($items as $id => $item) {
        if (!inSelection($selectedItems, $id)){
            $html .= "<option value='$id'>$item</option>";
        }
    }
    $html .= "</select>";
    return $html; 
}
?>