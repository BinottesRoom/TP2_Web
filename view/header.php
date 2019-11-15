<?php
    include_once 'utilities/htmlHelper.php'; 

    $viewHead = html_open('div','header-layout');
    $viewHead .= '<div><img src="images/NoMoviePoster.png" class="logo"></div><div>'.html_header('Cinéma', 1).'</div>';
    $viewHead .= "<a href='listActors.php'><h3>Acteurs</h3></a>";
    $viewHead .= "<a href='listMovies.php'><h3>Films</h3></a>";
    /*
    if (isset($_SESSION['validUser'])) {
        if (isset($_SESSION['userId'])) {
            $currentUser = getUser($_SESSION['userId']);
            $userName = $currentUser['Name'];
            $userAvatarURL = imageHelper()->getURL($currentUser['AvatarGUID']);
            $viewHead .= '<div class="headerUsernameCell">'.$userName.'</div>';
            $viewHead .= '<div>'.html_icon($userAvatarURL, 'editProfilForm.php', 'Modifier votre profil', 'left', 'roundPhoto').'</div>';
        }
        $viewHead .= '<div>'.html_icon('images/Exit.png', 'logout.php', 'Logout', 'left', 'iconBig').'</div>';
    }
    */
    $viewHead .= html_close('div');
?>