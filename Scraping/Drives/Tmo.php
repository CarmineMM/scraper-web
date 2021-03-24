<?php 

namespace Scraping\Drives;

class Tmo 
{
    private $colors;

    public function __construct($colors) 
    {
        $this->colors = $colors;    
    }

    
    /**
     * Obtiene la info de un manga especifico.
     *
     * @param string $url
     * @param integer $start
     * @param boolean $end
     * @return void
     */
    public function downloadManga(string $url, int $start = 1, int $end = 0): void
    {
        echo $this->colors->getColoredString("Identificando el Manga, espere...", 'black', 'light_gray');
        echo "\n\n";

        //
        // URL hacia el manga
        //
        $html = file_get_html($url);

        if ( !$html ) {
            echo "\n\n";
            echo $this->colors->getColoredString("No se pudo establecer una conexión", 'white', 'red');
            echo "\n\n";
        }
    
        //
        // Obtener información del Manga
        //
        $manga = $html->find('h2.element-subtitle')[0]->innertext ?? false;

        // Manga no identificado
        if ( !$manga ) {
            echo $this->colors->getColoredString("El manga no pudo ser identificado:", 'white', 'red');
            echo "\n\n";
            echo $this->colors->getColoredString("Asegúrese de tener la URL y del Drive correcto:", 'yellow');
            echo "\n";
            echo $this->colors->getColoredString("URL: {$url}", 'yellow');
            echo "\n";
            echo $this->colors->getColoredString("Drive: tmo", 'yellow');
            die();
        }

        echo "Descargando: ".$this->colors->getColoredString($manga, 'black', 'cyan');

        //
        // Carpeta y archivos
        //
        $path = ROOT_PATH.'manga'.DG.slug($manga);
        if ( !is_dir($path) ) {
            mkdir($path, 0777, true);
        }
        echo "\nGuardar en:  {$this->colors->getColoredString($path, 'dark_gray', 'cyan')}\n\n";

        //
        // Todos los capítulos
        //
        $allChapters = $html->find('#chapters li.list-group-item.p-0.bg-light.upload-link');
        echo $this->colors->getColoredString("\tObteniendo capítulos, espere...", 'black', 'light_gray');
        echo "\n\n";

        // Recorrer
        $chapter = 1;
        $countChapter = $end === 0 ? count($allChapters) : $end;
        $totalChapter = $countChapter;
        
        if ( $countChapter <= 10 )      $totalChapter = "00".$countChapter;
        elseif ( $countChapter <= 100 ) $totalChapter = "0".$countChapter;

        for ($i = count($allChapters)-1; $i >= 0; $i--) { 
            
            // Evita la ejecución en caso de no comenzar a descargar el capitulo
            if ( $chapter < $start ) {
                $chapter++;
                continue;
            }

            // Sale del FOR en caso de acabar
            if ($end !== 0 && $chapter > $end) break;
            
            //
            // Numero crudo del capitulo
            //
            $numChapter = "00".$chapter;
            if ( $chapter >= 100 ) $numChapter = $chapter;
            elseif ( $chapter >= 10 ) $numChapter = "0".$chapter;

            //
            // Nombre del capitulo
            //
            $nameChapter = str_replace(':', ' ', 
                str_replace('&nbsp;&nbsp;', '', $allChapters[$i]->find('a.btn-collapse')[0]->innertext)
            );
            $nameChapter = trim(str_replace('<i class="fa fa-chevron-down fa-fw"></i>', '', $nameChapter), ' ');

            echo "\n{$numChapter}/{$totalChapter} - Descargando ";
            echo $this->colors->getColoredString($nameChapter, 'blue')."\n\n";

            //
            // URL de la re-dirección
            //
            $linkTo = file_get_html( $allChapters[$i]->find('.btn.btn-default.btn-sm')[0]->href );

            //
            // Descargar imágenes del capitulo
            //
            $this->getChapter(
                $linkTo->find('meta[property="og:url"]')[0]->attr['content'], 
                $path.DG."{$numChapter} - {$nameChapter}"
            );

            echo "\n{$numChapter}/{$totalChapter} - ";
            echo $this->colors->getColoredString($nameChapter, 'blue');
            echo " ¡DESCARGADO!\n\n";
            $chapter++;
        }

        echo "MANGA: ".$this->colors->getColoredString($manga, 'black', 'cyan') . " YA FUE DESCARGADO.";
    }

    

    /**
     * Descarga un episodio especifico.
     *
     * @param string $url
     * @param boolean $folder
     * @return void
     */
    public function getChapter(string $url, $folder = false) 
    {
        $url  = str_replace('paginated', 'cascade',  $url);
        $html = file_get_html($url);
        $save = 1;
        $folder = $folder ?? 'manga';
        $countErrors = 1;
        
        // Crea carpeta, en caso existir.
        if ( !is_dir($folder) ) {
            mkdir($folder, 0777, true);
        }

        $images = $html->find('img.viewer-img');
        $countImages = count($images)/2;
        
        if ( count($images)/2 < 10 )      $countImages = "00".count($images)/2;
        elseif ( count($images)/2 < 100 ) $countImages = "0".count($images)/2;

        foreach ($images as $key => $value) {
            if ( $key % 2 !== 0 ) continue;

            $nameSave = "00".$save;

            if ( $save >= 100 ) $nameSave = $save;
            elseif ( $save >= 10 ) $nameSave = "0".$save;

            echo "Imagen N°: {$nameSave}/{$countImages} Guardando...\n";
            $ext = substr($value->attr['data-src'], strrpos($value->attr['data-src'], '.'));
            $file = "{$folder}/{$nameSave}{$ext}";

            //
            // Verifica si ya existe esta imagen
            //
            if ( is_file($file) ) 
                echo "Imagen N°: {$nameSave}/{$countImages} ¡YA EXISTE!\n\n";    
            else {
                $image = file_get_contents($value->attr['data-src']);

                //
                // Error al Obtener la imagen
                //
                if ( !$image ) {
                    echo $this->colors->getColoredString("No se pudo guardar la imagen", 'white', 'red');
                    echo "\n\n";
                    $countErrors++;
                }
                // Guardar la imagen satisfactoriamente.
                else {
                    file_put_contents($file, $image);
                    echo "Imagen N°: {$nameSave}/{$countImages} ¡SALVADA!\n\n";
                }
            }

            $save++;

            if ( $countErrors > 10 ) {
                echo "\n\n";
                echo $this->colors->getColoredString("Se ha alcanzado el limite máximo de errores a la hora de descargar las Imágenes.", 'white', 'red');
                echo "\n\n";
                die();
            }  
        }
    }
}

