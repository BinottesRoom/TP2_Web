<?php

// retourne vrai si l'item fait partie de la sélection
function inSelection($selections, $item){
    foreach($selections as $s){
        if ($s[0] === $item[0])
            return true;
    }
    return false;
}

// produire le html d'un controle <select...> avec une liste
// qui présente les items
function makeSelectedList($items){
    $html = "<select id='SelectedItems' name='SelectedItems[]' size='12' multiple>";
    foreach($items as $item) {
        $id =$item[0];
        $name = $item[1];
        $html .= "<option value='$id'>$name</option>";
    }
    $html .= "</select>";
    return $html; 
}

// produire le html d'un controle <select...> avec une liste
// qui présente les items qui ne font pas partie de 
// d'items sélectionnés se trouvant dans $selectedItems
function makeUnselectedList($items, $selectedItems){
    $html = "<select id='UnselectedItems' size='12' multiple>";
    foreach($items as $item) {
        if (!inSelection($selectedItems, $item)){
            $id =$item[0];
            $name = $item[1];
            $html .= "<option value='$id'>$name</option>";
        }
    }
    $html .= "</select>";
    return $html; 
}
?>