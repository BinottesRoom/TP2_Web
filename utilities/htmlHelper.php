<?php
//////////////////////////////////////////////////////////////////////////////////////////////
//
// Module de génération de code html
//
// Auteur : Nicolas Chourot dans le cadre du cours 420-KB9
// Date : 15 octobre 2019
//
//////////////////////////////////////////////////////////////////////////////////////////////
function html_BR() {
    return '<br>';
}

function html_HR() {
    return '<hr>';
}

function html_open($tag, $css=''){
    return "<$tag class='$css'>";
}

function html_close($tag){
    return "</$tag>";
}

function html_header($title, $size = 1){
    if ($size < 1 || $size > 6) $size = 1;  
    return "<h$size>$title</h$size>";
}

function html_label($title, $for){
    return "<label for='$for' class='label label-default'>$title</label>";
}

function html_password($name, $placeholder, $value = '', $css='form-control') {
    return 
    "<input 
    type='password' 
    name='$name'   
    id='$name' 
    value =\"$value\" 
    placeholder=\"$placeholder\" 
    class='$css'>";
}

function html_textbox($name, $placeholder, $value = '', $css='form-control') {
    return
    "<input 
    name='$name' 
    id='$name' 
    placeholder=\"$placeholder\" 
    value =\"$value\" 
    class='$css'>";
}

function html_textarea($name, $placeholder, $rows, $value = '', $css='form-control') {
    return
    "<textarea 
    name='$name' 
    rows='$rows' 
    id='$name' 
    placeholder=\"$placeholder\" 
    class='$css'>$value</textarea>";
}

function html_datepicker($name, $placeholder, $value = '', $css='form-control') {
    return
    html_label($placeholder,$name).
    "
    <input
    type='date'
    name='$name' 
    id='$name' 
    value =\"$value\" 
    class='$css'>";
}



function html_Hidden($name, $value) {
    return "<input type='hidden' name='$name' value=\"$value\">";
}


function html_checkbox($name, $caption, $css='') {
    return
    "<input 
    type='checkbox' 
    name='$name' 
    id='$name' 
    value=\"$caption\" 
    class='$css'>".
    html_label('&nbsp;'.$caption, $name);
}

function html_orderedList($values, $css=''){
    $html = "<ol class='$css'>";
    foreach($values as $value){
        $html .= "<li>$value</li>";
    }
    $html .= '</ol>';
    return $html;
}

function html_selectOption($value, $caption, $disabled=false){
    return '<option '.($disabled?'selected disabled':'')." value=\"$value\">$caption</option>";
}

function html_selectedOption($value, $caption){
    return "<option selected value='$value'>$caption</option>";
}

function html_combobox($name, $caption, $values, $selectedKey = 0, $css='form-control'){
    $html = "<select name='$name' id='$name' class='$css'>";
    $html .= html_selectOption('0',$caption, true);
    foreach($values as $key => $value){
        if ($key === $selectedKey)
            $html .= html_selectedOption($key,$value);
        else
            $html .= html_selectOption($key,$value);
    }
    $html .= '</select>';
    return $html;
}
function html_listbox($name, $caption, $values, $size, $css, $multipleSelect = 'true'){
    $multipleAttribute = '';
    $nameAttribute = $name;
    if ($multipleSelect){
        $multipleAttribute = ' multiple ';
        $nameAttribute .= [];
    }
    $html = "<select name='$nameAttribute' id='$name' class='$css' $multipleAttribute size='$size'>";
    $html .= html_label($caption, $name);
    foreach($values as $key => $value){
        $html .= html_selectOption($key,$value);
    }
    $html .= '</select>';
    return $html;
}

function html_tableCell($content, $css=''){
    return "<td class='$css'>$content</td>";
}

function html_table($values, $css='table'){
    $html = "<table class='$css'>";
    foreach($values as $key => $value){
        $html .= '<tr>';
        $html .= html_tableCell($key);
        $html .= html_tableCell($value);
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

function html_errorMessage($id, $message){
    return "<span id='$id' style='color:red'>$message</span>";
}

function html_image($imageFile, $css=''){
    return "<img 
    src='$imageFile' 
    alt='$imageFile' 
    class='$css'/>";
}

function html_link($link, $caption, $target = '_self'){
    return "<a href='$link' target='$target'>$caption</a>";
}

function html_icon($imageFile, $link, $tooltip, $position = 'top', $css='icon'){
    $html = "<a href='$link' tooltip=\"$tooltip\" tooltip-position='$position'>";
    $html .= html_image($imageFile, $css);
    $html .= '</a>';
    return $html;
}
function html_flashButton($flashButtonType, $link, $tooltip, $position = 'top'){
    $html = "<a href='$link' class='flashButton $flashButtonType 'tooltip=\"$tooltip\" tooltip-position='$position'>";
    $html .= '</a>';
    return $html;
}

function html_frame($content, $css=''){
    return "<iframe src='$content' class='$css'></iframe>";
}

function html_beginForm($action, $id, $multipart = false){
    $html = "<form  id='$id' action='$action' method='POST'";
    if ($multipart)
        $html .= "enctype='multipart/form-data'";
    $html .= '>';
    return $html;
}

function html_closeForm() {
    return '</form>';
}

function html_submit($name, $caption, $css='form-control') {
    return "<input type='submit' name='$name' id='$name' value=\"$caption\" class='$css'>";
}

function html_button($id, $caption, $css='form-control') {
    return "<button id='$id' class='$css'>$caption</button>";
}

function html_linkButton($caption, $url, $css='form-control') {
    return "<a href='$url' class='$css'>$caption</a>";
}

function html_faviconLinkFromUrl($url){
    $parts = explode('/', $url);
    $html  = '<a href="'.$url.'" target="_blank"><img class="favicon" alt="" src="'.$parts[0].'//'.$parts[2].'/favicon.ico"/></a>';
    return $html;
 }

 
?>