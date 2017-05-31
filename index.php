<?php
// Array de configuracion de URLS de proyectos en localhost y de favoritos
$array_projects = json_decode( file_get_contents( 'config/projects.json' ), true ); 
$array_favorites = json_decode( file_get_contents( 'config/favorites.json' ), true );

// Configuracion de parametros 
$n_cols = 4;
$col_width = round( 50 / $n_cols, 2 ); 
$url_logo = 'images/header.png';

// Cabeceras, estilos, etc..
echo "<head>";
echo "<title>New TAB</title>";
echo '<meta http-equiv="Content-Security-Policy" content="default-src *; style-src \'self\' \'unsafe-inline\' http: https: data:; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: http: data:; img-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: http: data: content:;">';
echo "<link rel='stylesheet' type='text/css' href='css/index.css'>";
echo "</head><body>";

echo "<div class='container'>";

// Logo
echo "<div id='logo'><img src='$url_logo' /></div>";

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
    echo "<h2>Development Projects</h2>";

    foreach( $array_projects as $url ) {
        $url_parsed = parse_url( $url[ 'url' ] );
        $url_icon =  $url[ 'url' ] .'/images/favicon.ico';
        $str_image = Image_Process::base64_encode_image( $url_icon  ); 
        echo "<div class='column'>";
        echo "<a href='". $url[ 'url' ] ."' class='box box-project'>". $str_image .  str_replace( '.iternova.net', '', $url_parsed[ 'host' ] ) . "</a>";
        echo "</div>";
    }

    echo "</div>";
}
echo "</body>";


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