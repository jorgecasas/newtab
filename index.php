<?php
// Version 20171218
// Array de configuracion de URLS de proyectos en localhost y de favoritos
$array_projects = json_decode( file_get_contents( 'config/projects.json' ), true ); 
$array_favorites = json_decode( file_get_contents( 'config/favorites.json' ), true );
$array_issues = json_decode( file_get_contents( 'config/issues.json' ), true );
$array_fortune = explode( '%', file_get_contents( 'config/fortunes' ) );

// Configuracion de parametros 
$n_cols = 4;
$col_width = round( 50 / $n_cols, 2 ); 
$url_logo = 'images/header.png';
$url_ico_issues = 'images/gitlab.ico';
$issues_open_on_project = false; 

// Cabeceras, estilos, etc..
echo "<head>";
echo "<title>New TAB</title>";
echo '<meta http-equiv="Content-Security-Policy" content="default-src *; style-src \'self\' \'unsafe-inline\' http: https: data:; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: http: data:; img-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: http: data: content:;">';
echo "<link rel='stylesheet' type='text/css' href='css/index.css'>";
echo "<link rel='shortcut icon' id='favicon-base' type='image/x-icon' href='images/iternova.ico' />";
echo "</head><body>";

echo "<div class='container'>";

// Datetime in UTC+0
$date_utc = new \DateTime("now", new \DateTimeZone("UTC"));
echo "<div id='datetime'><b>UTC+0:</b> " . $date_utc->format( 'Y-m-d H:i' ). "</div>";

// Logo
echo "<div id='logo' class='column'><img src='$url_logo' /></div>";

// Fortunes
echo "<div id='fortune'>" . utf8_decode( trim( $array_fortune[ array_rand( $array_fortune ) ] ) ). "</div>";

echo '</div><div class="container">';

// Generacion de botones de accesos rapidos
if ( !empty( $array_favorites ) ) {
    foreach( $array_favorites as $url ) {
        $str_image = ( isset(  $url[ 'icon' ]) ? "<img class='thumbnail' src='". $url[ 'icon' ] ."' />" : '' );
        echo "<div class='column'>";
        echo "<a href='".$url['url']."' class='box'>". $str_image . $url[ 'name' ] . "</a>";
        echo "</div>";
      
    }
    echo "</div>";
}

echo "</div>";

// New fast access to localhost and development projects
if ( !empty( $array_projects ) ) {
    echo "<div class='separator'></div>";
    echo "<div class='container'>";
    echo "<h2>DEV Projects</h2>";

    foreach( $array_projects as $url ) {
        $background = ( isset(  $url[ 'background' ]) ? $url[ 'background' ] : '' );
        $url_issues = ( isset(  $url[ 'issues' ]) ? $url[ 'issues' ] : '' );
        $url_parsed = parse_url( $url[ 'url' ] );
        $str_image = ( isset( $url[ 'icon' ] ) ? "<img class='thumbnail thumbnail-project' src='". $url[ 'icon' ] ."' />" : Image_Process::base64_encode_image( $url[ 'url' ] . '/images/favicon.ico' ) );
        $str_title = ( isset( $url[ 'name' ] ) ? $url[ 'name' ] : str_replace( '.iternova.net', '', $url_parsed[ 'host' ] ) ); 
        
        echo "<div class='column'>";
        if ( !empty( $url_issues ) ) { 
            echo "<a href='". $url_issues ."' class='box-issue box-project'><img class='thumbnail thumbnail-project' src='" . $url_ico_issues . "' /></a>";
            echo "<a href='". $url[ 'url' ] ."' " . ( $issues_open_on_project ? "onclick='window.open(\"". $url_issues ."\");' " : '' ) . "class='box-small box-project " . $background . "'>". $str_image .  $str_title . "</a>";
        } else {
            echo "<a href='". $url[ 'url' ] ."' class='box box-project'>". $str_image .  $str_title . "</a>";
        }
        echo "</div>";
    }

    echo "</div>";
}


// New fast access to Gitlab ISSUES
if ( !empty( $array_issues ) ) {
    echo "<div class='separator'></div>";
    echo "<div class='container'>";
    echo "<h2>Gitlab Issues</h2>";

    foreach( $array_issues as $url ) {
        $str_image = ( isset(  $url[ 'icon' ]) ? "<img class='thumbnail thumbnail-project' src='". $url[ 'icon' ] ."' />" : '' );
        echo "<div class='column'>";
        echo "<a href='".$url['url']."' class='box box-project'>". $str_image . $url[ 'name' ] . "</a>";
        echo "</div>";
    }

    echo "</div>";
}
echo "</body>";

echo "<div class='separator'></div>";

/**
 * Clase para procesar iconos que pueden ser de webs externas
 */
class Image_Process {
    
    /**
     * Devuelve imagen formateada en base64. Necesario para la correcta visualizacion de las imagenes de documentos Microsoft Word por ejemplo...
     *
     * @param string $url URI de la imagen
     *
     * @return string Codigo HTML base64 de la imagen codificada
     */
    public static function base64_encode_image( $url ) {
        // Para evitar que SSL compruebe validez de certificados HTTPS y obtenga el logo correctamente
        $array_context_options = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );
        $imgbinary = file_get_contents( $url, false, stream_context_create( $array_context_options ) );
        if ( $imgbinary !== false ) {
            $filetype = pathinfo( $url, PATHINFO_EXTENSION );
            return "<img class='thumbnail thumbnail-project' src='data:image/" . $filetype . ";base64," . base64_encode( $imgbinary ) . "' />";
           
        } else {

            return "<img class='thumbnail thumbnail-project' src='". $url ."' />";
        }
    }
}
