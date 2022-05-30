<?php


#session_start();
#if (isset($_SESSION['valida']) && $_SESSION['valida'] == true){

@@ -19,45 +17,73 @@
    $anio = strip_tags($_POST["anio"]);
    $costo = strip_tags($_POST["costo"]);

    $imagen = "img/".$titulo.$grupo_id.$disquera_id;
    #move_uploaded_file($filetmpname, $imagen);

    if (!preg_match('/[a-z áéíóúñü]{2,50}/i', $titulo)) {
    if (!preg_match('/[a-z áéíóúñü 0-9\s]{2,50}/i', $titulo)) {
#        echo "no es alfanumerico titulo<br/>";
        pg_close($con);
        header('Location: form_disco.php?error=1');
    }
    if (!preg_match('/[a-z áéíóúñü]{2,50}/i', $genero)) {
    if (!preg_match('/[a-z áéíóúñü 0-9\s]{2,50}/i', $genero)) {
#        echo "no es alfanumerico genero<br/>";
        pg_close($con);
        header('Location: form_disco.php?error=1');
    }
    if (!(is_numeric($costo) && $costo >= 0)) {
#        echo "no es numero o es menor a 0<br/>";
        pg_close($con);
        header('Location: form_disco.php?error=1');
    }

    $insercion = "INSERT INTO discos (titulo, grupo_id, año, genero, disquera_id, productor_id, costo, portada) VALUES ('$titulo', '$grupo_id', '$anio', '$genero', '$disquera_id', '$productor_id', '$costo', '$imagen')";
    $query = pg_query($con, $insercion);


    if ($filetype == "image/jpeg" && $filesize <= 2000000) {
        move_uploaded_file($filetmpname, $imagen);
        $subir = true;
    } else if ($filetype == "image/png" && $filesize <= 2000000){
        move_uploaded_file($filetmpname, $imagen);
        $subir = true;
    } else {
#        echo "no cumple con especificaciones<br/>";
        pg_close($con);
        header('Location: form_disco.php?error=1');
    }


#    $consulta = "SELECT disco_id id from discos WHERE disco_id = 23";
    $consulta = "SELECT disco_id id from discos WHERE titulo = '$titulo' AND grupo_id = ".$grupo_id." AND productor_id= ".$productor_id." AND disquera_id = ".$disquera_id;

    #$consulta = "SELECT disco_id id from discos WHERE disco_id = 23";
#    echo $consulta.'<br/>';
    $disco = pg_query($con,$consulta);
    $disco = pg_fetch_assoc($disco);

#    echo $disco['id'].'<br/>';

    if (empty($disco)){

        $insercion = "INSERT INTO discos (titulo, grupo_id, año, genero, disquera_id, productor_id, costo, portada) VALUES ('$titulo', '$grupo_id', '$anio', '$genero', '$disquera_id', '$productor_id', '$costo', '')";
#        echo $insercion.'<br/>';
        $query = pg_query($con, $insercion);

        $consulta = "SELECT disco_id id from discos WHERE titulo = '$titulo' AND grupo_id = ".$grupo_id." AND productor_id= ".$productor_id." AND disquera_id = ".$disquera_id;
#        echo $consulta.'<br/>';
        $disco = pg_query($con,$consulta);
        $disco = pg_fetch_assoc($disco);

        if (!empty($disco)){
            $imagen = "img/".$titulo."-".$disco['id']."-".$grupo_id."-".$disquera_id;
            $insercion = "UPDATE discos SET portada = '$imagen' WHERE disco_id =".$disco['id'];
#            echo $insercion.'<br/>';
           $query = pg_query($con, $insercion);
            move_uploaded_file($filetmpname, $imagen);
        }

    /*
    } else {
#        echo "ya se encuentra registrado el disco<br/>";
       pg_close($con);
       header('Location: form_disco.php?error=1');
    }


/*   
    if (!empty($disco)){
        echo 'hola <br/>';
        echo 'hola <br/>'.$disco;
    }else{
        echo 'adios <br/>';
    }
    */
 */   

    $tituloCancionArr = $_POST['tituloCancion'];
    $compositorArr = $_POST['compositor'];
@@ -70,40 +96,47 @@

                //verifica que no exista el titulo de la cancion
                $consulta = "SELECT cancion_id id from canciones WHERE titulo = '$tituloCancion'";
#                echo $consulta.'<br/>';
                $resultado = pg_query($con,$consulta);
                $resultado = pg_fetch_assoc($resultado);
    #            echo $resultado['id'].'<br/>';
    #            echo $resultado.'<br/>';
#                echo $resultado['id'].'<br/>';

                if (empty($resultado['id'])){

                    #inserta cancion si no existe
                   $insercion = "INSERT INTO canciones (titulo) VALUES ('$tituloCancion')";
    #               echo $insercion.'<br/>';
#                   echo $insercion.'<br/>';
                    $query = pg_query($con, $insercion);

                    $consulta = "SELECT cancion_id id from canciones WHERE titulo = '$tituloCancion'";
     #               echo $consulta .'<br/>';
     #               $consulta = "SELECT cancion_id id from canciones WHERE cancion_id = 1";
#                    echo $consulta .'<br/>';
#                    $consulta = "SELECT cancion_id id from canciones WHERE cancion_id = 1";
                    $resultado = pg_query($con,$consulta);
                    $resultado = pg_fetch_assoc($resultado);
                }

                $insercion = "INSERT INTO cancion_compositor VALUES (".$resultado['id'].",".$compositor.") ON CONFLICT DO NOTHING";
    #            echo $insercion.'<br/>';
#                echo $insercion.'<br/>';
                $query = pg_query($con, $insercion);

                $insercion = "INSERT INTO disco_cancion VALUES (".$disco['id'].",".$resultado['id'].") ON CONFLICT DO NOTHING";
    #           echo $insercion.'<br/>';
#               echo $insercion.'<br/>';
                $query = pg_query($con, $insercion);

            #    echo $tituloCancion.'<br/>';
            #    echo $compositor.'<br/>';
#                echo $tituloCancion.'<br/>';
#                echo $compositor.'<br/>';
            } else {
#                echo "no se ingresaron compositor<br/>";
                pg_close($con);
                header('Location: form_disco.php?error=1');
            }
        }
    }else{
        echo 'no canciones';
#        echo "no se ingresaron canciones<br/>";
        pg_close($con);
        header('Location: form_disco.php?error=1');
    }

    pg_close($con);
#}

?> 
