<?php 

namespace Scraping;

use Exception;
use Scraping\Helpers\Colors;

class Foundation 
{
    /**
     * Path del Scraper
     * 
     * @var string
     */
    protected string $path;

    /**
     * Configuración del usuario
     * 
     * @var array
     */
    private object $config;

    /**
     * Drives disponibles
     */
    private array $drives = [
        'tmo' => 'Tmo'
    ];

    /**
     * Colorear en consola
     *
     * @var Colors
     */
    protected $colors;
    

    /**
     * Constructor
     *
     * @param string $path
     */
    public function __construct(string $fileConfig, Colors $colors) 
    {
        $this->path    = ROOT_PATH;
        $fileConfig    = ROOT_PATH.$fileConfig;
        $this->colors  = $colors;

        // Importar Archivo de configuración
        if ( !is_file($fileConfig) ) 
            throw new Exception( $this->colors->getColoredString("Archivo no encontrado: ".$fileConfig, 'white', 'red')."\n\n" );
        else $this->config = (object) require_once $fileConfig;
        
        // Importa archivos de funciones
        $this->imports(
            ROOT_PATH.'Scraping/Helpers/functions.php',
            ROOT_PATH.'Scraping/vendor/simple_html_dom.php'
        );
    }

    /**
     * Importa archivos
     *
     * @param string ...$files
     * @return void
     */
    private function imports(...$files): void
    {
        foreach ($files as $file) {
            if ( !is_file($file) ) 
                throw new Exception( $this->colors->getColoredString("Archivo no encontrado: ".$file, 'white', 'red')."\n\n" );
            else require_once $file;
        }
    }

    /**
     * Verifica si los parámetros pasados son soportados.
     *
     * @return Foundation
     */
    public function checkParams(): Foundation
    {
        // Verifica los Drives posibles
        if ( !isset($this->drives[$this->config->drive]) ) {
            throw new Exception( 
                $this->colors->getColoredString("Drive no encontrado: ".$this->config->drive, 'white', 'red') 
                ."\n\n"
            );            
        }

        // Verifica la URL
        if ( 
            gettype( $this->config->url ) !== 'string' || 
            strpos($this->config->url, 'http') === false 
        ) {
            throw new Exception( 
                $this->colors->getColoredString("La URL especificada no es correcta: ".$this->config->url, 'white', 'red') 
                ."\n\n"
            );
        }

        // Verifica el numero de inicio
        if ( $this->config->start < 0 ) {
            throw new Exception( 
                $this->colors->getColoredString("El numero de inicio no es posible: ".$this->config->start, 'white', 'red') 
                ."\n\n"
            );
        }

        return $this;
    }


    /**
     * Ejecuta el Scraping
     *
     * @return void
     */
    public function execute(): void
    {
        $drive = "\Scraping\Drives\\".$this->drives[$this->config->drive];

        $instance = new $drive($this->colors);

        echo $this->colors->getColoredString("\nScraper By Carmine Maggio <carminemaggiom@gmail.com>", 'green');
        echo "\n\n\t";

        //
        // Si es falso, es que es un capitulo,
        // en caso contrario, es un manga o serie.
        //
        if ( strpos($this->config->url, 'library') ) {
            $instance->downloadManga($this->config->url, $this->config->start, $this->config->end);
        }
        else $instance->getChapter($this->config->url, ROOT_PATH.'manga');
        

        echo "\n";
        echo $this->colors->getColoredString("\n\tScraper By Carmine Maggio <carminemaggiom@gmail.com>\n\n", 'green');
    }

    /**
     * Ejecuta el Scraper
     *
     * @param string $path
     * @return void
     */
    public static function run(string $fileConfig): void
    {
        $colors = new Colors;

        try {
            $self = new self($fileConfig, $colors);
            $self->checkParams()->execute();
        } catch (Exception $th) {
            die($th);
        }
    }
}